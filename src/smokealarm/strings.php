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

class strings extends \strings {
	static function GoodStreetString( $street ) {
		if ( preg_match( '/The\s?Drive/i', $street ))
			return ( $street);
		if ( preg_match( '/The\s?Avenue/i', $street ))
			return ( $street);

		$find = [
			'@\sroad$@i','@\sroad,@i',
			'@\sstreet$@i','@\sstreet,@i','@\sstreet\s@i',
			'@\savenue$@i','@\savenue,@i','@\save$@i',
			'@\sparade$@i','@\spde$@i','@\sparade,@i','@\spde,@i',
			'@\sterrace$@i','@\stce$@i','@\sterrace,@i','@\stce,@i',
			'@\sdrive$@i','@\sdrive,@i',
			'@\splace$@i','@\splace,@i',
			'@\scourt$@i','@\scourt,@i',
			'@\screscent$@i','@\screscent,@i'
			];
		$replace = [
			' Rd',' Rd,',
			' St',' St,',' St, ',
			' Av',' Av,',' Av,',
			' Pd',' Pd',' Pd,',' Pd,',
			' Tc',' Tc',' Tc,',' Tc,',
			' Dr',' Dr,',
			' Pl',' Pl,',
			' Ct',' Ct,',
			' Cres',' Cres,'
			];


		return ( trim( preg_replace( $find, $replace, $street ), ', '));

	}

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