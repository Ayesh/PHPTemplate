<!DOCTYPE html>
<html>
<head>
  <title><?= $v['foo']; ?></title>
</head>
<body>

<h1><?= $v['bar']; ?></h1>
<p><?= $v['foo']; ?></p>
<a href="<?= $v[':url_safe']; ?>">Test</a>
<a href="<?= $v[':url_unsafe']; ?>">Test</a>

<?= $v['non-existent']; ?>
<?= $v['!non-existent']; ?>
<?= $v['@non-existent']; ?>

<span <?= $v->attributes($v->get('attr')); ?>></span>

</body>
</html>
