<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace cms;

use bravedave;

class currentUser extends bravedave\dvc\currentUser {

	static public function option( $key, $value = null) {
		return ( false);
	}

	static public function isadmin() {
		// return ( true);
		return ( false);

	}

	static public function restriction( $key, $value = null ) {
		if ( 'smokealarm-admin' == $key) {
			return 'yes';

		}

		// if ( 'smokealarm-company' == $key) {
		// 	return '1';

		// }

		return ( false);

	}

}
