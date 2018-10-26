<?php


namespace Ayesh\PHPTemplate\Tests;


use Ayesh\PHPTemplate\Exception\TemplateNotFound;
use Ayesh\PHPTemplate\PHPTemplate;
use PHPUnit\Framework\TestCase;

class TemplateRendereTest extends TestCase {
  public function testNonExistentTemplateThrows() {
    $template = new PHPTemplate();
    $this->expectException(TemplateNotFound::class);
    $template->render('test-43432');
  }

  public function testTemplateRawContent() {
    $template = new PHPTemplate();
    $content = $template->render(__DIR__ . '/fixtures/template-test-1.php');
    $this->assertSame('Hi', $content);
  }

  public function testTemplateRawContent_2() {
    $template = new PHPTemplate();
    $content = $template->render(__DIR__ . '/fixtures/template-test-2.php');
    $this->assertSame('<strong>Hi!</strong>', $content);
  }

  public function testTemplatePHPIgnoredWithoutTags() {
    $template = new PHPTemplate();
    $content = $template->render(__DIR__ . '/fixtures/template-without-php-tags.php');
    $this->assertSame('Hi $v;', $content);
  }
}
