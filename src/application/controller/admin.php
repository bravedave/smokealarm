<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

class admin extends \Controller {
  protected function _index() {
    $this->render([
      'primary' => 'blank',
      'secondary' => [
        'index-admin'

      ]

    ]);

  }

}
