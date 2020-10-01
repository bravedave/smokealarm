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

class smokealarm_types extends _dao {
	protected $_db_name = 'smokealarm_types';
	protected $template = __NAMESPACE__ . '\dto\smokealarm_types';
}
