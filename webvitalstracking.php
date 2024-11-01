<?php
/*
    Plugin Name: Web Vitals Tracking
    Description: Track core web vitals data and save history, powers a dashboard with 24h and one month of historic data, shows worsts pages. Send also into Google Analytics and can be extended to send also to Google Tag Manager and third parties.
    Version: 1.0.1
    Author: Enrico Atzeni
    License:     GPL v2 or later
    License URI: https://www.gnu.org/licenses/gpl-2.0.html
    Text Domain: webvitals-tracking
    Domain Path: /languages
*/

class WPWebVitalsTracking
{
    public function __construct()
    {
        include 'classes/load.php';
        register_activation_hook( __FILE__, [$this, 'install'] );
        register_deactivation_hook( __FILE__, [$this, 'uninstall'] );

        add_action('wp_enqueue_scripts', [$this, 'add_webvitalsscript']);
    }

    public function install()
    {
        // install Tables
        include('sql/install.php');
    }
    
    public function uninstall()
    {
        // uninstall Tables
        include('sql/uninstall.php');
    }

    public function add_webvitalsscript()
    {
        wp_enqueue_script ('webvitals-tracking', plugin_dir_url( __FILE__ ) . '/views/assets/js/webvitals-0.2.2.js', array(), '', true);
        wp_localize_script('webvitals-tracking', 'wbwvt', [
            'admin_ajax_url' => admin_url('admin-ajax.php')
        ]);
    }
}

new WPWebVitalsTracking();
