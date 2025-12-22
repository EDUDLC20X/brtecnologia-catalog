<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteContent;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ContentController extends Controller
{
    /**
     * Tamaño máximo de imagen en bytes (2MB)
     */
    private const MAX_IMAGE_SIZE = 2 * 1024 * 1024;
    
    /**
     * Formatos de imagen permitidos
     */
    private const ALLOWED_FORMATS = ['jpeg', 'jpg', 'png', 'gif', 'webp'];

    /**
     * Display all site content for editing
     */
    public function index()
    {
        $sections = SiteContent::getAllForAdmin();

        return view('admin.content.index', compact('sections'));
    }

    /**
     * Edit a specific section
     */
    public function editSection(string $section)
    {
        $sectionLabels = [
            'global' => 'Configuración Global',
            'home' => 'Página de Inicio',
            'about' => 'Página Acerca de',
            'contact' => 'Información de Contacto',
            'banners' => 'Banners Promocionales',
        ];

        if (!array_key_exists($section, $sectionLabels)) {
            return redirect()->route('admin.content.index')
                ->with('error', 'Sección no encontrada');
        }

        $contents = SiteContent::where('section', $section)
            ->orderBy('order')
            ->get();

        $sectionLabel = $sectionLabels[$section];

        return view('admin.content.edit-section', compact('contents', 'section', 'sectionLabel'));
    }

    /**
     * Update section contents
     */
    public function updateSection(Request $request, string $section)
    {
        $contents = SiteContent::where('section', $section)->get();
        $errors = [];

        foreach ($contents as $content) {
            $fieldName = str_replace('.', '_', $content->key);
            
            // Handle image uploads
            if ($content->type === 'image') {
                if ($request->hasFile("image_{$fieldName}")) {
                    $file = $request->file("image_{$fieldName}");
                    
                    // Validar formato
                    $extension = strtolower($file->getClientOriginalExtension());
                    if (!in_array($extension, self::ALLOWED_FORMATS)) {
                        $errors[] = "'{$content->label}': Formato no válido. Use: " . implode(', ', self::ALLOWED_FORMATS);
                        continue;
                    }
                    
                    // Validar tamaño
                    if ($file->getSize() > self::MAX_IMAGE_SIZE) {
                        $errors[] = "'{$content->label}': La imagen excede el tamaño máximo de 2MB";
                        continue;
                    }
                    
                    // Validar que sea una imagen real
                    if (!$file->isValid() || !getimagesize($file->getRealPath())) {
                        $errors[] = "'{$content->label}': El archivo no es una imagen válida";
                        continue;
                    }

                    // Guardar referencias de imagen anterior para eliminar después
                    $oldImagePath = $content->image_path;
                    $oldCloudinaryId = $content->cloudinary_public_id;
                    
                    Log::info("Procesando imagen para: {$content->key}", [
                        'old_path' => $oldImagePath,
                        'old_cloudinary_id' => $oldCloudinaryId,
                    ]);

                    // Try Cloudinary first if configured
                    $cloudinary = app(CloudinaryService::class);
                    $path = null;
                    $cloudinaryPublicId = null;
                    
                    if ($cloudinary->isConfigured()) {
                        $result = $cloudinary->upload($file, 'brtecnologia/content');
                        if ($result) {
                            $path = $result['url'];
                            $cloudinaryPublicId = $result['public_id'];
                            Log::info("Imagen subida a Cloudinary: {$cloudinaryPublicId}");
                        }
                    }
                    
                    // Fallback to local storage
                    if (!$path) {
                        // Primero eliminar archivo local anterior si existe
                        $this->deleteLocalImage($content->key);
                        
                        // Guardar nuevo archivo con nombre único basado en el key
                        $safeName = str_replace('.', '-', $content->key) . '.' . $extension;
                        $path = $file->storeAs('content', $safeName, 'public');
                        Log::info("Imagen guardada localmente: {$path}");
                    }
                    
                    // Actualizar con nueva imagen
                    $content->update([
                        'image_path' => $path,
                        'cloudinary_public_id' => $cloudinaryPublicId,
                    ]);

                    // AHORA eliminar imagen anterior de Cloudinary (después de guardar la nueva)
                    if ($oldCloudinaryId && $oldCloudinaryId !== $cloudinaryPublicId) {
                        $cloudinary->delete($oldCloudinaryId);
                        Log::info("Imagen anterior eliminada de Cloudinary: {$oldCloudinaryId}");
                    }
                }
            } else {
                // Handle text/textarea/html content
                if ($request->has("content_{$fieldName}")) {
                    $value = $request->input("content_{$fieldName}");
                    
                    // Sanitize HTML if type is html
                    if ($content->type === 'html') {
                        $value = $this->sanitizeHtml($value);
                    }
                    
                    $content->update(['value' => $value]);
                }
            }
        }

        // Clear cache for the section
        SiteContent::clearCache();

        if (!empty($errors)) {
            return redirect()->route('admin.content.section', $section)
                ->with('warning', 'Algunos cambios se guardaron, pero hubo errores:')
                ->withErrors($errors);
        }

        return redirect()->route('admin.content.section', $section)
            ->with('success', 'Contenido actualizado correctamente');
    }

    /**
     * Eliminar imagen local para un content key específico
     */
    private function deleteLocalImage(string $contentKey): void
    {
        $prefix = str_replace('.', '-', $contentKey);
        $files = Storage::disk('public')->files('content');
        
        foreach ($files as $file) {
            $filename = basename($file);
            // Eliminar si coincide con el prefijo (mismo content key)
            if (str_starts_with($filename, $prefix . '.')) {
                Storage::disk('public')->delete($file);
                Log::info("Imagen local eliminada: {$file}");
            }
        }
    }

    /**
     * Reset a content item to default
     */
    public function resetContent(Request $request, int $id)
    {
        $content = SiteContent::findOrFail($id);
        $content->resetToDefault();

        return redirect()->back()
            ->with('success', "'{$content->label}' restaurado al valor por defecto");
    }

    /**
     * Remove uploaded image and revert to default
     */
    public function removeImage(int $id)
    {
        $content = SiteContent::findOrFail($id);

        if ($content->type !== 'image') {
            return redirect()->back()->with('error', 'Este contenido no es una imagen');
        }

        // Delete from Cloudinary if applicable
        if ($content->cloudinary_public_id) {
            $cloudinary = app(CloudinaryService::class);
            $cloudinary->delete($content->cloudinary_public_id);
            Log::info("Imagen eliminada de Cloudinary: {$content->cloudinary_public_id}");
        }
        
        // Delete local file if exists
        if ($content->image_path && !str_starts_with($content->image_path, 'http')) {
            if (Storage::disk('public')->exists($content->image_path)) {
                Storage::disk('public')->delete($content->image_path);
                Log::info("Imagen local eliminada: {$content->image_path}");
            }
            // Also clean up any orphaned images
            $this->deleteLocalImage($content->key);
        }

        $content->update([
            'image_path' => null,
            'cloudinary_public_id' => null,
        ]);
        
        SiteContent::clearCache();

        return redirect()->back()
            ->with('success', 'Imagen eliminada. Se mostrará la imagen por defecto.');
    }

    /**
     * Preview a section with current draft values
     */
    public function preview(string $section)
    {
        // Clear cache temporarily to show live values
        SiteContent::clearCache();

        $previewUrls = [
            'home' => route('home'),
            'about' => route('about'),
            'contact' => route('contact'),
        ];

        if (!isset($previewUrls[$section])) {
            return redirect()->route('admin.content.index')
                ->with('info', 'Vista previa no disponible para esta sección');
        }

        return redirect($previewUrls[$section]);
    }

    /**
     * Sanitize HTML content (basic)
     */
    private function sanitizeHtml(string $html): string
    {
        // Allow safe HTML tags
        $allowedTags = '<p><br><strong><b><i><em><u><ul><ol><li><a><h1><h2><h3><h4><h5><h6><span><div><img>';
        
        return strip_tags($html, $allowedTags);
    }
}
