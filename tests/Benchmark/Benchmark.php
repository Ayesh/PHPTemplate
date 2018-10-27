<?php


namespace Ayesh\PHPTemplate\Tests\Benchmark;


use Ayesh\PHP_Timer\Stopwatch;
use Ayesh\PHPTemplate\PHPTemplate;

class Benchmark {
  public function benchmarkFull(): float {
    new PHPTemplate(); // Autoload.

    $vars = [
      'foo' => 'bar',
      'bar' => 'baz',
      'attr' => ['name' => 'foo', 'bar' => 'baz'],
      'url_safe' => 'https://ayeshious.com',
      'url_unsafe' => "javascript:alert('xss!')",
    ];

    $timer = new Stopwatch();

    for ($i = 0; $i < 10000; $i++) {
      $template = new PHPTemplate(__DIR__ . '/../fixtures/template-benchmark.php', $vars);
      $template->render();
    }

    return $timer->stop();
  }
}
