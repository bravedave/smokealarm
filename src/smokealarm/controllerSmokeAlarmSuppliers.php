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

class controllerSmokeAlarmSuppliers extends controller {
  protected function _index() {
    $dao = new dao\smokealarm_suppliers;
    $this->data = (object)[
      'dtoSet' => $dao->dtoSet( $dao->getAll()),
      'showimport' => $dao->count() < 1

    ];

    $this->render([
      'primary' => 'suppliers/report',
      'secondary' => [
        'index'

      ],
      'data' => [
        'title' => $this->title = config::label . ' - suppliers'

      ]

    ]);

  }

	function edit( $id = 0, $mode = '') {
		$this->data = (object)[
			'title' => $this->title = 'Add Smoke Alarm Supplier',
			'dto' => new dao\dto\smokealarm_suppliers

		];

		if ( $id = (int)$id) {
			$dao = new dao\smokealarm_suppliers;
			if ( $dto = $dao->getByID( $id)) {

				$this->data->title = $this->title = 'Edit Smoke Alarm Supplier';
				$this->data->dto = $dto;
				$this->load('suppliers/edit');

			}
			else {
        $this->load('suppliers/not-found');

			}

		}
		else {
      $this->load('suppliers/edit');

		}

  }

}