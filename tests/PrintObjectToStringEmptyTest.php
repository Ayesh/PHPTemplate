<?php

namespace Ayesh\PHPTemplate\Tests;

use Ayesh\PHPTemplate\PHPTemplate;
use PHPUnit\Framework\TestCase;

class PrintObjectToStringEmptyTest extends TestCase {
  public function testUndefinedVars() {
    $template = new PHPTemplate(['name' => 'Ayesh']);
    $content = $template->render(__DIR__ . '/fixtures/template-undefined-vars.php');
    $this->assertSame('Hi !', $content);

    $content = $template->render(__DIR__ . '/fixtures/template-hello-name.php');
    $this->assertSame('Hello Ayesh!', $content);
  }
}
