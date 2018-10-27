<?php

namespace Ayesh\PHPTemplate\Tests;

use Ayesh\PHPTemplate\PHPTemplate;
use PHPUnit\Framework\TestCase;

class SanitizerTest extends TestCase {
  public function testHtmlEscape(): void {
    $template = new PHPTemplate(__DIR__ . '/fixtures/template-hello-name.php');

    $content = $template->render([
      'name' => '<strong>Ayesh</strong>',
    ]);
    $this->assertSame('Hello &lt;strong&gt;Ayesh&lt;/strong&gt;!', $content);

    $template = new PHPTemplate(__DIR__ . '/fixtures/template-hello-name-unsafe.php');
    $content = $template->render([
      'name' => '<strong>Ayesh</strong>',
    ]);
    $this->assertSame('Hello <strong>Ayesh</strong>!', $content);
  }

  public function testUrlSanitizeJS(): void {
    $template = new PHPTemplate(__DIR__ . '/fixtures/template-url-js.php');

    $content = $template->render([
      'url' => "javascript:alert('xss!');",
      'url2' => 'https://ayeshious.com?query=test:pass#fragment',
      'url3' => 'normal-path#test:true',
    ]);
    $expected = <<<EXPECTED
<a href="alert(&#039;xss!&#039;);">Test</a>
<a href="javascript:alert(&#039;xss!&#039;);">Test</a>
<a href="javascript:alert('xss!');">Test</a>
<a href="https://ayeshious.com?query=test:pass#fragment">Test</a>
<a href="normal-path#test:true">Test</a>
EXPECTED;

    $this->assertSame($expected, $content);
  }
}
