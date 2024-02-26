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

abstract class config extends bravedave\dvc\config {

  public static function cmsStore() {
    return self::dataPath();
  }
}
