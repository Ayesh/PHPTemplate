<?php

require_once __DIR__ . '/../../vendor/autoload.php';
$benchmark = new \Ayesh\PHPTemplate\Tests\Benchmark\Benchmark();
$duration = $benchmark->benchmarkFull();
echo 'Benchmark: ' . number_format($duration, 3) . ' seconds.';
