<?php 

namespace WPWebVitalsTracking;

if (!defined('ABSPATH')) {
	exit;
}

class Records
{
	private $table = 'wpwebvitalstrack';
	private static $instance;

	/**
	 * Define if plugin is in presentation mode or not.
	 * If in presentation mode all data returned are randomly generated.
	 * NOTE: even in presentation mode real data still be collected and saved in the database.
	 *
	 * @var boolean
	 */
	private $presentation_mode = false;

	/**
	 * Retrieve an instance of current class
	 *
	 * @return Records
	 */
	public static function instance()
	{
		if (!self::$instance) {
			self::$instance = new Records();
		}
		return self::$instance;
	}

	/**
	 * Parse params for sanitizing and preparing for usage in query
	 *
	 * @param [type] $params
	 * @return void
	 */
	private function parseParams($params)
	{
		global $wpdb;
		$conditions = [];
		$joins = [];

		// name
		if (isset($params['name'])) {
			$conditions[] = $wpdb->prepare('name = %s', $params['name']);
		}

		// device
		if (isset($params['device'])) {
			$conditions[] = $wpdb->prepare('device = %s', $params['device']);
		}

		// value
		if (isset($params['value'])) {
			$operator = '>';
			if (isset($params['value_operator']) && in_array($params['value_operator'], ['=', '>', '<', '>=', '<='])) {
				$operator = $params['value_operator'];
			}
			$conditions[] = $wpdb->prepare("delta {$operator} %f", $params['value']);
		}

		// date
		if (isset($params['date'])) {
			$operator = '>=';
			if (isset($params['date_operator']) && in_array($params['date_operator'], ['=', '>', '<', '>=', '<='])) {
				$operator = $params['date_operator'];
			}
			$conditions[] = $wpdb->prepare("date_created {$operator} %s", $params['date']);
		}

		return [
			'conditions' => $conditions,
			'joins' => $joins
		];
	}

	/**
	 * Get records matching $params
	 *
	 * @param array $params Allowed are: name, device, value, value_operator, date, date_operator. See parse_params for more details
	 * @param string $groupped For which interval group results. Allowed are: month, day, null. Using null the results are not groupped.
	 * @param string $orderby Order by param. Allowed are: value, date.
	 * @param string $order Order param. Allowed are: DESC, ASC.
	 * @param integer $offset Useful for pagination.
	 * @param integer $limit How many results obtain at max.
	 * @return array
	 */
	public function get($params, $groupped = 'month', $orderby = 'date', $order = 'DESC', $offset = 0, $limit = 20)
	{
		global $wpdb;
		$whjo = $this->parseParams($params);

		$wheres = implode(' AND ', $whjo['conditions']);
		$wheres = !empty($wheres) ? ' AND ' . $wheres : '';
		$joins = implode(' ', $whjo['joins']);

		$order = in_array($order, ['DESC', 'ASC']) ? $order : 'DESC';

		// order parse
		switch($orderby) {
			case 'value':
				$order_sql = 'delta '.$order.', r.name ASC';
			break;
			case 'date':
			default:
				$order_sql = 'r.name ASC, date_created '.$order;
			break;
		}

		$groupby = '';
		$fields = 'r.*';
		if ($groupped) {
			switch($groupped) {
				case 'day':
					$format = '%Y-%m-%d %H';
					// presentation mode 
					if ($this->presentation_mode) return $this->getRandom24HoursData();
				break;
				case 'month':
				default:
					$format = '%Y-%m-%d';
					// presentation mode 
					if ($this->presentation_mode) return $this->getRandomMonthData();
				break;
			}
			$fields = 'r.name, DATE_FORMAT(r.date_created, \''.$format.'\') as date, AVG(r.delta) as avg';
			$groupby = 'GROUP BY r.name, DATE_FORMAT(r.date_created, \''.$format.'\')';
			$order_sql = 'r.name ASC, date ' . $order;
		}

		$sql = "SELECT {$fields} FROM {$wpdb->prefix}{$this->table} AS r {$joins} WHERE 1 {$wheres} {$groupby} ORDER BY {$order_sql} " . 
			$wpdb->prepare(
				"LIMIT %d, %d", 
				$offset,
				$limit
			);

		return $wpdb->get_results($sql);
	}

	/**
	 * Get results groupped by path.
	 *
	 * @param array $params Allowed are: name, device, value, value_operator, date, date_operator. See parse_params for more details
	 * @param integer $offset Useful for pagination.
	 * @param integer $limit How many results obtain at max.
	 * @return array
	 */
	public function getByPaths($params, $offset = 0, $limit = 20)
	{
		// presentation mode 
		if ($this->presentation_mode) return $this->getRandomPathData($params);

		global $wpdb;
		$whjo = $this->parseParams($params);

		$wheres = implode(' AND ', $whjo['conditions']);
		$wheres = !empty($wheres) ? ' AND ' . $wheres : '';
		$joins = implode(' ', $whjo['joins']);

		$sql = "SELECT r.path, AVG(r.delta) as avg FROM {$wpdb->prefix}{$this->table} AS r {$joins} WHERE 1 {$wheres} GROUP BY r.path ORDER BY avg DESC " . 
			$wpdb->prepare(
				"LIMIT %d, %d", 
				$offset,
				$limit
			);
		
		return $wpdb->get_results($sql);
	}

	/**
	 * Get average metric values by device.
	 *
	 * @param string $device Device to search for. Allowed are: desktop, mobile.
	 * @param string $from_date It's a strtotime compatibile string. Default to "24 hours ago"
	 * @return array
	 */
	public function getAVG($device = 'mobile', $from_date = null)
	{
		// presentation mode 
		if ($this->presentation_mode) return $this->getRandomAVGData();

		global $wpdb;

		if (!$from_date || !strtotime($from_date)) {
			$from_date = '24 hours ago';
		}

		$date = date('Y-m-d H:i:s', strtotime($from_date));

		$sql = $wpdb->prepare("SELECT name, AVG(delta) as avg 
						FROM {$wpdb->prefix}{$this->table} 
						WHERE device = %s and date_created >= %s 
						GROUP BY name
						ORDER BY name ASC", 
				$device,
				$date
			);

		$results = $wpdb->get_results($sql);

		if (!$results) {
			return [];
		}

		$formatted = [];
		foreach($results as $res) {
			$formatted[$res->name] = $res->avg;
		}

		return $formatted;
	}

	/**
	 * Get metric ranges. Useful for display colors or warnings.
	 *
	 * @return array
	 */
	public function getRanges()
	{
		$red = 'rgba(255, 0, 0, 0.5)';
		$orange = 'rgba(255, 200, 0, 0.5)';
		$green = 'rgba(0, 255, 0, 0.5)';
		return [
			'LCP' => [
				[0, 2.5, 'label' => __('Good', 'webvitals-tracking'), 'color' => $green],
				[2.5, 4, 'label' => __('Needs improvement', 'webvitals-tracking'), 'color' => $orange],
				[4, 'label' => __('Poor', 'webvitals-tracking'), 'color' => $red]
			],
			'FID' => [
				[0, 100, 'label' => __('Good', 'webvitals-tracking'), 'color' => $green],
				[100, 300, 'label' => __('Needs improvement', 'webvitals-tracking'), 'color' => $orange],
				[300, 'label' => __('Poor', 'webvitals-tracking'), 'color' => $red]
			],
			'CLS' => [
				[0, 0.1, 'label' => __('Good', 'webvitals-tracking'), 'color' => $green],
				[0.1, 0.25, 'label' => __('Needs improvement', 'webvitals-tracking'), 'color' => $orange],
				[0.25, 'label' => __('Poor', 'webvitals-tracking'), 'color' => $red]
			]
		];
	}

	/**
	 * Only for test purpose
	 *
	 * @return array Random data
	 */
	private function getRandom24HoursData()
	{
		// random
		$records = [];
		$date_from = new \DateTime();
		$date_from->setTimestamp(strtotime('-1 day'));

		$date = clone $date_from;
		for($i=0; $i<24; $i++) {
			$records[] = (object) [
				'date' => $date->format('Y-m-d H'),
				'name' => 'CLS',
				'avg' => rand(50, 230) / 1000
			];
			$date->add(new \DateInterval('PT1H'));
		}
		$date = clone $date_from;
		for($i=0; $i<24; $i++) {
			$records[] = (object) [
				'date' => $date->format('Y-m-d H'),
				'name' => 'FID',
				'avg' => rand(100, 300)
			];
			$date->add(new \DateInterval('PT1H'));
		}
		$date = clone $date_from;
		for($i=0; $i<24; $i++) {
			$records[] = (object) [
				'date' => $date->format('Y-m-d H'),
				'name' => 'LCP',
				'avg' => rand(78, 500) * 10
			];
			$date->add(new \DateInterval('PT1H'));
		}

		return $records;
	}

	/**
	 * Only for test purpose
	 *
	 * @return array Random data
	 */
	private function getRandomMonthData()
	{
		// random
		$records = [];
		$date_from = new \DateTime();
		$date_from->setTimestamp(strtotime('-1 month'));

		$date = clone $date_from;
		for($i=0; $i<30; $i++) {
			$records[] = (object) [
				'date' => $date->format('Y-m-d'),
				'name' => 'CLS',
				'avg' => rand(50, 230) / 1000
			];
			$date->add(new \DateInterval('P1D'));
		}
		$date = clone $date_from;
		for($i=0; $i<30; $i++) {
			$records[] = (object) [
				'date' => $date->format('Y-m-d'),
				'name' => 'FID',
				'avg' => rand(100, 300)
			];
			$date->add(new \DateInterval('P1D'));
		}
		$date = clone $date_from;
		for($i=0; $i<30; $i++) {
			$records[] = (object) [
				'date' => $date->format('Y-m-d'),
				'name' => 'LCP',
				'avg' => rand(78, 500) * 10
			];
			$date->add(new \DateInterval('P1D'));
		}

		return $records;
	}

	/**
	 * Only for test purpose
	 *
	 * @param $params same as getByPaths
	 * @return array Random data
	 */
	private function getRandomPathData($params)
	{
		if (!isset($params['name'])) {
			$params['name'] = 'CLS';
		}
		// random path to use
		$paths = ['/awesome-web-page/', '/contacts/', '/', '/about-us/', 
					'/search/', '/checkout/', '/cart/', '/products/red-shirt-v/',
					'/products/category/', '/why-us/', '/compare/', '/reviews/',
					'/procuts/blue-shirt-v/', '/procuts/blue-shirt-x/', '/procuts/blue-shirt-y/',
					'/procuts/black-shirt-v/', '/procuts/yellow-shirt-x/', '/procuts/green-shirt-y/',
					'/blog/article/', '/category/trending-topic/', '/pricing/'];
		
		// max number of records to retrieve
		$max = rand(3,10);
		$res = [];

		// to avoid duplicates
		$path_already_done = [];

		for ($i=0; $i<$max; $i++) {
			switch ($params['name']) {
				case 'FID':
					$avg = rand(100, 500);
				break;
				case 'LCP':
					$avg = rand(250, 560) * 10;
				break;
				default:
				case 'CLS':
					$avg = rand(100, 300) / 1000;
				break;
			}

			// get random unique path
			$path = $paths[rand(0, count($paths)-1)];
			while (in_array($path, $path_already_done)) {
				$path = $paths[rand(0, count($paths)-1)];
			}

			$path_already_done[] = $path;

			// get random objects with random path
			$res[] = (object) [
				'path' => $path,
				'avg' => $avg
			];
		}

		// sort by avg DESC 
		// function cmp($a, $b)
		// {
		// 	return $a->avg <> $b->avg;
		// }

		usort($res, function($a, $b) {
			return ($b->avg*1000) - ($a->avg*1000);
		});

		return $res;
	}

	/**
	 * Only for test purpose
	 *
	 * @return array Random data
	 */
	private function getRandomAVGData()
	{
		return [
			'CLS' => rand(50, 120) / 1000,
			'FID' => rand(50, 120),
			'LCP' => rand(78, 260) * 10
		];
	}
}