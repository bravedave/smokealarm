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

use green;
use Json;

class controllerSmokeAlarmLocations extends controller {
  protected function _index() {
    $dao = new dao\smokealarm_locations;
    $this->data = (object)[
      'dtoSet' => $dao->dtoSet( $dao->getAll())

    ];

    $this->render([
      'primary' => 'locations/report',
      'secondary' => [
        'index'

      ],
      'data' => [
        'title' => $this->title = config::label . ' - locations'

      ]

    ]);

  }

	function edit( $id = 0, $mode = '') {
		$this->data = (object)[
			'title' => $this->title = 'Add Smoke Alarm Location',
			'dto' => new dao\dto\smokealarm_locations

		];

		if ( $id = (int)$id) {
			$dao = new dao\smokealarm_locations;
			if ( $dto = $dao->getByID( $id)) {

				$this->data->title = $this->title = 'Edit Smoke Alarm Location';
				$this->data->dto = $dto;
				$this->load('locations/edit');

			}
			else {
        $this->load('locations/not-found');

			}

		}
		else {
      $this->load('locations/edit');

		}

  }

}