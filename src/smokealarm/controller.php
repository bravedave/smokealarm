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

use currentUser;
use green;
use Json;
use Response;
use SplFileInfo;
use strings;
use sys;

class controller extends \Controller {
  protected $viewPath = __DIR__ . '/views/';

  protected function _index() {

    $excludeInactive = false;
    if ( \class_exists('dao\console_properties')) {
      $excludeInactive = 'yes' == \currentUser::option('smokealarm-inactive-exclude');

    }

    $na = 'yes' == $this->getParam('na');
    $dao = new dao\smokealarm;
    $this->data = (object)[
      'dtoSet' => $dao->dtoSet( $dao->getOrderedByStreet( $excludeInactive, $na)),
      'na' => $na

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

  protected function page( $params) {

    if ( !isset( $params['latescripts'])) $params['latescripts'] = [];
    $params['latescripts'][] = sprintf(
      '<script type="text/javascript" src="%s"></script>',
      strings::url( $this->route . '/js')

    );

		return parent::page( $params);

	}

	protected function postHandler() {
    $action = $this->getPost('action');

    if ( 'archive-smokealarm' == $action) {
      if ( $id = (int)$this->getPost('id')) {
        $dao = new dao\smokealarm;
        if ( $dto = $dao->getByID( $id)) {
          $a = [
            'location' => $dto->location,
            'make' => $dto->make,
            'model' => $dto->model,
            'type' => $dto->type,
            'expiry' => $dto->expiry,
            'connect' => $dto->connect,
            'status' => $dto->status,
            'properties_id' => $dto->properties_id,
            'smokealarm_id' => $dto->id

          ];

          $daoA = new dao\smokealarm_archive;
          $daoA->Insert( $a);

          $dao->delete( $id);
          Json::ack( $action);


        } else { Json::nak( $action); }

      } else { Json::nak( $action); }

		}
    elseif ( 'delete-smokealarm-location' == $action) {
      if ( $id = (int)$this->getPost('id')) {
        $dao = new dao\smokealarm_locations;
        $dao->delete( $id);

        Json::ack( $action);

      } else { Json::nak( $action); }

    }
    elseif ( 'delete-smokealarm-supplier' == $action) {
      if ( $id = (int)$this->getPost('id')) {
        $dao = new dao\smokealarm_suppliers;
        $dao->delete( $id);

        Json::ack( $action);

      } else { Json::nak( $action); }

    }
    elseif ( 'document-delete-for-property' == $action) {
      $debug = false;
      // $debug = true;
      if ( $properties_id = (int)$this->getPost('properties_id')) {
        if ( $file = $this->getPost('file')) {
          \sys::logger( sprintf('<%s> %s/%s', $file, __METHOD__, $action));
          $dao = new dao\properties;
          if ( $dto = $dao->getByID( $properties_id)) {
            if ( $store = $dao->smokealarmStore( $dto)) {
              $target = implode( DIRECTORY_SEPARATOR, [ $store, $file ]);

              if ( \file_exists( $target)) unlink( $target);
              Json::ack( $action);

            } else { Json::nak( sprintf( '%s : invalid store', $action)); }

          } else { Json::nak( sprintf( '%s : property not found', $action)); }

        } else { Json::nak( sprintf( '%s : invalid file', $action)); }

      } else { Json::nak( sprintf( '%s : invalid id', $action)); }

    }
    elseif ( 'document-get-for-property' == $action) {
      $debug = false;
      // $debug = true;
      if ( $properties_id = (int)$this->getPost('properties_id')) {
        $dao = new dao\properties;
        if ( $dto = $dao->getByID( $properties_id)) {
          if ( $store = $dao->smokealarmStore( $dto)) {

            $tags = $dto->smokealarms_tags ?
              (array)\json_decode( $dto->smokealarms_tags) :
              [];

            $it = new \FilesystemIterator($store);
            $a = [];
            foreach ($it as $obj) {
              if ( 'notes.txt' == $obj->getFilename()) continue;

              $key = (string)\array_search( $obj->getFilename(), $tags);
              $a[] = (object)[
                'name' => $obj->getFilename(),
                'size' => strings::formatBytes( $obj->getSize(), 0),
                'tag' => $key

              ];

            }

            Json::ack( $action)
              ->add( 'data', $a);

          } else { Json::nak( sprintf( '%s : invalid store', $action)); }

        } else { Json::nak( sprintf( '%s : property not found', $action)); }

      } else { Json::nak( sprintf( '%s : invalid id', $action)); }

    }
    elseif ( 'document-upload' == $action) {
      $debug = false;
      // $debug = true;
      if ( $properties_id = (int)$this->getPost('properties_id')) {

        $dao = new dao\properties;
        if ( $dto = $dao->getByID( $properties_id)) {
          if ( $store = $dao->smokealarmStore( $dto)) {
            /*--- ---[uploads]--- ---*/
            if ( $debug) \sys::logger( sprintf('<%s> %s', '-- uploads --', __METHOD__));
            $good = [];
            $bad = [];
            foreach ( $_FILES as $file ) {
              if ( $debug) sys::logger( sprintf('%s : %s', $file['name'], __METHOD__));
              if ( is_uploaded_file( $file['tmp_name'] )) {
                $strType = mime_content_type ( $file['tmp_name']);
                if ( $debug) sys::logger( sprintf('%s (%s) : %s', $file['name'], $strType, __METHOD__));

                $ok = true;
                $accept = [
                  'image/png',
                  'image/x-png',
                  'image/jpeg',
                  'image/pjpeg',
                  'image/tiff',
                  'image/gif',
                  'application/pdf',

                ];

                if ( in_array( $strType, $accept)) {
                  $source = $file['tmp_name'];
                  $target = implode( DIRECTORY_SEPARATOR, [
                    $store,
                    $file['name']

                  ]);

                  if ( file_exists( $target )) unlink( $target );
                  if ( move_uploaded_file( $source, $target)) {
                    chmod( $target, 0666 );
                    $good[] = [ 'name' => $file['name'], 'result' => 'uploaded'];

                  }
                  else {
                    $bad[] = [ 'name' => $file['name'], 'result' => 'nak'];

                  }

                }
                elseif ( !$strType) {
                  sys::logger( sprintf('%s invalid file type : %s', $file['name'], __METHOD__));
                  $bad[] = [ 'name' => $file['name'], 'result' => 'invalid file type'];

                }
                else {
                  sys::logger( sprintf('%s invalid file type - %s : %s', $file['name'], $strType, __METHOD__));
                  $bad[] = [ 'name' => $file['name'], 'result' => 'invalid file type : ' . $strType];

                }

              }
              elseif ( UPLOAD_ERR_INI_SIZE == $file['error']) {
                sys::logger( sprintf('%s size exceeds ini size', $file['name'], __METHOD__));
                $bad[] = [ 'name' => $file['name'], 'result' => 'size exceeds ini size'];

              }
              else {
                sys::logger( sprintf('is not an uploaded file ? : %s : %s', $file['name'], __METHOD__));

              }

            }
            /*--- ---[/uploads]--- ---*/

            Json::ack( $action)
              ->add( 'good', $good)
              ->add( 'bad', $bad);

          } else { Json::nak( sprintf( '%s : invalid store', $action)); }

        } else { Json::nak( sprintf( '%s : property not found', $action)); }

      } else { Json::nak( sprintf( '%s : invalid id', $action)); }

    }
    elseif ( 'get-property-by-id' == $action) {
      if ( $id = (int)$this->getPost('id')) {
        $dao = new dao\properties;
        if ( $dto = $dao->getByID( $id)) {

          $dto->smokealarm_expired = $dto->smokealarm_warning = false;
          if ( ( $et = \strtotime( $dto->smokealarms_last_inspection)) > 0) {
            $etx = \strtotime( config::smokealarm_valid_time, $et);
            if ( date('Y-m-d', $etx) < date('Y-m-d')) {
              $dto->smokealarm_expired = true;

            }
            else {
              $etx = \strtotime( config::smokealarm_warn_time, $et);
              if ( date('Y-m-d', $etx) < date('Y-m-d')) {
                $dto->smokealarm_warning = true;

              }

            }

          }

          $daoS = new dao\smokealarm;
          $stat = $daoS->getCompliantCountForProperty( $dto->id);

          Json::ack( $action)
            ->add( 'dto', $dto)
            ->add( 'compliant', $stat->compliant)
            ->add( 'hasSmokeAlarmComplianceCertificate', $dao->hasSmokeAlarmComplianceCertificate( $dto) ? 'yes' : 'no')
            ;

        } else { Json::nak( $action); }

      } else { Json::nak( $action); }

    }
    elseif ( 'get-photolog-image-of-alarm' == $action) {
      if ( $location = $this->getPost('location')) {
        if ( $properties_id = (int)$this->getPost('properties_id')) {
          $dao = new dao\properties;
          if ( $property = $dao->getByID( $properties_id)) {
            $alarm = false;
            if ( class_exists( 'photolog\dao\property_photolog')) {
              $dao = new \photolog\dao\property_photolog;
              if ($photologs = $dao->getForProperty( $property->id)) {
                foreach ($photologs as $photolog) {
                  $files = $dao->getFiles( $photolog, config::$PHOTOLOG_ROUTE);
                  foreach ($files as $file) {
                    if ( $location == $file->location) {
                      $file->photolog = $photolog;
                      $alarm = $file;

                    }

                  }

                }

              }

            }

            Json::ack( $action)
              ->add( 'property', $property)
              ->add( 'alarm', $alarm);

          } else { Json::nak( $action); }

        } else { Json::nak( $action); }

      } else { Json::nak( $action); }

    }
		elseif ( 'get-tenant-of-property' == $action) {
      if ( \class_exists( 'dao\console_tenants')) {;
        if ( $properties_id = $this->getPost( 'properties_id')) {
          /*
          ( _ => {
            _.post({
              url : _.url('smokealarm'),
              data : {
                action : 'get-tenant-of-property',
                properties_id : 48283

              }

            })
            .then( d => console.log( d));

          })(_brayworth_);
          */
          $dao = new \dao\console_tenants;
          if ( $dto = $dao->getTenantOfProperty( $properties_id)) {
            \Json::ack( $action)->add( 'data', $dto);

          } else { \Json::nak( sprintf( '%s : not found', $action)); }

        } else { \Json::nak( sprintf( '%s : missing id', $action)); }

      } else { \Json::nak( sprintf( '%s : not enabled', $action)); }

    }
    elseif ( 'mark-property-na' == $action) {
      if ( $id = (int)$this->getPost('id')) {
        $dao = new dao\properties;
        if ( $dto = $dao->getByID( $id)) {
          $v = (int)$this->getPost('value');

          $a = [ 'smokealarms_na' => $v ];

          $dao->UpdateByID( $a, $id);
          Json::ack( $action)
            ->add( 'na', $v ? 'yes' : 'no');

        } else { Json::nak( $action); }

      } else { Json::nak( $action); }

    }
    elseif ( 'save-notes' == $action) {
      if ( $id = (int)$this->getPost('id')) {
        $dao = new dao\properties;
        if ( $dto = $dao->getByID( $id)) {
          if ( $path = $dao->smokealarmNotesPath( $dto)) {
            $text = (string)$this->getPost( 'text');
            \file_put_contents( $path, $text);
            Json::ack( $action);

          } else { Json::nak( $action); }

        } else { Json::nak( $action); }

      } else { Json::nak( $action); }

		}
    elseif ( 'save-properties' == $action) {
      if ( $id = (int)$this->getPost('id')) {
        $a = [
          'smokealarms_required' => $this->getPost('smokealarms_required'),
          'smokealarms_2022_compliant' => $this->getPost('smokealarms_2022_compliant'),
          'smokealarms_power' => $this->getPost('smokealarms_power'),
          'smokealarms_company_id' => $this->getPost('smokealarms_company_id'),
          'smokealarms_company' => $this->getPost('smokealarms_company'),
          'smokealarms_last_inspection' => $this->getPost('smokealarms_last_inspection'),
          'smokealarms_annual' => $this->getPost('smokealarms_annual'),

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
        'type' => $this->getPost('type'),
        'model' => $this->getPost('model'),
        'properties_id' => $this->getPost('properties_id'),
        'status' => $this->getPost('status'),
        'connect' => $this->getPost('connect'),

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
    elseif ( 'save-smokealarm-supplier' == $action) {
      $a = [
        'name' => $this->getPost('name'),
        'contact' => $this->getPost('contact'),
        'phone' => $this->getPost('phone'),
        'email' => $this->getPost('email'),

      ];

      $dao = new dao\smokealarm_suppliers;
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
        $restriction = '';
        if ( $co = (int)currentUser::restriction( 'smokealarm-company')) {
          $restriction = sprintf( 'smokealarms_company_id = %d', $co);

        }

				Json::ack( $action)
					->add( 'term', $term)
					->add( 'data', green\search::properties( $term, $restriction));

			} else { Json::nak( $action); }

		}
    elseif ( 'search-makes' == $action) {
			if ( $term = $this->getPost('term')) {
        $dao = new dao\smokealarm;
        $makes = $dao->searchMakes( $term);

				Json::ack( $action)
					->add( 'term', $term)
					->add( 'data', $makes);

			} else { Json::nak( $action); }

    }
    elseif ( 'search-suppliers' == $action) {
			if ( $term = $this->getPost('term')) {
        $dao = new dao\smokealarm_suppliers;
        $suppliers = $dao->search( $term);

        \sys::logger( sprintf('<%s> %s/%s', json_encode($suppliers), __METHOD__, $action));

				Json::ack( $action)
					->add( 'term', $term)
					->add( 'data', $suppliers);

			} else { Json::nak( $action); }

    }
    elseif ( 'set-option-exclude-inactive' == $action) {
      \currentUser::option('smokealarm-inactive-exclude', 'yes');
      Json::ack( $action);

    }
    elseif ( 'set-option-exclude-inactive-undo' == $action) {
      \currentUser::option('smokealarm-inactive-exclude', '');
      Json::ack( $action);

    }
    elseif ( 'suppliers-extract' == $action) {
      $dao = new dao\smokealarm_suppliers;
      $dao->extractDataSet();
      Json::ack( $action);

    }
    elseif ( 'tag-set-for-property' == $action) {
      if ( $file = $this->getPost( 'file')) {
        if ( $properties_id = (int)$this->getPost('properties_id')) {

          $dao = new dao\properties;
          if ( $dto = $dao->getByID( $properties_id)) {
            $tags = (array)\json_decode( $dto->smokealarms_tags);
            foreach ($tags as $k => $v) {
              if ( $file == $v) unset( $tags[$k]);

            }

            if ( $tag = $this->getPost( 'tag')) {
              $tags[$tag] = $file;

            }

            $dao->UpdateByID(
              ['smokealarms_tags' => json_encode( $tags)],
              $dto->id

            );

            Json::ack( $action);

          } else { Json::nak( sprintf( '%s : property not found', $action)); }

        } else { Json::nak( sprintf( '%s : invalid id', $action)); }

      } else { Json::nak( $action); }

		}
    elseif ( 'tags-get-available' == $action) {
      Json::ack( $action)
        ->add( 'tags', config::smokealarm_tags);

		}
		else {
			parent::postHandler();

		}

  }

  public function documentView( $id = 0) {
		if ( $id = (int)$id) {
			$dao = new dao\properties;
			if ( $dto = $dao->getByID( $id)) {
        if ( $document = $this->getParam( 'd')) {
          if ( $store = $dao->smokealarmStore( $dto)) {
            $target = implode( DIRECTORY_SEPARATOR, [ $store, $document ]);

            if ( \file_exists( $target)) {
              \sys::serve( $target);

            } else { $this->render(['content' => 'not-found']); }

          } else { $this->render(['content' => 'not-found']); }

        } else { $this->render(['content' => 'not-found']); }

      } else { $this->render(['content' => 'not-found']); }

    } else { $this->render(['content' => 'not-found']); }

  }

  public function edit( $id = 0, $mode = '') {
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

  public function js( $lib = '') {
    $s = [];
    $r = [];

    $s[] = '@{{route}}@';
    $r[] = strings::url( $this->route);

    $js = \file_get_contents( __DIR__ . '/js/custom.js');
    $js = preg_replace( $s, $r, $js);

    Response::javascript_headers();
    print $js;

  }

	public function editproperty( $id = 0) {
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

  public function propertyalarms( $id = 0) {

    if ( $id = (int)$id) {
      $daoP = new dao\properties;
      if ( $dto = $daoP->getByID( $id)) {

        $notes = $daoP->smokealarmNotes( $dto);

        $dao = new dao\smokealarm;
        $this->data = (object)[
          'dtoSet' => $dao->dtoSet( $dao->getForProperty( $id)),
          'property' => $dto,
          'notes' => $notes,
          'certificate' => $daoP->hasSmokeAlarmComplianceCertificate( $dto),

        ];

        if ( $this->data->certificate) {
          $certInfo = new \SplFileInfo( $daoP->smokeAlarmComplianceCertificatePath( $dto));
          $this->data->certificate = $certInfo->getFilename();

        }

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
