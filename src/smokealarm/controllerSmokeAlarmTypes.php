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

namespace smokealarm;

use green;
use Json;

class controllerSmokeAlarmTypes extends controller {
  protected function _index() {
    $dao = new dao\smokealarm_types;
    $this->data = (object)[
      'dtoSet' => $dao->dtoSet( $dao->getAll())

    ];

    $this->render([
      'primary' => 'types/report',
      'secondary' => [
        'index'

      ],
      'data' => [
        'title' => $this->title = config::label . ' - types'

      ]

    ]);

  }

	function edit( $id = 0) {
		$this->data = (object)[
			'title' => $this->title = 'Add Smoke Alarm Type',
			'dto' => new dao\dto\smokealarm

		];

		if ( $id = (int)$id) {
			$dao = new dao\smokealarm;
			if ( $dto = $dao->getByID( $id)) {

				$this->data->title = $this->title = 'Edit Smoke Alarm Type';
				$this->data->dto = $dto;
				$this->load('types/edit');

			}
			else {
        $this->load('types/not-found');

			}

		}
		else {
      $this->load('types/edit');

		}

  }

}