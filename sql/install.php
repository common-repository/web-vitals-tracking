<?php 

if (!defined('ABSPATH')) {
	exit;
}

global $wpdb;

$sql = [
    "CREATE TABLE `{$wpdb->prefix}wpwebvitalstrack` (
        `id_record` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(50) NOT NULL,
        `device` varchar(10) NOT NULL,
        `delta` double(10,3) NOT NULL,
        `id_view` varchar(40) NOT NULL,
        `path` varchar(255) NOT NULL,
        `date_created` datetime NOT NULL,
        PRIMARY KEY (`id_record`)
       ) ENGINE=InnoDB CHARSET=utf8",

    "ALTER TABLE `{$wpdb->prefix}wpwebvitalstrack` ADD INDEX `search` (`device`, `name`, `date_created`, `delta`, `id_view`);"
];

foreach ($sql as $s) {
    $wpdb->query($s);
}