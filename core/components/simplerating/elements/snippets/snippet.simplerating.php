<?php
$modx->regClientCSS(MODX_ASSETS_URL . 'components/simplerating/css/web/default.css');
$modx->regClientScript(MODX_ASSETS_URL . 'components/simplerating/js/web/default.js');

/** @var array $scriptProperties */
$tpl_option = $modx->getOption('tpl', $scriptProperties, 'tplSimpleRating');

$rating_value = 0.0;
$rating_count = 0;
$rating_ips = array();
$rating_active_class = ' rating_active';
$cookie_key = 'star_rating';

$modx->addPackage('simplerating', MODX_CORE_PATH . 'components/simplerating/model/');

$id = $modx->resource->get('id');
$simple_rating = $modx->getObject('SimpleRating', array(
    'resource' => $id
));

if (is_object($simple_rating)) {
    $rating_value = $simple_rating->get('rating_value');
    $rating_count = $simple_rating->get('rating_count');
    $rating_ips = $simple_rating->get('rating_ips');
}
// проверка по IP
$ip = $modx->request->getClientIp();
if (($modx->getOption('simple_rating_ip')===1) && in_array($ip['ip'], $rating_ips)) {
    $rating_active_class = '';
}
// проверка по COOKIE
if (!empty($_COOKIE[$cookie_key])) {
    $cookie = (int)$_COOKIE[$cookie_key];
    if ($id === $cookie) {
        $rating_active_class = '';
    }
}

$width = 130 * $rating_value / 5;

$output = $modx->getChunk($tpl_option, array(
    'id' => $id,
    'rating_best' => 5,
    'rating_value' => $rating_value,
    'rating_count' => $rating_count,
    'rating_active' => $rating_active_class,
    'rating_width' => str_replace(',', '.', $width)
));

return $output;