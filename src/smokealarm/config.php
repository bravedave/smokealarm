<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace smokealarm;

use Json;

class config extends \config {
  const label = 'Smoke Alarms 2022';
	const smokealarm_db_version = 0.24;

	const smokealarm_connect = [
		'HW - I/C',
		'HW - I/C RF',
		'HW - no connect',
		'WL - I/C',
		'WL - unable to connect'

	];

	const smokealarm_status = [
		'pass',
		'pass (x22)',
		'installed',
		'expired',
		'exp/replaced',
		'exp/replaced (x22)',
		'fail',
		'fail/replaced',
		'fail/replaced (x22)',
		'not required',
		'removed',

	];

	const smokealarm_status_compliant = [
		'compliant',
		'exp/replaced',
		'fail/replaced',
		'installed',
		'pass',

	];

	const smokealarm_tag_smoke_alarm_certificate = 'Smoke Alarm Certificate';

	const smokealarm_tags = [
		self::smokealarm_tag_smoke_alarm_certificate,
		'Floorplan',
		'I\'ll add more later'

	];

	/**
	 * format is per php strtotime
	 * see https://www.php.net/manual/en/function.strtotime.php
	 */
	const smokealarm_valid_time = '+1 year';
	const smokealarm_warn_time = '+11 months';

	static $PHOTOLOG_ROUTE = 'photolog';
	static $SMOKEALARM_IMPORT_ADD_PROPERTIES = false;
  // static protected $_SMOKEALARM_VERSION = 0;

	// static protected function smokealarm_version( $set = null) {
	// 	$ret = self::$_SMOKEALARM_VERSION;

	// 	if ( (float)$set) {
	// 		$j = Json::read( $config = self::smokealarm_config());

	// 		self::$_SMOKEALARM_VERSION = $j->smokealarm_version = $set;

	// 		Json::write( $config, $j);

	// 	}

	// 	return $ret;

	// }

	// static function smokealarm_checkdatabase() {
	// 	if ( self::smokealarm_version() < self::smokealarm_db_version) {
  //     $dao = new dao\dbinfo;
	// 		$dao->dump( $verbose = false);

	// 		config::smokealarm_version( self::smokealarm_db_version);

	// 	}

	// }

	static function smokealarm_checkdatabase() {
		$dao = new dao\dbinfo(null, method_exists(__CLASS__, 'cmsStore') ? self::cmsStore() : self::dataPath());
		// $dao->debug = true;
		$dao->checkVersion('smokealarm', self::smokealarm_db_version);
	}

	static function smokealarm_config() {
		return implode( DIRECTORY_SEPARATOR, [
      rtrim( self::dataPath(), '/ '),
      'smokealarm.json'

    ]);

	}

	static function smokealarm_import_csv() {
		return realpath( implode( DIRECTORY_SEPARATOR, [
      rtrim(method_exists(__CLASS__, 'cmsStore') ? self::cmsStore() : self::dataPath(), '/ '),
      'smokealarm_import.csv'

    ]));

	}

	static function smokealarm_propertystatus_import_csv() {
		return realpath( implode( DIRECTORY_SEPARATOR, [
      rtrim(method_exists(__CLASS__, 'cmsStore') ? self::cmsStore() : self::dataPath(), '/ '),
      'smoke-alarm-property-status.csv'

    ]));

	}

  static function smokealarm_init() {
    $_a = [
      'import_add_properties' => self::$SMOKEALARM_IMPORT_ADD_PROPERTIES,

    ];

		if ( file_exists( $config = self::smokealarm_config())) {

      $j = (object)array_merge( $_a, (array)Json::read( $config));

      self::$SMOKEALARM_IMPORT_ADD_PROPERTIES = (float)$j->import_add_properties;
      // self::$_SMOKEALARM_VERSION = (float)$j->smokealarm_version;

		}

	}

	static function smokealarm_store() {
		$store = implode( DIRECTORY_SEPARATOR, [
      rtrim(method_exists(__CLASS__, 'cmsStore') ? self::cmsStore() : self::dataPath(), '/ '),
      'smokealarm'

		]);

		if ( !is_dir( $store)) {
			mkdir( $store, 0777);
			chmod( $store, 0777);

		}

		return realpath( $store);

	}

}

config::smokealarm_init();
