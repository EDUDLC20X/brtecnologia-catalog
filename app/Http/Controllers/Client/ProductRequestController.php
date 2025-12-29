<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ProductRequest;
use App\Models\Product;
use App\Mail\ProductRequestMail;
use App\Mail\ProductRequestConfirmationMail;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

/**
 * ProductRequestController
 * 
 * Gestiona las solicitudes de información/cotización de productos.
 * Usuarios autenticados: solicitudes vinculadas a su cuenta
 * Visitantes: solicitudes con datos manuales
 */
class ProductRequestController extends Controller
{
    /**
     * Muestra las solicitudes del usuario autenticado
     * Soporta filtrado por estado (pending, contacted, quoted, completed, cancelled)
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $status = $request->query('status');
        
        $query = $user->productRequests()
            ->with(['product.mainImage'])
            ->latest();
        
        // Filtrar por estado si se especifica
        if ($status && in_array($status, ['pending', 'contacted', 'quoted', 'completed', 'cancelled'])) {
            $query->where('status', $status);
        }
        
        $requests = $query->paginate(10);
        
        // Mantener el filtro en la paginación
        if ($status) {
            $requests->appends(['status' => $status]);
        }
        
        return view('client.requests.index', compact('requests', 'status'));
    }

    /**
     * Muestra el detalle de una solicitud
     */
    public function show(Request $request, ProductRequest $productRequest)
    {
        // Verificar que la solicitud pertenece al usuario
        if ($productRequest->user_id !== $request->user()->id) {
            abort(403, 'No tienes permiso para ver esta solicitud.');
        }
        
        $productRequest->load(['product.mainImage', 'product.category']);
        
        return view('client.requests.show', compact('productRequest'));
    }

    /**
     * Crea una nueva solicitud de producto
     * Disponible para usuarios autenticados y visitantes
     */
    public function store(Request $request, Product $product)
    {
        $user = $request->user();
        
        // Validación diferente para usuarios autenticados vs visitantes
        $rules = [
            'quantity' => 'nullable|integer|min:1|max:9999',
            'message' => 'nullable|string|max:1000',
        ];
        
        if (!$user) {
            $rules = array_merge($rules, [
                'name' => 'required|string|max:100',
                'email' => 'required|email|max:100',
                'phone' => 'nullable|string|max:20',
                'company' => 'nullable|string|max:100',
            ]);
        }
        
        $validated = $request->validate($rules, [
            'name.required' => 'Por favor ingresa tu nombre.',
            'email.required' => 'Por favor ingresa tu correo electrónico.',
            'email.email' => 'Por favor ingresa un correo electrónico válido.',
            'quantity.min' => 'La cantidad mínima es 1.',
            'message.max' => 'El mensaje no puede exceder 1000 caracteres.',
        ]);
        
        // Crear la solicitud
        $productRequest = ProductRequest::create([
            'user_id' => $user?->id,
            'product_id' => $product->id,
            'name' => $user?->name ?? $validated['name'],
            'email' => $user?->email ?? $validated['email'],
            'phone' => $user?->phone ?? ($validated['phone'] ?? null),
            'company' => $validated['company'] ?? null,
            'quantity' => $validated['quantity'] ?? 1,
            'message' => $validated['message'] ?? null,
            'status' => ProductRequest::STATUS_PENDING,
        ]);
        
        Log::info('Nueva solicitud de producto creada', [
            'request_id' => $productRequest->id,
            'product_id' => $product->id,
            'user_id' => $user?->id,
        ]);
        
        // Enviar correos de notificación (sin bloquear la respuesta)
        $this->sendNotifications($productRequest, $product);
        
        // Si es una solicitud AJAX, devolver JSON
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Tu solicitud ha sido enviada correctamente. Te contactaremos pronto.',
                'request_id' => $productRequest->id,
            ]);
        }
        
        // Si es una solicitud normal (formulario), redirigir con mensaje de éxito
        return redirect()->back()->with('success', '¡Tu solicitud ha sido enviada correctamente! Te contactaremos pronto.');
    }

    /**
     * Cancela una solicitud pendiente
     */
    public function cancel(Request $request, ProductRequest $productRequest): JsonResponse
    {
        // Verificar que la solicitud pertenece al usuario
        if ($productRequest->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para cancelar esta solicitud.',
            ], 403);
        }
        
        // Solo se pueden cancelar solicitudes pendientes
        if ($productRequest->status !== ProductRequest::STATUS_PENDING) {
            return response()->json([
                'success' => false,
                'message' => 'Solo se pueden cancelar solicitudes pendientes.',
            ], 400);
        }
        
        $productRequest->update(['status' => ProductRequest::STATUS_CANCELLED]);
        
        return response()->json([
            'success' => true,
            'message' => 'Solicitud cancelada correctamente.',
        ]);
    }

    /**
     * Envía notificaciones por correo electrónico
     */
    protected function sendNotifications(ProductRequest $productRequest, Product $product): void
    {
        // Datos formateados para los emails
        $data = [
            'name' => $productRequest->name,
            'email' => $productRequest->email,
            'phone' => $productRequest->phone,
            'company' => $productRequest->company,
            'product_name' => $product->name,
            'product_sku' => $product->sku_code,
            'quantity' => $productRequest->quantity,
            'message' => $productRequest->message ?? 'Sin mensaje adicional',
            'product_url' => route('catalog.show', $product),
            'sent_at' => now()->format('d/m/Y H:i'),
        ];
        
        // Notificar al administrador
        $adminEmail = config('mail.admin_email', config('mail.from.address'));
        if ($adminEmail) {
            try {
                MailService::send(
                    $adminEmail,
                    new ProductRequestMail($data),
                    'product-request-admin-' . $productRequest->id
                );
            } catch (\Exception $e) {
                Log::error('Error enviando email de solicitud al admin: ' . $e->getMessage());
            }
        }
        
        // Confirmación al cliente
        try {
            MailService::send(
                $productRequest->email,
                new ProductRequestConfirmationMail($data),
                'product-request-confirmation-' . $productRequest->id
            );
        } catch (\Exception $e) {
            Log::error('Error enviando email de confirmación al cliente: ' . $e->getMessage());
        }
    }
}
