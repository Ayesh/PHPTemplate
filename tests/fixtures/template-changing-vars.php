<?php

/**
 * @var $v \Ayesh\PHPTemplate\PHPTemplate
 */

$v['name_plain'] = 'Ayesh';
$v['name_insecure'] = '<strong>Ayesh</strong>';
$v['nope'] = new stdClass();
$v['nope'] = isset($v['nope']) ? 'Ayesh' : 'Bar';
unset($v['nope']);
?>
Hi <?= $v['name_plain']; ?>!
Hi <?= $v['name_insecure']; ?>!
Hi <?= $v['!name_insecure']; ?>!
Hi <?= $v['!nope']; ?>!
