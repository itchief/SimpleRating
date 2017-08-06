<?php

$modx->regClientCSS('assets/components/simplerating/css/web/default.css');
$modx->regClientScript('assets/components/simplerating/js/web/default.js');

const BEST_RATING = 5;
$ratingValue = 0.0;
$ratingCount = 0;
$ratingIps = array();
$ratingActive = ' rating_active';

$modx->addPackage('simplerating', $modx->getOption('core_path') . 'components/simplerating/model/');
$id = $modx->resource->get('id');
$query = $modx->newQuery('SimpleRating');
$query->where(array(
    'resource' => $id,
));
$simpleRating = $modx->getObject('SimpleRating', $query);
if (is_object($simpleRating)) {
    $ratingValue = $simpleRating->get('rating_value');
    $ratingCount = $simpleRating->get('rating_count');
    $ratingIps = $simpleRating->get('rating_ips');
}

if (in_array($_SERVER['REMOTE_ADDR'], $ratingIps)) {
    $ratingActive = '';
}

$width = 109 * $ratingValue / BEST_RATING;

$output = $modx->getChunk('tplSimpleRating', array(
    'id' => $id,
    'rating_best' => BEST_RATING,
    'rating_value' => $ratingValue,
    'rating_count' => $ratingCount,
    'rating_active' => $ratingActive,
    'rating_width' => str_replace(',', '.', $width),
    'rating_ips' => implode(',', $ratingIps)
));

return $output;