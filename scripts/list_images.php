<?php
$projectRoot = __DIR__ . '/../';
require $projectRoot . 'vendor/autoload.php';
$app = require_once $projectRoot . 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$status = $kernel->handle(
    $input = new Symfony\Component\Console\Input\ArgvInput,
    new Symfony\Component\Console\Output\ConsoleOutput
);

use App\Models\Product;

$products = Product::with('mainImage')->take(20)->get();
foreach ($products as $p) {
    echo $p->id . '|' . $p->name . '|' . ($p->mainImage->path ?? 'NULL') . PHP_EOL;
}

$kernel->terminate($input, $status);
