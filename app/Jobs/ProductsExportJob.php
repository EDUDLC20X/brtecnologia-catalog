<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Mail\ProductsExportReady;
use Illuminate\Support\Facades\Mail;

class ProductsExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userEmail;

    public function __construct(string $userEmail)
    {
        $this->userEmail = $userEmail;
    }

    public function handle()
    {
        $products = Product::with('category')->orderBy('id')->get();

        // Ensure directory
        Storage::disk('public')->put('exports/.keep', '');

        $timestamp = now()->format('Ymd_His');

        // If PhpSpreadsheet is available, create XLSX, otherwise fallback to CSV
        if (class_exists('\\PhpOffice\\PhpSpreadsheet\\Spreadsheet')) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            // Header
            $sheet->fromArray([
                ['ID','SKU','Name','Category','Price','Stock','Active','Created At']
            ], null, 'A1');

            $row = 2;
            foreach ($products as $p) {
                $sheet->setCellValue("A{$row}", $p->id);
                $sheet->setCellValue("B{$row}", $p->sku_code);
                $sheet->setCellValue("C{$row}", $p->name);
                $sheet->setCellValue("D{$row}", $p->category?->name ?? 'Uncategorized');
                $sheet->setCellValue("E{$row}", $p->price_base);
                $sheet->setCellValue("F{$row}", $p->stock_available);
                $sheet->setCellValue("G{$row}", $p->is_active ? 'yes' : 'no');
                $sheet->setCellValue("H{$row}", $p->created_at);
                $row++;
            }

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $filename = 'exports/products_export_'.$timestamp.'.xlsx';
            $tmpPath = sys_get_temp_dir().'/'.basename($filename);
            $writer->save($tmpPath);

        } else {
            // Fallback CSV
            $filename = 'exports/products_export_'.$timestamp.'.csv';
            $tmpPath = sys_get_temp_dir().'/'.basename($filename);
            $fh = fopen($tmpPath, 'w');
            fputcsv($fh, ['ID','SKU','Name','Category','Price','Stock','Active','Created At']);
            foreach ($products as $p) {
                fputcsv($fh, [
                    $p->id,
                    $p->sku_code,
                    $p->name,
                    $p->category?->name ?? 'Uncategorized',
                    $p->price_base,
                    $p->stock_available,
                    $p->is_active ? 'yes' : 'no',
                    $p->created_at,
                ]);
            }
            fclose($fh);
        }

        // Store file to public disk
        try {
            Storage::disk('public')->putFileAs('exports', new \Illuminate\Http\UploadedFile($tmpPath, basename($filename)), basename($filename));
        } catch (\Exception $e) {
            // Fallback: copy
            copy($tmpPath, storage_path('app/public/exports/'.basename($filename)));
        }

        // Build public url
        $url = Storage::disk('public')->url('exports/'.basename($filename));

        // Local path of stored file (in case we want to attach it)
        $localPath = storage_path('app/public/exports/'.basename($filename));

        // Send notification (attach file when available)
        try {
            Mail::to($this->userEmail)->send(new ProductsExportReady($url, file_exists($localPath) ? $localPath : null));
            Log::info('ProductsExportJob: export sent', [
                'email' => $this->userEmail,
                'file' => $url,
                'local_path' => $localPath,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send products export mail: '.$e->getMessage());
        }
    }
}
