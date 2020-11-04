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
use strings;

class properties extends green\properties\dao\properties {
  public function hasSmokeAlarmComplianceCertificate( $dto) : bool {
    if ( $dto->smokealarms_tags) {
      // \sys::dump( $dto);

      $tags = json_decode( $dto->smokealarms_tags);
      if ( isset( $tags->{smokealarm\config::smokealarm_tag_smoke_alarm_certificate})) {
        if ( $cert = $tags->{smokealarm\config::smokealarm_tag_smoke_alarm_certificate}) {
          $certPath = implode( DIRECTORY_SEPARATOR, [
            $this->smokealarmStore( $dto),
            $cert

          ]);

          if ( \file_exists( $certPath)) {
            // \sys::logger( sprintf('<%s> %s', $certPath, __METHOD__));
            return true;

          }

        }

      }

    }

    return false;

  }

  public function smokealarmNotesPath( $dto) : string {
    if ( $store = $this->smokealarmStore( $dto)) {
      return implode( DIRECTORY_SEPARATOR, [
        $store,
        'notes.txt'

      ]);

    }

    return '';

  }

  public function smokealarmNotes( $dto) : string {
    if ( $path = $this->smokealarmNotesPath( $dto)) {
      if ( \file_exists( $path)) {
        return \file_get_contents( $path);

      }

    }

    return '';

  }

  public function smokealarmStore( $dto) : string {
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
