<?php


namespace Ayesh\PHPTemplate;


use Ayesh\PHPTemplate\Exception\TemplateError;
use Ayesh\PHPTemplate\Exception\TemplateNotFound;

final class PHPTemplate implements \ArrayAccess {
  private $vars = [];

  private const ALLOWED_URL_PROTOCOLS = [
    'http', 'https', 'ftp'
  ];

  public function __construct(array $vars = []) {
    $this->vars = &$vars;
  }

  public function render(string $template_path): string {
    if (!file_exists($template_path)) {
      throw new TemplateNotFound(sprintf('Template %s could not be loaded.', $template_path));
    }

    return trim(self::renderTemplate($template_path, $this));
  }

  private static function renderTemplate($path, PHPTemplate $v): string {
    ob_start();
    /** @noinspection PhpIncludeInspection */
    include $path;
    return ob_get_clean();
  }

  public function get(string $key) {
    if (isset($this->vars[$key])) {
      return $this->vars[$key];
    }
    return null;
  }

  public function offsetExists($offset): bool {
    return isset($this->vars[$offset]);
  }

  public function offsetGet($offset) {
    $first_char = $offset{0};

    switch ($first_char) {
      case ':':
        return $this->url($this->getVar(substr($offset, 1)));

      case '!':
        return $this->getVar(substr($offset, 1));
      default:
        return $this->escape($this->getVar($offset));
    }
  }

  public function offsetSet($offset, $value): void {
    $this->vars[$offset] = $value;
  }

  public function offsetUnset($offset): void {
    unset($this->vars[$offset]);
  }

  private function getVar(string $offet): string {
    if (isset($this->vars[$offet]) && \is_string($this->vars[$offet])) {
      return $this->vars[$offet];
    }
    return '';
  }

  public function escape(string $input): string {
    return \htmlspecialchars($input, \ENT_QUOTES | \ENT_SUBSTITUTE, 'UTF-8');
  }

  public function url(string $uri): string {
    $allowed_protocols = array_flip(static::ALLOWED_URL_PROTOCOLS);

    // Iteratively remove any invalid protocol found.
    do {
      $before = $uri;
      $colonpos = strpos($uri, ':');
      if ($colonpos > 0) {

        // We found a colon, possibly a protocol. Verify.
        $protocol = substr($uri, 0, $colonpos);

        // If a colon is preceded by a slash, question mark or hash, it cannot
        // possibly be part of the URL scheme. This must be a relative URL, which
        // inherits the (safe) protocol of the base document.
        if (preg_match('![/?#]!', $protocol)) {
          break;
        }

        // Check if this is a disallowed protocol. Per RFC2616, section 3.2.3
        // (URI Comparison) scheme comparison must be case-insensitive.
        if (!isset($allowed_protocols[strtolower($protocol)])) {
          $uri = substr($uri, $colonpos + 1);
        }
      }
    } while ($before !== $uri);
    return $this->escape($uri);
  }

  /**
   * @param string $error_message
   * @param int $error_code
   *
   * @throws \Ayesh\PHPTemplate\Exception\TemplateError
   */
  public function error(string $error_message, int $error_code = 0): void {
    throw new TemplateError($error_message, $error_code);
  }
}
