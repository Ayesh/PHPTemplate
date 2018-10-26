<?php

namespace Ayesh\PHPTemplate\Tests;

use Ayesh\PHPTemplate\PHPTemplate;
use PHPUnit\Framework\TestCase;

class SanitizerTest extends TestCase {
  public function testHtmlEscape() {
    $template = new PHPTemplate([
      'name' => '<strong>Ayesh</strong>',
    ]);

    $content = $template->render(__DIR__ . '/fixtures/template-hello-name.php');
    $this->assertSame('Hello &lt;strong&gt;Ayesh&lt;/strong&gt;!', $content);

    $content = $template->render(__DIR__ . '/fixtures/template-hello-name-unsafe.php');
    $this->assertSame('Hello <strong>Ayesh</strong>!', $content);
  }

  public function testUrlSanitizeJS() {
    $template = new PHPTemplate([
      'url' => "javascript:alert('xss!');",
      'url2' => 'https://ayeshious.com?query=test:pass#fragment',
      'url3' => 'normal-path#test:true',
    ]);

    $content = $template->render(__DIR__ . '/fixtures/template-url-js.php');
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
