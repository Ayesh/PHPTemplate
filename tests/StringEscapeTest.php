<?php

namespace Ayesh\PHPTemplate\Tests;

use Ayesh\PHPTemplate\PHPTemplate;
use PHPUnit\Framework\TestCase;

class StringEscapeTest extends TestCase {
  public function testHtmlEscape(): void {
    $template = new PHPTemplate();
    $this->assertSame('test', $template->escape('test'));
    $this->assertSame('&lt;strong&gt;test&lt;/strong&gt;', $template->escape('<strong>test</strong>'));
  }

  public function testHtmlEscapeFromVars(): void {
    $template = new PHPTemplate();
    $template->set(['test' => '<strong>test</strong>']);
    $this->assertSame('&lt;strong&gt;test&lt;/strong&gt;', $template['test']);
    $this->assertSame('<strong>test</strong>', $template['!test']);
  }

  public function testNonStringVarsReturnEmptyString(): void {
    $template = new PHPTemplate('', ['test' => []]);

    $this->assertSame('', $template['test']);
    $this->assertSame('', $template['!test']);
    $this->assertSame('', $template[':test']);
    $this->assertSame([], $template->get('test'));
    $this->assertNull($template->get('test2'));
  }

  public function testReturnUnmodified(): void {
    $template = new PHPTemplate('', ['test' => '<script>alert("")</script>']);
    $return = $template->get('test');
    $this->assertSame('<script>alert("")</script>', $return);
  }
}
