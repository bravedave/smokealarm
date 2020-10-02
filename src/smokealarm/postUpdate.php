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

use application;
use dvc;
use green;
use strings;
use ParseCsv;

class postUpdate extends dvc\service {
  protected function _upgrade() {

    green\properties\config::green_properties_checkdatabase();
    echo( sprintf('%s : %s%s', 'green updated', __METHOD__, PHP_EOL));

    config::smokealarm_checkdatabase();
    config::route_register( 'smokealarm', 'smokealarm\controller');
    config::route_register( 'smokealarmtypes', '');
    config::route_register( 'smokealarmlocations', 'smokealarm\controllerSmokeAlarmLocations');
    echo( sprintf('%s : %s%s', 'smokealarm  updated', __METHOD__, PHP_EOL));

  }

  static function upgrade() {
    $app = new self( application::startDir());
    $app->_upgrade();

  }

  protected function _importcsv() {
    if ( $csvFile = config::smokealarm_import_csv()) {
      $csv = new ParseCsv\Csv( $csvFile);
      // sys::dump($csv->data, null, false);

      $cmsIDs = [];
      $locations = [];

      $idebug = 0;

      foreach ($csv->data as $data) {

        if ( $data['Location'] && !\in_array( $data['Location'], $locations)) {
          $locations[] = $data['Location'];
          $dao = new dao\smokealarm_locations;
          if ( !$dao->getByLocation( $data['Location'])) {
            $a = [
              'location' => $data['Location']

            ];

            $dao->Insert($a);

          }

        }

        $id = $data['CMS ID'];
        if ( !\in_array( $data['CMS ID'], $cmsIDs)) {
          $cmsIDs[] = $data['CMS ID'];

          $dao = new dao\properties;
          if ( !$dto = $dao->getByID( $data['CMS ID'])) {
            if ( config::$SMOKEALARM_IMPORT_ADD_PROPERTIES) {
              $a = [
                'id' => $data['CMS ID'],
                'address_street' => sprintf( '%s %s', $data['No'], $data['Street']),

              ];

              $id = $dao->db->Insert( 'properties', $a);

            }
            else {
              sys::dump( $data, null, false);
              throw new \Exception('Property Not Found');

            }

          }

        }

        // print_r( $data);
        // if ( 3 == ++$idebug) {
        //   exit;

        // }

        if ( 'Smoke Alarm Upgrade' == $data['Type']) {
          // this is the summary line
          $a = [];

          // print_r( $data);
          // exit;

          if ( '0 - Alarms meet AS3786:2014 AND are interconnected' == $data['Sum total of alarms required']) {
            $a['smokealarms_2022_compliant'] = 'yes';

          }
          elseif ( (int)$data['Sum total of alarms required']) {
            $a['smokealarms_required'] = (int)$data['Sum total of alarms required'];

          }

          if ( $data['Power Source']) {
            if ( 'non removable battery' == strtolower( $data['Power Source'])) {
              $a['smokealarms_power'] = 'battery';

            }
            elseif ( 'hard wired' == strtolower( $data['Power Source'])) {
              $a['smokealarms_power'] = 'mains';

            }
            elseif ( 'combination' == strtolower( $data['Power Source'])) {
              $a['smokealarms_power'] = 'combination';

            }

          }

          if ( $a) {
            $dao = new dao\properties;
            $dao->UpdateByID( $a, $data['CMS ID']);

          }

        }
        else {
          // it's an alarm
          $status = 'non compliant';
          if ( \in_array( $data['Status'], ['Existing', 'Replacement'])) {
            $status = 'compliant';

          }

          $a = [
            'properties_id' => $id,
            'location' => $data['Location'],
            'make' => $data['Make'],
            'model' => $data['Type and Power Source'],
            'expiry' => strings::BRITISHDateAsANSI( $data['Date of expiry']),
            'status' =>$status

          ];

          $dao = new dao\smokealarm;
          $dao->Insert( $a);

        }

      }

      \rename( $csvFile, \preg_replace( '@csv$@', 'bak', $csvFile));
      print 'done';

    }

  }

  static function importcsv() {
    $app = new self( application::startDir());
    $app->_importcsv();

  }

}
