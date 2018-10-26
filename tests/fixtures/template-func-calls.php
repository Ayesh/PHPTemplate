<div>
  <div href="<?= $v->url("javascript:alert('xss');"); ?>></div>
  <div href="<?= $v->escape('<strong>test</strong>'); ?>></div>
  <div <?= $v->attributes(['foo' => 'bar']); ?>></div>
</div>
