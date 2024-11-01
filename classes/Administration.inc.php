<?php 

namespace WPWebVitalsTracking;

if (!defined('ABSPATH')) {
	exit;
}

class Administration
{
    
    public function __construct()
    {
        add_action ('admin_menu', [$this, 'wpwvt_options_page']);
        add_action ('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action ('plugin_action_links_' . plugin_basename( dirname(__DIR__) . '/webvitalstracking.php' ), [$this, 'add_link_to_dashboard']);
    }
    
    public function add_link_to_dashboard($links)
    {
        $links = array_merge( array(
            '<a href="' . esc_url(admin_url('admin.php?page=wpwebvitalstracking')) . '">' . __('Dashboard', 'webvitals-tracking') . '</a>'
        ), $links );
    
        return $links;
    }

    public function enqueue_scripts($hook)
    {
        if ( 'toplevel_page_wpwebvitalstracking' != $hook) {
            return;
        }

        $css_version = filemtime(dirname(__DIR__).'/views/assets/css/admin.css');
        wp_register_style( 'wpwebvitalstracking_css', plugin_dir_url( __DIR__ ) . '/views/assets/css/admin.css', false, $css_version );
        wp_enqueue_style( 'wpwebvitalstracking_css' );
        wp_enqueue_script( 'wpwebvitalstracking_lib', plugin_dir_url( __DIR__ ) . '/views/assets/js/chart.min.js', array(), '' );
        wp_enqueue_script( 'wpwebvitalstracking_annotations', plugin_dir_url( __DIR__ ) . '/views/assets/js/chartjs-plugin-annotation.js', array('wpwebvitalstracking_lib'), '' );
    }
    
    /**
     * top level menu
     */
    public function wpwvt_options_page()
    {
        // add top level menu page
        add_menu_page(
            'Web Vitals Tracking',
            'Web Vitals Tracking',
            'manage_options',
            'wpwebvitalstracking',
            [$this, 'wpwvt_options_page_html']
        );
    }
    
    /**
     * top level menu:
     * callback functions
     */
    function wpwvt_options_page_html()
    {
        // check user capabilities
        if (!current_user_can('manage_options')) return;
        
        include(__DIR__ . '/../views/admin/dashboard.php');
    }
}

new Administration();