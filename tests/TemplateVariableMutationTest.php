<?php

namespace Ayesh\PHPTemplate\Tests;

use Ayesh\PHPTemplate\PHPTemplate;
use PHPUnit\Framework\TestCase;

class TemplateVariableMutationTest extends TestCase {
  public function testTemplateVarMutation(): void {
    $template = new PHPTemplate(__DIR__  . '/fixtures/template-changing-vars.php');
    $content = $template->render();

    $expected = <<<EXPECTED
Hi Ayesh!
Hi &lt;strong&gt;Ayesh&lt;/strong&gt;!
Hi <strong>Ayesh</strong>!
Hi !
EXPECTED;

    $this->assertSame($expected, $content);
  }
}
