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

class config extends \config {
  const label = 'Smoke Alarms';
  const smokealarm_db_version = 0.03;

  static protected $_SMOKEALARM_VERSION = 0;

	static protected function smokealarm_version( $set = null) {
		$ret = self::$_SMOKEALARM_VERSION;

		if ( (float)$set) {
			$config = self::smokealarm_config();

			$j = file_exists( $config) ?
				json_decode( file_get_contents( $config)):
				(object)[];

			self::$_SMOKEALARM_VERSION = $j->smokealarm_version = $set;

			file_put_contents( $config, json_encode( $j, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

		}

		return $ret;

	}

	static function smokealarm_checkdatabase() {
		if ( self::smokealarm_version() < self::smokealarm_db_version) {
      $dao = new dao\dbinfo;
			$dao->dump( $verbose = false);

			config::smokealarm_version( self::smokealarm_db_version);

		}

	}

	static function smokealarm_config() {
		return implode( DIRECTORY_SEPARATOR, [
      rtrim( self::dataPath(), '/ '),
      'smokealarm.json'

    ]);

	}

  static function smokealarm_init() {
    $_a = [
      'smokealarm_version' => self::$_SMOKEALARM_VERSION,

    ];

		if ( file_exists( $config = self::smokealarm_config())) {

      $j = (object)array_merge( $_a, (array)json_decode( file_get_contents( $config)));

      self::$_SMOKEALARM_VERSION = (float)$j->smokealarm_version;

		}

	}

}

config::smokealarm_init();
