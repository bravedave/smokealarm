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

class smokealarm extends _dao {
	protected $_db_name = 'smokealarm';
	protected $template = __NAMESPACE__ . '\dto\smokealarm';
	protected $_sql_getByID =
		'SELECT
			sa.*,
			p.address_street,
			p.address_suburb,
			p.address_state,
			p.address_postcode
		FROM
			%s sa
			LEFT JOIN properties p on p.id = sa.properties_id
		WHERE
			sa.id = %d';

	protected $_sql_getAll =
		'SELECT
			sa.*,
			p.address_street,
			p.address_suburb,
			p.address_state,
			p.address_postcode
		FROM `smokealarm` sa
			LEFT JOIN properties p on p.id = sa.properties_id';

}
