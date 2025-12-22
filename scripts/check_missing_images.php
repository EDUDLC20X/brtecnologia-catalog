<?php
$projectRoot = __DIR__ . '/../';
require $projectRoot . 'vendor/autoload.php';
$app = require_once $projectRoot . 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$status = $kernel->handle(
    $input = new Symfony\Component\Console\Input\ArgvInput,
    new Symfony\Component\Console\Output\ConsoleOutput
);

use App\Models\ProductImage;

$missing = [];
foreach (ProductImage::all() as $img) {
    $publicPath = $projectRoot . 'public/storage/' . $img->path;
    if (! file_exists($publicPath)) {
        $missing[] = [$img->id, $img->path];
    }
}

if (count($missing) === 0) {
    echo "No missing files found.\n";
} else {
    echo "Missing images (product_image id | path):\n";
    foreach ($missing as $m) {
        echo $m[0] . ' | ' . $m[1] . "\n";
    }
}

$kernel->terminate($input, $status);
exit(0);
