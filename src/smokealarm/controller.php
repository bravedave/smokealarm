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
use strings;
use sys;

class controller extends \Controller {
  protected $viewPath = __DIR__ . '/views/';

  protected function _index() {
    $dao = new dao\smokealarm;
    $this->data = (object)[
      'dtoSet' => $dao->dtoSet( $dao->getOrderedByStreet())

    ];

    // sys::dump( $this->data->dtoSet);

    $this->title = config::label;
    $this->render([
      'primary' => 'report',
      'secondary' => [
        'index'

      ],
      'data' => (object)[
        'searchFocus' => false,
        'pageUrl' => strings::url( $this->route)

      ]

    ]);

  }

	protected function before() {
		config::smokealarm_checkdatabase();
		parent::before();

  }

	protected function posthandler() {
    $action = $this->getPost('action');

    if ( 'delete-smokealarm' == $action) {
      if ( $id = (int)$this->getPost('id')) {
        $dao = new dao\smokealarm;
        $dao->delete( $id);

        Json::ack( $action);

      } else { Json::nak( $action); }

		}
    elseif ( 'delete-smokealarm-location' == $action) {
      if ( $id = (int)$this->getPost('id')) {
        $dao = new dao\smokealarm_locations;
        $dao->delete( $id);

        Json::ack( $action);

      } else { Json::nak( $action); }

		}
    elseif ( 'get-property-by-id' == $action) {
      if ( $id = (int)$this->getPost('id')) {
        $dao = new dao\properties;
        if ( $dto = $dao->getByID( $id)) {

          $dao = new dao\smokealarm;
          $stat = $dao->getCompliantCountForProperty( $dto->id);

          Json::ack( $action)
            ->add( 'dto', $dto)
            ->add( 'compliant', $stat->compliant)
            ;

        } else { Json::nak( $action); }

      } else { Json::nak( $action); }

		}
    elseif ( 'save-properties' == $action) {
      if ( $id = (int)$this->getPost('id')) {
        $a = [
          'smokealarms_required' => $this->getPost('smokealarms_required'),
          'smokealarms_2022_compliant' => $this->getPost('smokealarms_2022_compliant'),
          'smokealarms_power' => $this->getPost('smokealarms_power')

        ];

        $dao = new dao\properties;
        $dao->UpdateByID( $a, $id);
        Json::ack( $action);

      } else { Json::nak( $action); }

		}
    elseif ( 'save-smokealarm' == $action) {
      $a = [
        'expiry' => $this->getPost('expiry'),
        'location' => $this->getPost('location'),
        'make' => $this->getPost('make'),
        'model' => $this->getPost('model'),
        'properties_id' => $this->getPost('properties_id'),
        'status' => $this->getPost('status'),

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
    elseif ( 'save-smokealarm-location' == $action) {
      $a = [
        'location' => $this->getPost('location'),

      ];

      $dao = new dao\smokealarm_locations;
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

	function edit( $id = 0, $mode = '') {
		$this->data = (object)[
			'title' => $this->title = 'Add Smoke Alarm',
			'dto' => new dao\dto\smokealarm

		];

		if ( $id = (int)$id) {
			$dao = new dao\smokealarm;
			if ( $dto = $dao->getByID( $id)) {

        if ( 'copy' == $mode) {
          $dto->id = 0;

        }
        else {
          $this->data->title = $this->title = 'Edit Smoke Alarm';

        }

				$this->data->dto = $dto;
				$this->load('edit');

			}
			else {
				$this->load('not-found');

			}

		}
		else {
      if ( $pid = (int)$this->getParam('pid')) {
        $dao = new dao\properties;
        if ( $dto = $dao->getByID( $pid)) {
          $this->data->dto->properties_id = $dto->id;
          $this->data->dto->address_street = $dto->address_street;

        }

      }
			$this->load('edit');

		}

  }

	function editproperty( $id = 0) {
    $this->title = 'Edit Property';

    if ( $id = (int)$id) {

      $dao = new dao\properties;
      if ( $dto = $dao->getByID( $id)) {

        $this->data = (object)[
          'dto' => $dto

        ];

				$this->load('edit-property');

			}
			else {
				$this->load('not-found-property');

			}

		}
    else {
      $this->load('not-found-property');

    }

  }

  function propertyalarms( $id = 0) {

    if ( $id = (int)$id) {
      $dao = new dao\properties;
      if ( $dto = $dao->getByID( $id)) {
        $dao = new dao\smokealarm;
        $this->data = (object)[
          'dtoSet' => $dao->dtoSet( $dao->getForProperty( $id)),
          'property' => $dto

        ];

        $this->title = config::label;
        $this->load( 'report-property');

      }
      else {
				$this->load('not-found-property-alert');

      }

    }
    else {
      $dao = new dao\smokealarm;
      $this->data = (object)[
        'dtoSet' => $dao->dtoSet( $dao->getAll())

      ];

      $this->title = config::label;
      $this->render([
        'primary' => 'report-all',
        'secondary' => [
          'index'

        ],
        'data' => (object)[
          'searchFocus' => false,
          'pageUrl' => strings::url( $this->route)

        ]

      ]);

    }

  }

}