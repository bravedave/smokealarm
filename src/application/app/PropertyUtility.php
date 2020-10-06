<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
 * styleguide : https://codeguide.co/
*/

abstract class PropertyUtility {
  static function street_index( $street ) {
		$strStreetIndex = $street;	// safety

		if ( is_numeric( substr( $street, 0, 1 ) ) ) {
			$aStreet = explode( ' ', $street );

			$no = array_shift( $aStreet);
			if ( false != strpos( $no, '/')) {
				$_no = explode('/', $no);
				$_r = array_reverse( $_no);
				$no = implode( ' ', $_r);

			}

			$aStreet[] = str_pad( trim( (string)$no), 6, ' ', STR_PAD_LEFT);
			$strStreetIndex = implode( ' ', $aStreet );
			if ( '' == $strStreetIndex)
				$strStreetIndex = $street;	// safety

		}

		return ( $strStreetIndex);

	}

}
