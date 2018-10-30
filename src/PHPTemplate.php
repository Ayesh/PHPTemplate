<?php

namespace Ayesh\PHPTemplate;

use Ayesh\PHPTemplate\Exception\BadMethodCallException;
use Ayesh\PHPTemplate\Exception\TemplateError;
use Ayesh\PHPTemplate\Exception\TemplateNotFound;
use Exception;

final class PHPTemplate implements \ArrayAccess {

	/**
	 * @var View data
	 */
	private $vars;

	/**
	 * @var string Template file
	 */
	private $template_file;

	/**
	 * @var array Allowed url protocols
	 */
	private const ALLOWED_URL_PROTOCOLS = [
		'http',
		'https',
		'ftp'
	];

	/**
	 * Constructor.
	 *
	 * @param string|null $template_file The template file
	 * @param array $vars The view data
	 */
	public function __construct(string $template_file = null, array $vars = []) {
		$this->template_file = $template_file;
		$this->set($vars);
	}

	/**
	 * Set view data.
	 *
	 * @param array $vars The view data
	 * @return void
	 */
	public function set(array $vars = []): void {
		$this->vars = $vars;
	}

	/**
	 * Render template.
	 *
	 * @param array|null $vars The view data
	 * @return string Rendered template
	 * @throws Exception
	 */
	public function render(array $vars = null): string {
		if (empty($this->template_file)) {
			throw new BadMethodCallException('Calls to render() method is illegal when a template path is not set.');
		}
		if (!file_exists($this->template_file)) {
			throw new TemplateNotFound(sprintf('Template %s could not be loaded.', $this->template_file));
		}

		if (null !== $vars) {
			$this->set($vars);
		}

		return trim(self::renderTemplate($this->template_file, $this));
	}

	/**
	 * Render template.
	 *
	 * @param string $path The path
	 * @param PHPTemplate $v The PHPTemplate instance
	 * @return string The rendered content
	 * @throws Exception
	 */
	private static function renderTemplate($path, PHPTemplate $v): string {
		ob_start();
		try {
			/** @noinspection PhpIncludeInspection */
			include $path;
		} catch (Exception $exception) {
			ob_end_clean();
			throw $exception;
		}

		return ob_get_clean();
	}

	/**
	 * Get view data by key name.
	 *
	 * @param string $key The key
	 * @return mixed|null The value
	 */
	public function get(string $key) {
		if (isset($this->vars[$key])) {
			return $this->vars[$key];
		}

		return null;
	}

	/**
	 * Whether an offset exists.
	 *
	 * @param mixed $offset An offset to check for
	 * @return bool Returns true on success or false on failure.
	 */
	public function offsetExists($offset): bool {
		return isset($this->vars[$offset]);
	}

	/**
	 * Offset to retrieve.
	 *
	 * @param mixed $offset The offset to retrieve.
	 * @return mixed Returns the value at specified offset.
	 */
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

	/**
	 * Assign a value to the specified offset.
	 *
	 * @param mixed $offset The offset to assign the value to
	 * @param mixed $value The value to set
	 * @return void
	 */
	public function offsetSet($offset, $value): void {
		$this->vars[$offset] = $value;
	}

	/**
	 * Unset an offset.
	 *
	 * @param mixed $offset The offset to unset.
	 * @return void
	 */
	public function offsetUnset($offset): void {
		unset($this->vars[$offset]);
	}

	/**
	 * View data value to retrieve.
	 *
	 * @param string $offset The offset to retrieve
	 * @return string Returns the value at specified offset
	 */
	private function getVar(string $offset): string {
		if (isset($this->vars[$offset]) && \is_scalar($this->vars[$offset]) && !\is_bool($this->vars[$offset])) {
			return $this->vars[$offset];
		}
		return '';
	}

	/**
	 * Html encoding.
	 *
	 * Escapes the provided literal string to make sure it does not contain anything that would be interpreted as HTML.
	 * You can also use this escape method inside HTML attributes as it would also convert single and double
	 * quotes to HTML entities.
	 *
	 * @param string $input The value to encode
	 * @return string The html encoded value
	 */
	public function escape(string $input): string {
		return \htmlspecialchars($input, \ENT_QUOTES | \ENT_SUBSTITUTE, 'UTF-8');
	}

	/**
	 * URL encoding.
	 *
	 * Sanitizes the provided URL making sure it only contains relative paths, or URIs whose protocol
	 * is http://, https://, or ftp://. Any other protocols such as javascript: will be removed.
	 *
	 * @param string $uri The url
	 * @return string The url encoded string
	 */
	public function url(string $uri): string {
		$allowed_protocols = array_flip(static::ALLOWED_URL_PROTOCOLS);

		// Iteratively remove any invalid protocol found.
		// Borrowed from Drupal.
		do {
			$before = $uri;
			$colon_position = strpos($uri, ':');
			if ($colon_position > 0) {

				// We found a colon, possibly a protocol. Verify.
				$protocol = substr($uri, 0, $colon_position);

				// If a colon is preceded by a slash, question mark or hash, it cannot
				// possibly be part of the URL scheme. This must be a relative URL, which
				// inherits the (safe) protocol of the base document.
				if (preg_match('![/?#]!', $protocol)) {
					break;
				}

				// Check if this is a disallowed protocol. Per RFC2616, section 3.2.3
				// (URI Comparison) scheme comparison must be case-insensitive.
				if (!isset($allowed_protocols[strtolower($protocol)])) {
					$uri = substr($uri, $colon_position + 1);
				}
			}
		} while ($before !== $uri);
		return $this->escape($uri);
	}

	/**
	 * Expands the provided array into HTML attributes.
	 *
	 * @param array $attributes The attributes
	 *
	 * @return string
	 */
	public function attributes(array $attributes): string {
		foreach ($attributes as $attribute => &$data) {
			$data = implode(' ', (array)$data);
			$data = $attribute . '="' . $this->escape($data) . '"';
		}

		return $attributes ? ' ' . implode(' ', $attributes) : '';
	}

	/**
	 * This is a shortcut to throw an error of type TemplateError that the parent caller can catch.
	 * You will probably never need to use this method, but this is an easy way to throw an error if your
	 * template cannot proceed and you want to terminate and report it to the parent callers.
	 *
	 * @param string $error_message The error nessage
	 * @param int $error_code The error code
	 *
	 * @throws TemplateError
	 */
	public function error(string $error_message, int $error_code = 0): void {
		throw new TemplateError($error_message, $error_code);
	}

	/**
	 * Cast to string.
	 *
	 * @return string Blank string
	 */
	public function __toString(): string {
		return '';
	}

	/**
	 * Set.
	 *
	 * @param mixed $name The name
	 * @param mixed $value The value
	 * @return void
	 */
	public function __set($name, $value): void {
	}

	/**
	 * Isset.
	 *
	 * @param mixed $name The name
	 * @return bool Status
	 */
	public function __isset($name) {
		return false;
	}

	/**
	 * Get.
	 *
	 * @param mixed $name The name
	 * @return string Blank string
	 */
	public function __get($name) {
		return '';
	}
}
