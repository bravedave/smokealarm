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

class postUpdate extends dvc\service {
  protected function _upgrade() {
    config::route_register( 'people', 'green\\people\\controller');
    config::route_register( 'property', 'green\\properties\\controller'); // for some cms compatibility
    config::route_register( 'properties', 'green\\properties\\controller');
    config::route_register( 'beds', 'green\\beds_list\\controller');
    config::route_register( 'baths', 'green\\baths\\controller');
    config::route_register( 'property_type', 'green\\property_type\\controller');
    config::route_register( 'postcodes', 'green\\postcodes\\controller');

    green\beds_list\config::green_beds_list_checkdatabase();
    green\baths\config::green_baths_checkdatabase();
    green\property_type\config::green_property_type_checkdatabase();
    green\postcodes\config::green_postcodes_checkdatabase();
    // green\property_diary\config::green_property_diary_checkdatabase();
    green\users\config::green_users_checkdatabase();

    green\people\config::green_people_checkdatabase();
    green\properties\config::green_properties_checkdatabase();
    echo( sprintf('%s : %s%s', 'green updated', __METHOD__, PHP_EOL));

    \photolog\config::photolog_checkdatabase();
    config::route_register( config::$PHOTOLOG_ROUTE, 'photolog\controller');
    echo( sprintf('%s : %s%s', 'photolog updated', __METHOD__, PHP_EOL));

    config::smokealarm_checkdatabase();
    config::route_register( 'smokealarm', 'smokealarm\controller');
    config::route_register( 'smokealarmtypes', '');
    config::route_register( 'smokealarmsuppliers', 'smokealarm\controllerSmokeAlarmSuppliers');
    config::route_register( 'smokealarmlocations', 'smokealarm\controllerSmokeAlarmLocations');
    echo( sprintf('%s : %s%s', 'smokealarm  updated', __METHOD__, PHP_EOL));

  }

  static function upgrade() {
    $app = new self( application::startDir());
    $app->_upgrade();

  }

  protected function _importcsv() {
    utility::importcsv();

  }

  protected function _importpropertystatuscsv() {
    utility::importpropertystatuscsv();

  }

  static function importcsv() {
    $app = new self( application::startDir());
    $app->_importcsv();

  }

  static function importpropertystatuscsv() {
    $app = new self( application::startDir());
    $app->_importpropertystatuscsv();

  }

}
