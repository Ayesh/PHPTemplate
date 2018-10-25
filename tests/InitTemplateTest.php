<?php

namespace Ayesh\PHPTemplate\Tests;

use Ayesh\PHPTemplate\PHPTemplate;
use PHPUnit\Framework\TestCase;

class InitTemplateTest extends TestCase {
  public function testInit() {
    $template = new PHPTemplate();
    $this->assertInstanceOf(PHPTemplate::class, $template);
  }
}
