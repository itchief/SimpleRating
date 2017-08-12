<?php
/** @var modX $modx */
/** @var array $sources */

$settings = array();

$tmp = array(
    'simple_rating_ip' => array(
        'xtype' => 'combo-boolean',
        'value' => false,
    ),

);

foreach ($tmp as $k => $v) {
    /** @var modSystemSetting $setting */
    $setting = $modx->newObject('modSystemSetting');
    $setting->fromArray(array_merge(
        array(
            'key' => PKG_NAME_LOWER . '_' . $k,
            'namespace' => PKG_NAME_LOWER,
            'area' => 'simplerating_main',
        ), $v
    ), '', true, true);

    $settings[] = $setting;
}
unset($tmp);

return $settings;
