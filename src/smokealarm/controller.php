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

class controller extends \Controller {
  protected $viewPath = __DIR__ . '/views/';

  protected function _index() {
    $dao = new dao\smokealarm;
    $this->data = (object)[
      'dtoSet' => $dao->dtoSet( $dao->getAll())

    ];

    $this->render([
      'primary' => 'report',
      'secondary' => [
        'index'

      ],
      'data' => [
        'title' => $this->title = config::label

      ]

    ]);

  }

	protected function before() {
		config::smokealarm_checkdatabase();
		parent::before();

  }

	protected function posthandler() {
    $action = $this->getPost('action');

    if ( 'save-smokealarm' == $action) {
      $a = [
        'expiry' => $this->getPost('expiry'),
        'location' => $this->getPost('location'),
        'make' => $this->getPost('make'),
        'model' => $this->getPost('model'),
        'power' => $this->getPost('power'),
        'properties_id' => $this->getPost('properties_id'),
        'status' => $this->getPost('status'),
        'type' => $this->getPost('type'),

      ];

      $dao = new dao\smokealarm;
      if ( $id = (int)$this->getPost('id')) {
        $dao->UpdateByID( $a, $id);

      }
      else {
        $id = $dao->Insert( $a);

      }

      Json::ack( $action)
        ->add( 'id', $id);

		}
    elseif ( 'save-smokealarm-type' == $action) {
      $a = [
        'type' => $this->getPost('type'),

      ];

      $dao = new dao\smokealarm_types;
      if ( $id = (int)$this->getPost('id')) {
        $dao->UpdateByID( $a, $id);

      }
      else {
        $id = $dao->Insert( $a);

      }

      Json::ack( $action)
        ->add( 'id', $id);

		}
    elseif ( 'search-properties' == $action) {
			if ( $term = $this->getPost('term')) {
				Json::ack( $action)
					->add( 'term', $term)
					->add( 'data', green\search::properties( $term));

			} else { Json::nak( $action); }

		}
		else {
			parent::postHandler();

		}

  }

	function edit( $id = 0) {
		$this->data = (object)[
			'title' => $this->title = 'Add Smoke Alarm',
			'dto' => new dao\dto\smokealarm

		];

		if ( $id = (int)$id) {
			$dao = new dao\smokealarm;
			if ( $dto = $dao->getByID( $id)) {

				$this->data->title = $this->title = 'Edit Smoke Alarm';
				$this->data->dto = $dto;
				$this->load('edit-smokealarm');

			}
			else {
				$this->load('smokealarm-not-found');

			}

		}
		else {
			$this->load('edit-smokealarm');

		}

  }

}