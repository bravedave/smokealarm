<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace smokealarm\dao\dto;

use bravedave\dvc\dto;

class smokealarm extends dto {
	public $id = 0;
	public $type = '';
	public $location = '';
	public $make = '';
	public $model = '';
	public $expiry = '';
	public $status = '';
	public $properties_id = 0;
	public $created = '';
	public $updated = '';
	public $address_street = '';
	public $connect = '';

}
