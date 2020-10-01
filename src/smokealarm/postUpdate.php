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

    green\properties\config::green_properties_checkdatabase();
    echo( sprintf('%s : %s%s', 'green updated', __METHOD__, PHP_EOL));

    config::route_register( 'smokealarm', 'smokealarm\controller');
    config::route_register( 'smokealarmtypes', 'smokealarm\controllerSmokeAlarmTypes');
    echo( sprintf('%s : %s%s', 'smokealarm  updated', __METHOD__, PHP_EOL));

  }

  static function upgrade() {
    $app = new self( application::startDir());
    $app->_upgrade();

  }

}
