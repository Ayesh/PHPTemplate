# PHPTemplate
Lightweight, fast, and simple template engine that you write templates in PHP.

PHPTemplate is a very simple and light weight template engine that you can write your templates in PHP, but still tries to help you write secure-by-default templates. It provides secure-by-default variable access, and a few helper methods that you will learn in 30 seconds and you are all set to use it!

In its simplest form, you can throw in any PHP file, and its output will be returned. You can optionally pass additional variables that will be made accessible inside the template, and these variables will be sanitized by default to make it difficult to forget sanitizing any user input.

#### Simple Example

**Template file: `test-template.php`:**
```html
<strong>Hello World</strong>
```

How to use template:
```php
$template = new Ayesh\PHPTemplate\PHPTemplate();
$contents = $template->render('test-template.php'); // <-- Path to the template file.
// $contents will contain the contents of the `test-template.php` file.
```

#### Example with variables

*Template file: `test-template.php`:*
```php
Good morning <em><?= $v['name']; ?></em>
Welcome to <?= $v['sitename']; ?>
```

*How to use template:*
```php
$vars = [
  'name' => '<script>alert("xss!");</script>',
  'sitename' => 'PHPTemplate',
];
$template = new Ayesh\PHPTemplate\PHPTemplate($vars);
$contents = $template->render('test-template.php');
```

Within the template, the special `$v` variable will be available, and will contain all the variables you provided at the time you instantiated the `$template` object.

Every time you access these variables, they will be sanitized by default. In the example above, note that the `$vars['name']` variable contains a JavaScript. If you do not sanitize this variable, it will be interpreted as JavaScript, making your site vulnerable to Cross Site Scripting attacks. However, PHPTemplate library sanitizes these variables by default, which gives the following output:

```php
Good morning <em>&lt;script&gt;alert(&quot;xss!&quot;);&lt;/script&gt;<em>,
Welcome to PHPTemplate
```

The above snippet contains the HTML you used in the template file, but notice how the `$vars['name']` variable is sanitized to HTML entities. Browsers will _not_ interpret this as JavaScript, and will instead print the literal characters `<script>alert("xss!");</script>`. When you print this to the browser, your users will see the following, **without the browser interpreting JavaScript**:


Good morning *`<script>alert("xss!");</script>`*
Welcome to PHPTemplate

In addition to HTML sanitizing, this library provides sanitation for URLs as well. Consider the following template:

*Template file: `test-template.php`:*
```php
<a href="<?= $v[':user_website']; ?>">User Web site</a>
```
Notice that in the template above, we access the `user_website` variable with a colon prefix (`:`). This will hint the template engine that we expect the URL to be sanitized.

A typical attach would be that the user provides a URL such as `javascript:alert("xss");` that is technically a valid URI, but executed JavaScript when clicked. With `PHPTemplate`, you can easily sanitize these URLs that will allow `http://`, `https://`, and `ftp://` protocols but none of the above.

The above template will be rendered as:
```php
$vars = [
  'user_website' => 'javascript:alert("xss");',
];
$template = new Ayesh\PHPTemplate\PHPTemplate($vars);
$contents = $template->render('test-template.php');
```

*Output:*
```html
<a href="alert(&#039;xss!&#039;);">User Web site</a>
```

The template above is safe, even though the variable we used was unsafe to use as-is.

Please refer to the full reference for more information.

### Installation

You can easily install this library via Composer:
```bash
composer require ayesh/phptemplate
```

By default, the download will not contain the `tests` directory that contains some templates used in automated tests. If you download the library as a Git clone, or in any other way (such as composer prefer source), please make sure the `tests` directory is not accessible via your web server. These scripts do not contain anything harmful, but will output some random strings to the browsers that you do not want. The good news is unless you explicitly download the source as-is, these tests will not be included.

### Reference

#### Accessing variables
 - `$v['example']` : Variable will be HTML sanitized by default
 - `$v[':example']` : Variable will be sanitized to make sure it's a safe URL.
 - `$v['!example']` : Variable will be returned *without* any sanitation (potentially unsafe).

Note that this kind of array accessing is **guaranteed to be a string**.  If you access a variable that is not set, or any other type other than a **string, float, or an integer**,  an empty string will be returned instead. This makes it easier to access variables that you are not sure if exists.

To access complex variables, you can use the `$v->get('variable'])` helper function. It will return whatever variable you set initially. This is **not guaranteed to be a string not is sanitized**.

#### Helper methods
PHPTemplate provides a few helper methods that can help you build your template.

---

**`$v->get('variable')`**:

Returns the original `variable` set in `new PHPTemplate(['variable' => foo])` call (`foo` in this case), or an empty string if not set. This return value is not sanitized. You can use the helper functions provided below to sanitize them as necessary.

---

**`$v->escape('foo')`**
Escapes the provided literal string to make sure it does not contain anything that would be interpreted as HTML. You can also use this `escape` method inside HTML attributes as it would also convert single and double quotes to HTML entities.

---

**`$v->url('https://example.com')`**
Sanitizes the provided URL making sure it only contains relative paths, or URIs whose protocol is `http://`, `https://`, or `ftp://`. Any other protocols such as `javascript:` will be removed.

---
**`$v->attributes(['foo' => 'bar')`**
Expands the provided array into HTML attributes. In this example, you can use the template

```php
<a <?= $v->attributes(['href' => 'https://ayesh.me', 'rel' => 'nofollow'); ?>Click</a>`
```
that would result:
```
<a href="https://ayesh.me" rel="nofollow">Click</a>
```
---
**`$v->error('Something went wrong')`**
This is a shortcut to throw an error of type `TemplateError` that the parent caller can catch. You will probably never need to use this method, but this is an easy way to throw an error if your template cannot proceed and you want to terminate and report it to the parent callers.

### Contributions
Contributions are welcome! Please note that my goal is to have a bare minimum template engine that simply does the job and leaves anything complex to whoever writes the template. Performance improvements and simple+useful helper methods would be highly appreciated. For security issues, please [contact me](https://ayesh.me/contact) instead of reporting them in public issue queues.
