<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

class home extends \Controller {
  protected function _index() {
    $this->render([
      'primary' => 'blank',
      'secondary' => [
        'index'

      ]

    ]);

  }

}
