<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteContent;
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

                    // Delete ALL old images for this content to prevent duplicates
                    if ($content->image_path) {
                        // Delete the current image
                        if (Storage::disk('public')->exists($content->image_path)) {
                            Storage::disk('public')->delete($content->image_path);
                        }
                        
                        // Also check for any orphaned images with similar names
                        $this->cleanupOrphanedImages($content->key);
                    }

                    // Store new image with unique name based on content key
                    $extension = $file->getClientOriginalExtension();
                    $safeName = str_replace('.', '-', $content->key) . '-' . time() . '.' . $extension;
                    $path = $file->storeAs('content', $safeName, 'public');
                    
                    $content->update(['image_path' => $path]);
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
     * Clean up orphaned images for a content key
     */
    private function cleanupOrphanedImages(string $contentKey): void
    {
        $prefix = str_replace('.', '-', $contentKey);
        $files = Storage::disk('public')->files('content');
        
        foreach ($files as $file) {
            $filename = basename($file);
            if (str_starts_with($filename, $prefix)) {
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

        // Delete the uploaded image
        if ($content->image_path && Storage::disk('public')->exists($content->image_path)) {
            Storage::disk('public')->delete($content->image_path);
        }

        $content->update(['image_path' => null]);
        
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
