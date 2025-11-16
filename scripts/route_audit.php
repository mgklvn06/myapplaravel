<?php
// Simple script to extract unique route names used in Blade views.
$dir = __DIR__ . '/../resources/views';
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$names = [];
foreach ($rii as $file) {
    if ($file->isDir()) continue;
    $path = $file->getPathname();
    if (!str_ends_with($path, '.blade.php')) continue;
    $contents = file_get_contents($path);
    if (preg_match_all('/route\(\s*[\'\"]([^\'\"]+)[\'\"]/m', $contents, $m)) {
        foreach ($m[1] as $name) $names[$name] = true;
    }
}
echo "Route names found in views:\n";
foreach (array_keys($names) as $n) echo " - $n\n";

echo "\nRun `php artisan route:list` to compare existing route names.\n";
