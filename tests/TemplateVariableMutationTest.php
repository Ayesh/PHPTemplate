<?php

namespace Ayesh\PHPTemplate\Tests;

use Ayesh\PHPTemplate\PHPTemplate;
use PHPUnit\Framework\TestCase;

class TemplateVariableMutationTest extends TestCase {
  public function testTemplateVarMutation() {
    $template = new PHPTemplate();
    $content = $template->render(__DIR__  . '/fixtures/template-changing-vars.php');

    $expected = <<<EXPECTED
Hi Ayesh!
Hi &lt;strong&gt;Ayesh&lt;/strong&gt;!
Hi <strong>Ayesh</strong>!
Hi !
EXPECTED;

    $this->assertSame($expected, $content);
  }
}
