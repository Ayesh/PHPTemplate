<?php

/** @noinspection PhpUndefinedFieldInspection */

namespace Ayesh\PHPTemplate\Tests;

use Ayesh\PHPTemplate\PHPTemplate;
use PHPUnit\Framework\TestCase;

class MagicMethodsTest extends TestCase {
  public function testMagicMethodsRounds(): void {
    $template = new PHPTemplate();
    $template->test = 'foo';
    $this->assertSame('', $template->test);
    $this->assertFalse(isset($template->test));
  }
}
