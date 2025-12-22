<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\AdminService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Mostrar página de contacto
     */
    public function show()
    {
        return view('contact');
    }

    /**
     * Procesar formulario de contacto o solicitud de producto
     */
    public function send(Request $request)
    {
        // Validación base
        $baseRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'product_id' => 'nullable|integer',
        ];

        $validator = Validator::make($request->all(), $baseRules);
        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Datos inválidos', 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        // Datos compartidos
        $shared = [
            'name' => $data['name'],
            'email' => $data['email'],
            'message' => $data['message'] ?? '',
            'sent_at' => now()->toDateTimeString(),
        ];

        $adminEmail = AdminService::getAdminEmail();

        try {
            if (!empty($data['product_id'])) {
                // Solicitud de información de producto
                $v2 = Validator::make($request->all(), ['message' => 'required|string']);
                if ($v2->fails()) {
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json(['success' => false, 'message' => 'El mensaje es requerido.'], 422);
                    }
                    return redirect()->back()->withErrors($v2)->withInput();
                }
                $data['message'] = $request->input('message');
                $product = Product::find($data['product_id']);
                $payload = array_merge($shared, [
                    'product_id' => $data['product_id'],
                    'product_name' => $product->name ?? 'Desconocido',
                    'product_url' => isset($product) ? route('catalog.show', $product) : null,
                ]);

                Mail::to($adminEmail)->send(new \App\Mail\ProductRequestMail($payload));
                Mail::to($data['email'])->send(new \App\Mail\ProductRequestConfirmationMail($payload));

                $message = 'Solicitud enviada correctamente. Nos contactaremos pronto.';
            } else {
                // Formulario de contacto general
                $v3 = Validator::make($request->all(), [
                    'subject' => 'required|string|max:255',
                    'message' => 'required|string',
                ]);
                if ($v3->fails()) {
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json(['success' => false, 'message' => 'Asunto y mensaje son requeridos.', 'errors' => $v3->errors()], 422);
                    }
                    return redirect()->back()->withErrors($v3)->withInput();
                }

                $payload = array_merge($shared, ['subject' => $request->input('subject'), 'message' => $request->input('message')]);

                Mail::to($adminEmail)->send(new \App\Mail\ContactMessageMail($payload));
                Mail::to($data['email'])->send(new \App\Mail\ContactConfirmationMail($payload));

                $message = 'Mensaje enviado correctamente';
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $message]);
            }

            return redirect()->route('contact')->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Mail send failed', ['error' => $e->getMessage()]);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Ocurrió un error al enviar el correo. Por favor intenta nuevamente.'], 500);
            }
            return redirect()->back()->withInput()->with('error', 'Ocurrió un error al enviar el correo. Por favor intenta nuevamente.');
        }
    }
}
