<?php


namespace Ayesh\PHPTemplate\Tests;


use Ayesh\PHPTemplate\Exception\TemplateNotFound;
use Ayesh\PHPTemplate\PHPTemplate;
use PHPUnit\Framework\TestCase;

class TemplateRendereTest extends TestCase {
  public function testNonExistentTemplateThrows() {
    $template = new PHPTemplate('test-43432');
    $this->expectException(TemplateNotFound::class);
    $template->render();
  }

  public function testTemplateRawContent() {
    $template = new PHPTemplate(__DIR__ . '/fixtures/template-test-1.php');
    $content = $template->render();
    $this->assertSame('Hi', $content);
  }

  public function testTemplateRawContent_2() {
    $template = new PHPTemplate(__DIR__ . '/fixtures/template-test-2.php');
    $content = $template->render();
    $this->assertSame('<strong>Hi!</strong>', $content);
  }

  public function testTemplatePHPIgnoredWithoutTags() {
    $template = new PHPTemplate(__DIR__ . '/fixtures/template-without-php-tags.php');
    $content = $template->render();
    $this->assertSame('Hi $v;', $content);
  }
}
