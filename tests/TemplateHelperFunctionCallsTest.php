<?php

namespace Ayesh\PHPTemplate\Tests;

use Ayesh\PHPTemplate\PHPTemplate;
use PHPUnit\Framework\TestCase;

class TemplateHelperFunctionCallsTest extends TestCase {
  public function testTemplateFunctionCalls() {
    $template = new PHPTemplate();
    $content = $template->render(__DIR__ . '/fixtures/template-func-calls.php');

    $expected = <<<EXPECTED
<div>
  <div href="alert(&#039;xss&#039;);></div>
  <div href="&lt;strong&gt;test&lt;/strong&gt;></div>
  <div  foo="bar"></div>
</div>
EXPECTED;

    $this->assertSame($expected, $content);
  }

  public function testTemplateAttributesSpreading() {
    $template = new PHPTemplate(['vars' => [
      'foo' => 'bar',
      'baz' => ['hi', '"Escape', "'escape"],
    ]]);

    $content = $template->render(__DIR__ . '/fixtures/template-attributes.php');

    $expect = <<<EXPECT
<foo  foo="bar" baz="hi &quot;Escape &#039;escape" />
EXPECT;

    $this->assertSame($expect, $content);
  }
}
