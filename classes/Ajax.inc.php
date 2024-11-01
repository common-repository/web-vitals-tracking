<?php 

namespace WPWebVitalsTracking;

if (!defined('ABSPATH')) {
	exit;
}

class Ajax
{
    public function __construct()
    {
        add_action('wp_ajax_webvitalstrack', [$this, 'ajax_track_webvitals']);
        add_action('wp_ajax_nopriv_webvitalstrack', [$this, 'ajax_track_webvitals']);
    }

    private function die($code, $msg)
    {
        http_response_code($code);
        if (defined('WP_DEBUG') && WP_DEBUG)
            wp_die($msg);
        else 
            wp_die(0);
    }

    private function checkRequiredFieldsOrDie($field, $type = 'string')
    {
        if (!isset($_POST[$field])) {
            $this->die(400, "Field {$field} missing or empty");
        }

        switch ($type) {
            case 'int':
                if (!filter_var($_POST[$field], FILTER_VALIDATE_FLOAT)) {
                    $this->die(400, "Field {$field} not valid");
                }
            break;
            case 'string':
            default:
                if (empty($_POST[$field])) {
                    $this->die(400, "Field {$field} not valid");
                }
            break;
        }

        return $_POST[$field];
    }

    public function ajax_track_webvitals()
    {
        // does not require nonce but required http_referer set to current domain to prevent data flooding from malicious users
        if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], get_site_url()) === FALSE) {
            http_response_code(401);
            wp_die(0);
        }

        $name = $this->checkRequiredFieldsOrDie('name');
        $device = $this->checkRequiredFieldsOrDie('device');
        $delta = $this->checkRequiredFieldsOrDie('delta', 'int');
        $id_view = $this->checkRequiredFieldsOrDie('id_view');
        $path = $this->checkRequiredFieldsOrDie('path');

        if (!in_array($device, ['mobile','desktop','tablet'])) {
            http_response_code(400);
            if (defined('WP_DEBUG') && WP_DEBUG)
                wp_die("Field {$field} not valid");
            else 
                wp_die(0);
        }

        $record = new Record();

        $record->name = $name;
        $record->device = $device;
        $record->delta = $delta;
        $record->id_view = $id_view;
        $record->path = $path;

        $res = $record->save();

        wp_die(!!$res);
    }
}

new Ajax();