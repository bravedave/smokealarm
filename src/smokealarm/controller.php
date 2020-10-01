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
use ParseCsv;
use strings;
use sys;

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
    elseif ( 'delete-smokealarm-type' == $action) {
      if ( $id = (int)$this->getPost('id')) {
        $dao = new dao\smokealarm_types;
        $dao->delete( $id);

        Json::ack( $action);

      } else { Json::nak( $action); }

		}
    elseif ( 'save-smokealarm' == $action) {
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
			$this->load('edit');

		}

  }

  function importcsv() {
    if ( $csvFile = config::smokealarm_import_csv()) {
      $csv = new ParseCsv\Csv( $csvFile);
      // sys::dump($csv->data, null, false);

      $cmsIDs = [];

      foreach ($csv->data as $data) {
        # code...
        if ( !\in_array( $data['CMS ID'], $cmsIDs)) {
          $cmsIDs[] = $id = $data['CMS ID'];

          $dao = new dao\properties;
          if ( $dto = $dao->getByID( $data['CMS ID'])) {
            $a = [
              'smokealarms_required' => (int)$data['Sum total of alarms required'],

            ];

            $dao->UpdateByID( $a, $dto->id);

          }
          else {
            $a = [
              'address_street' => sprintf( '%s %s', $data['No'], $data['Street']),
              'smokealarms_required' => (int)$data['Sum total of alarms required'],

            ];

            $id = $dao->Insert( $a);

          }

        }

        $status = 'non compliant';
        if ( \in_array( $data['Status'], ['Existing', 'Replacement'])) {
          $status = 'compliant';

        }

        $a = [
          'properties_id' => $id,
          'type' => $data['Type'],
          'location' => $data['Location'],
          'make' => $data['Make'],
          'model' => $data['Type and Power Source'],
          'expiry' => strings::BRITISHDateAsANSI( $data['Date of expiry']),
          'status' =>$status,
          'power' => $data['Power Source'],

        ];

        $dao = new dao\smokealarm;
        $dao->Insert( $a);

      }

      \rename( $csvFile, \preg_replace( '@csv$@', 'bak', $csvFile));

    }

  }

}