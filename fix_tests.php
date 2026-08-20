<?php
$dir = new RecursiveDirectoryIterator('tests');
$iter = new RecursiveIteratorIterator($dir);
foreach ($iter as $file) {
    if ($file->getExtension() !== 'php') continue;
    $content = file_get_contents($file->getPathname());
    $new = str_replace(
        'use Illuminate\Foundation\Testing\RefreshDatabase;',
        'use Illuminate\Foundation\Testing\DatabaseTransactions;',
        $content
    );
    if ($new !== $content) {
        file_put_contents($file->getPathname(), $new);
        echo 'Updated: ' . $file->getFilename() . PHP_EOL;
    }
}
echo 'Done.' . PHP_EOL;
