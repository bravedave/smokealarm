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

use green;
use smokealarm;

class properties extends green\properties\dao\properties {
  public function smokealarmNotesPath( \green\properties\dao\dto\properties $dto) : string {
    if ( $store = $this->smokealarmStore( $dto)) {
      return implode( DIRECTORY_SEPARATOR, [
        $store,
        'notes.txt'

      ]);

    }

    return '';

  }

  public function smokealarmNotes( \green\properties\dao\dto\properties $dto) : string {
    if ( $path = $this->smokealarmNotesPath( $dto)) {
      if ( \file_exists( $path)) {
        return \file_get_contents( $path);

      }

    }

    return '';

  }

  public function smokealarmStore( \green\properties\dao\dto\properties $dto) : string {
    $store = implode( DIRECTORY_SEPARATOR, [
      smokealarm\config::smokealarm_store(),
      $dto->id

    ]);

		if ( !is_dir( $store)) {
			mkdir( $store, 0777);
			chmod( $store, 0777);

		}

    return realpath( $store);

  }

}
