<?php 

namespace WPWebVitalsTracking;

if (!defined('ABSPATH')) {
	exit;
}

class Record
{
    private $fields = [     'id_record',    'name', 'device',   'delta',    'id_view',  'path', 'date_created'];
    private $fields_type = ['%d',           '%s',   '%s',       '%f',       '%s',       '%s',   '%s'];

    private $table = 'wpwebvitalstrack';

    /**
     * Constructor
     *
     * @param int $id
     */
    public function __construct($id = null)
    {
        foreach ($this->fields as $f) {
            $this->{$f} = null;
        }

        if ($id) {
            $this->getFromDB($id);
        }
    }

    /**
     * Fetch record from db
     *
     * @param int $id
     * @return boolean
     */
    private function getFromDB($id)
    {
        global $wpdb;
        $row = $wpdb->getRow($wpdb->prepare("SELECT * FROM {$wpdb->prefix}{$this->table} WHERE id_record = %d", $id));

        if ($row) {
            $keys = get_object_vars($row);
            foreach ($keys as $k) {
                $this->{$k} = $row->{$k};
            }
            return true;
        }

        return false;
    }

    /**
     * Get id_record
     *
     * @return int
     */
    public function getIdRecord()
    {
        return $this->id_record;
    }
    
    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Get device (desktop/mobile)
     *
     * @return string
     */
    public function getDevice()
    {
        return $this->device;
    }
    
    /**
     * Get delta (value)
     *
     * @return float
     */
    public function getDelta()
    {
        return $this->delta;
    }

    /**
     * Get id view of the measured metric
     *
     * @return string
     */
    public function getIdView()
    {
        return $this->id_view;
    }
    
    /**
     * Get path of the measured metric.
     * Relative url.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
    
    /**
     * Get date when the record has been created (about 1second after the metric has been captured)
     *
     * @return string
     */
    public function getDateCreated()
    {
        return $this->date_created;
    }

    /**
     * Save record on the database. Create or update depending on the id_record field. 
     * Update if not empty, else insert.
     *
     * @return boolean
     */
    public function save()
    {
        global $wpdb;
        if ($this->id_record) {
            // update
            return $wpdb->update(
                $wpdb->prefix . $this->table,
                $this->getFields(),
                [
                    'id_record' => $this->id_record
                ],
                $this->getFieldsType(),
                ['%d']
            );
        } else {
            // insert
            $this->date_created = date('Y-m-d H:i:s');

            $result = $wpdb->insert(
                $wpdb->prefix . $this->table,
                $this->getFields(),
                $this->getFieldsType()
            );

            if (!$result) {
                return false;
            }

            $this->id_record = $wpdb->insert_id;

            return $this->id_record;
        }
    }

    /**
     * Delete metric from the database
     *
     * @return boolean
     */
    public function delete()
    {
        if (!$this->id_record) return false;
        global $wpdb;
        return $wpdb->delete(
            $wpdb->prefix . $this->table,
                [
                    'id_record' => $this->id_record
                ],
                ['%d']
            );
    }

    /**
     * Get current instance fields as array key => value
     *
     * @return array
     */
    private function getFields()
    {
        $res = [];
        foreach ($this->fields as $f) {
            $res[$f] = $this->{$f};
        }
        return $res;
    }

    /**
     * Get current instance fields type for preparing sql
     *
     * @return array
     */
    private function getFieldsType()
    {
        return $this->fields_type;
    }

    /**
     * Format value to be user understandable
     *
     * @param string $name Name of the metric, one of CLS, LCP, FID
     * @param float $value The value of the metric
     * @return float
     */
    public static function formatValue($name, $value)
    {
        $new_value = $value;

        // scale it back
        if ($name == 'LCP') {
            $new_value = $value / 1000; // avg è in ms, lo voglio visualizzare in secondi
        }

        // remove extra precision
        switch ($name) {
            case 'FID': 
                $new_value = round($new_value * 10) / 10;
            break;
            case 'LCP': 
            case 'CLS': 
            default:
                $new_value = round($new_value * 100) / 100;
            break;
        }

        return $new_value;
    }

    /**
     * Get color appropriate for current value.
     *
     * @param string $name Name of the metric, one of CLS, LCP, FID
     * @param float $value The value of the metric
     * @param string $color Default color if none appliable. 
     * @return string
     */
    public static function getValueColor($name, $value, $color = 'none')
    {
        $ranges = Records::instance()->getRanges();
        if (!isset($ranges[$name])) return $color;

        foreach ($ranges[$name] as $range) {
            if ($range[0] > $value) break;
            $color = $range['color'];
        }

        return $color;
    }
}