<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

class currentUser extends dvc\currentUser {
	static public function option( $key, $value = null) {
		return ( false);

	}

	static public function isadmin() {
		return ( true);
		return ( false);

	}

	static public function restriction( $key, $value = null ) {
		// if ( 'smokealarm-company' == $key) {
		// 	return '1';

		// }

		return ( false);

	}

}
