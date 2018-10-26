<?php

namespace Ayesh\PHPTemplate\Tests;

use Ayesh\PHPTemplate\Exception\TemplateError;
use Ayesh\PHPTemplate\PHPTemplate;
use PHPUnit\Framework\TestCase;

class TemplateThrowErrorTest extends TestCase {
  public function testTemplateCanThrowErrors() {
    /** @noinspection PhpUnhandledExceptionInspection */
    $code = random_int(PHP_INT_MIN, PHP_INT_MAX);

    $template = new PHPTemplate(['code' => $code]);
    $this->expectException(TemplateError::class);
    $this->expectExceptionMessage('Oops');
    $this->expectExceptionCode($code);
    
    $template->render(__DIR__ . '/fixtures/template-throw-error.php');
  }
}
