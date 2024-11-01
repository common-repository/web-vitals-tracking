<?php 

if (!defined('ABSPATH')) {
	exit;
}

global $wpdb;

// NOTE: does not drop table on uninstall, data are important, just do it manually if you want to clean your DB.
// Unless I will add the "drop data on delete" option

$sql = [
    // 'DROP TABLE `{$wpdb->prefix}wpwebvitalstrack`;'
];

foreach ($sql as $s) {
    $wpdb->query($s);
}