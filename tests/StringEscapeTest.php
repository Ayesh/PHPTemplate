<?php

namespace Ayesh\PHPTemplate\Tests;

use Ayesh\PHPTemplate\PHPTemplate;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\CodeCoverage\Report\PHP;

class StringEscapeTest extends TestCase {
  public function testHtmlEscape(): void {
    $template = new PHPTemplate( []);
    $this->assertSame('test', $template->escape('test'));
    $this->assertSame('&lt;strong&gt;test&lt;/strong&gt;', $template->escape('<strong>test</strong>'));
  }

  public function testHtmlEscapeFromVars(): void {
    $template = new PHPTemplate(['test' => '<strong>test</strong>']);
    $this->assertSame('&lt;strong&gt;test&lt;/strong&gt;', $template['test']);
    $this->assertSame('<strong>test</strong>', $template['!test']);
  }

  public function testNonStringVarsReturnEmptyString(): void {
    $template = new PHPTemplate(['test' => []]);
    $this->assertSame('', $template['test']);
    $this->assertSame('', $template['!test']);
    $this->assertSame('', $template[':test']);
    $this->assertSame([], $template->get('test'));
    $this->assertNull($template->get('test2'));
  }
}
