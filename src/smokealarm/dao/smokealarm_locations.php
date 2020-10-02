<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace smokealarm\dao;

use dao\_dao;

class smokealarm_locations extends _dao {
	protected $_db_name = 'smokealarm_locations';
	protected $template = __NAMESPACE__ . '\dto\smokealarm_locations';

	public function getByLocation( string $location) {
		$sql = sprintf(
			'SELECT * FROM `%s` WHERE `location` = "%s"',
			$this->_db_name,
			$this->escape($location)

		);

		if ( $res = $this->Result( $sql)) {
			return $res->dto( $this->template);

		}

		return false;

	}

}
