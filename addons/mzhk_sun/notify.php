<?php


$xml = file_get_contents('php://input');

libxml_disable_entity_loader(true);
$result = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
if(!$result) die('FAIL');




define('IN_MOBILE', true);
require '../../framework/bootstrap.inc.php';

$site = WeUtility::createModuleSite('mzhk_sun');
$_GPC['c']='site';
$_GPC['a']='entry';
$_GPC['m']='mzhk_sun';
$_GPC['do']='notify';

if(!is_error($site)) {
    $method = 'doWebNotify';
    $site->inMobile = true;
    if (method_exists($site, $method)) {
        $site->$method($result);
        exit;
    }
}
