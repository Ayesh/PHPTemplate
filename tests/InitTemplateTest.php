<?php

namespace Ayesh\PHPTemplate\Tests;

use Ayesh\PHPTemplate\Exception\BadMethodCallException;
use Ayesh\PHPTemplate\PHPTemplate;
use PHPUnit\Framework\TestCase;

class InitTemplateTest extends TestCase {
  public function testInit(): void {
    $template = new PHPTemplate();
    $this->assertInstanceOf(PHPTemplate::class, $template);

    $this->expectException(BadMethodCallException::class);
    $template->render();
  }
}
