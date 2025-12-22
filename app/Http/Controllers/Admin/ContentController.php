<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteContent;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
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

        foreach ($contents as $content) {
            $fieldName = str_replace('.', '_', $content->key);
            
            // Handle image uploads
            if ($content->type === 'image') {
                if ($request->hasFile("image_{$fieldName}")) {
                    $file = $request->file("image_{$fieldName}");
                    
                    // Validate image
                    $validated = $request->validate([
                        "image_{$fieldName}" => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048'
                    ]);

                    // Guardar referencias de imagen anterior para eliminar después
                    $oldImagePath = $content->image_path;
                    $oldCloudinaryId = $content->cloudinary_public_id;

                    // Try Cloudinary first if configured
                    $cloudinary = app(CloudinaryService::class);
                    $path = null;
                    $cloudinaryPublicId = null;
                    
                    if ($cloudinary->isConfigured()) {
                        $result = $cloudinary->upload($file, 'brtecnologia/content');
                        if ($result) {
                            $path = $result['url'];
                            $cloudinaryPublicId = $result['public_id'];
                        }
                    }
                    
                    // Fallback to local storage
                    if (!$path) {
                        $extension = $file->getClientOriginalExtension();
                        $safeName = str_replace('.', '-', $content->key) . '-' . time() . '.' . $extension;
                        $path = $file->storeAs('content', $safeName, 'public');
                    }
                    
                    // Actualizar con nueva imagen
                    $content->update([
                        'image_path' => $path,
                        'cloudinary_public_id' => $cloudinaryPublicId,
                    ]);

                    // AHORA eliminar imagen anterior (después de guardar la nueva)
                    if ($oldImagePath) {
                        if ($oldCloudinaryId) {
                            // Eliminar de Cloudinary
                            $cloudinary->delete($oldCloudinaryId);
                        } elseif (!str_starts_with($oldImagePath, 'http')) {
                            // Eliminar archivo local
                            if (Storage::disk('public')->exists($oldImagePath)) {
                                Storage::disk('public')->delete($oldImagePath);
                            }
                        }
                    }
                    
                    // Limpiar otras imágenes huérfanas del mismo content key
                    $this->cleanupOrphanedImages($content->key, $path);
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

        return redirect()->route('admin.content.section', $section)
            ->with('success', 'Contenido actualizado correctamente');
    }

    /**
     * Clean up orphaned images for a content key (excluding current image)
     */
    private function cleanupOrphanedImages(string $contentKey, ?string $currentPath = null): void
    {
        $prefix = str_replace('.', '-', $contentKey);
        $files = Storage::disk('public')->files('content');
        
        // Obtener solo el nombre del archivo actual para excluirlo
        $currentFilename = $currentPath ? basename($currentPath) : null;
        
        foreach ($files as $file) {
            $filename = basename($file);
            // Eliminar solo si coincide con el prefijo Y no es la imagen actual
            if (str_starts_with($filename, $prefix) && $filename !== $currentFilename) {
                Storage::disk('public')->delete($file);
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
        }
        
        // Delete local file if exists
        if ($content->image_path && !str_starts_with($content->image_path, 'http')) {
            if (Storage::disk('public')->exists($content->image_path)) {
                Storage::disk('public')->delete($content->image_path);
            }
            // Also clean up any orphaned images
            $this->cleanupOrphanedImages($content->key);
        }

        $content->update([
            'image_path' => null,
            'cloudinary_public_id' => null,
        ]);
        
        SiteContent::clearCache();

        return redirect()->back()
            ->with('success', 'Imagen eliminada, se mostrará la imagen por defecto');
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
