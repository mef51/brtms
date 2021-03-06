<?php

/**
 * Serve the two script_raw.p?.js files with the ${ROOT} symbol replaced.
 */

require_once dirname(__FILE__) . '/../l/config.inc.php';

$contents = file_get_contents('script_raw.p1.js');
$contents .= file_get_contents('script_raw.p2.js');
$contents = str_replace('${ROOT}', $config['ROOT'], $contents);

header('Content-Type: text/javascript');
echo $contents;

