<?php

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx = new modX();
$modx->initialize('web');
$modx->getService('error', 'error.modError', '', '');

// Если  запрос не AJAX (XMLHttpRequest), то завершить работу
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
    exit();
}

if (empty($_POST['action'])) {
    exit();
}

$output = array();

switch ($_POST['action']) {

    case 'setSimpleRating':

        if (empty($_POST['title'])) {
            break;
        }

        $title = $_POST['title'];
        $id = $_POST['id'];
        $modx->addPackage('simplerating', $modx->getOption('core_path') . 'components/simplerating/model/');

        //const BEST_RATING = 5;

        $ratingValue = 0.0;
        $ratingCount = 0;
        $ratingIps = array();

        $query = $modx->newQuery('SimpleRating');
        $query->where(array(
            'resource' => $id,
        ));
        $simpleRating = $modx->getObject('SimpleRating', $query);
        if (is_object($simpleRating)) {
            $ratingValue = $simpleRating->get('rating_value');
            $ratingCount = $simpleRating->get('rating_count');
            $ratingIps = $simpleRating->get('rating_ips');
        } else {
            $simpleRating = $modx->newObject('SimpleRating');
            $simpleRating->set('resource', $id);
        }

        if (in_array($_SERVER['REMOTE_ADDR'], $ratingIps)) {
            break;
        }

        $ratingNewValue = ($ratingValue * $ratingCount + $title) / ($ratingCount + 1);
        $ratingIps[] = $_SERVER['REMOTE_ADDR'];
        $simpleRating->set('rating_value', $ratingNewValue);
        $simpleRating->set('rating_count', $ratingCount + 1);
        $simpleRating->set('rating_ips', $ratingIps);
        $simpleRating->save();

        $output = array(
            'rating_value' => $ratingNewValue,
            'rating_count' => $ratingCount + 1
        );

        break;

}
header('Content-Type: application/json');
$output = json_encode($output);
exit($output);