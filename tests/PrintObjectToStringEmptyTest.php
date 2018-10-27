<?php

namespace Ayesh\PHPTemplate\Tests;

use Ayesh\PHPTemplate\PHPTemplate;
use PHPUnit\Framework\TestCase;

class PrintObjectToStringEmptyTest extends TestCase {
  public function testUndefinedVars(): void {
    $template = new PHPTemplate(__DIR__ . '/fixtures/template-undefined-vars.php');
    $content = $template->render(['name' => 'Ayesh']);
    $this->assertSame('Hi !', $content);

    $template = new PHPTemplate(__DIR__ . '/fixtures/template-hello-name.php');
    $content = $template->render(['name' => 'Ayesh']);
    $this->assertSame('Hello Ayesh!', $content);
  }
}
