<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace smokealarm\dao;

use currentUser;
use dao\_dao;
use strings;
class smokealarm extends _dao {
	protected $_db_name = 'smokealarm';
	protected $template = __NAMESPACE__ . '\dto\smokealarm';
	protected $_sql_getByID =
		'SELECT
			sa.*,
			p.address_street,
			p.address_suburb,
			p.address_state,
			p.address_postcode,
			p.smokealarms_required,
			p.smokealarms_power,
			p.smokealarms_2022_compliant,
			p.smokealarms_na
		FROM
			%s sa
			LEFT JOIN properties p on p.id = sa.properties_id
		WHERE
			sa.id = %d';

	protected $_sql_getAll =
		'SELECT
			sa.*,
			p.address_street,
			p.street_index,
			p.address_suburb,
			p.address_state,
			p.address_postcode,
			p.smokealarms_required,
			p.smokealarms_power,
			p.smokealarms_2022_compliant,
			p.smokealarms_na
		FROM `smokealarm` sa
			LEFT JOIN properties p on p.id = sa.properties_id';

	public function getOrderedByStreet( bool $excludeInactive = false,  bool $IncludeNotApplicable = false) : ?object {
		$debug = false;
		$debug = true;

		$_sql =
			'SELECT
				sa.*,
				p.address_street,
				p.people_id,
				p.street_index,
				p.address_suburb,
				p.address_state,
				p.address_postcode,
				p.smokealarms_required,
				p.smokealarms_power,
				p.smokealarms_2022_compliant,
				p.smokealarms_company,
				p.smokealarms_company_id,
				p.smokealarms_annual,
				p.smokealarms_last_inspection,
				p.smokealarms_tags,
				p.smokealarms_na,
				p.smokealarms_upgrade_preference,
				p.smokealarms_workorder_sent,
				p.smokealarms_workorder_schedule,
				people.name people_name
			FROM `smokealarm` sa
				LEFT JOIN properties p on p.id = sa.properties_id
				LEFT JOIN people on p.people_id = people.id';

		$conditions = [];
		if ( !$IncludeNotApplicable) {
			$conditions[] = 'p.smokealarms_na = 0';

		}

		if ( $company = (int)currentUser::restriction( 'smokealarm-company')) {
			$conditions[] = sprintf( 'p.smokealarms_company_id = %d', $company);

		}

		$activeProperties = [];
		$leaseDetails = [];
    if ( $excludeInactive && \class_exists('dao\console_properties')) {

			if ($debug) \sys::logger( sprintf( '<%s> : %s : %s', 'getting console data', \application::timer()->elapsed(), __METHOD__));

      $_cp_dao = new \dao\console_properties;
      if ( $_cp_res = $_cp_dao->getActive('properties_id')) {
        $activeProperties = array_map( function( $dto) {
          return $dto->properties_id;

        }, $_cp_res->dtoSet());

        if ( $activeProperties) {
          $conditions[] = sprintf( 'sa.`properties_id` IN (%s)', implode( ',', $activeProperties));

        }

			}

			if ($debug) \sys::logger( sprintf( '<%s> : %s : %s', 'getting console lease data', \application::timer()->elapsed(), __METHOD__));

			if ( $_cp_res = $_cp_dao->getActiveWithCurrentTenant()) {
        $leaseDetails = array_map( function( $dto) {
          return (object)[
						'property_id' => $dto->properties_id,
						'LeaseStop' => $dto->LeaseStop

					];

        }, $_cp_res->dtoSet());

			}

			if ($debug) \sys::logger( sprintf( '<%s> : %s : %s', 'got console lease data', \application::timer()->elapsed(), __METHOD__));

		}

		if ( $conditions) {
			$_sql .= sprintf( ' WHERE %s', implode( ' AND ', $conditions));

		}

		$this->Q( $_z = 'DROP TABLE IF EXISTS tmp');
		if ( $debug) \sys::logSQL( sprintf('<%s> %s', $_z, __METHOD__));

		$this->Q( $_z = sprintf( 'CREATE TEMPORARY TABLE tmp AS %s', $_sql));
		if ( $debug) \sys::logSQL( sprintf('<%s> %s', $_z, __METHOD__));

    if ($activeProperties) {
      $_sql = 'SELECT properties_id FROM tmp WHERE properties_id > 0';
      if ( $res = $this->Result( $_sql)) {
        $res->dtoSet( function( $dto) use (&$activeProperties) {
          if ( $i = array_search( $dto->properties_id, $activeProperties)) {
            if ( false !== $i) {
              unset( $activeProperties[$i]);

            }

          }

        });

      }
			if ( $debug) \sys::logSQL( sprintf('<%s> %s', $_sql, __METHOD__));

    }

		// if ( $activeProperties) {
		if ( $activeProperties ||!$excludeInactive) {
			/**
			 * So it's either:
			 * - console was active and have active properties .. I should be here ..
			 * or
			 * - we simple have excludeInactive off .. I should be here ..
			 * or
			 * - console was active and had no active properties .. should I be here ?
			 */


			$conditions = [];
			if ( !$IncludeNotApplicable) {
				$conditions[] = 'p.smokealarms_na = 0';

			}

			if ( $company = (int)currentUser::restriction( 'smokealarm-company')) {
				$conditions[] = sprintf( 'p.smokealarms_company_id = %d', $company);

			}

			if ($activeProperties) {
				$conditions[] = sprintf( 'p.id IN (%s)', implode( ',', $activeProperties));

			}
			else {
				$this->Q( $_z = 'CREATE TEMPORARY TABLE tmpx AS SELECT id FROM tmp');
				if ( $debug) \sys::logSQL( sprintf('<%s> %s', $_z, __METHOD__));
				$conditions[] = 'p.id NOT IN (SELECT id FROM tmpx)';

			}

			$_sql = $__sql = 'INSERT INTO tmp(
				`properties_id`,
				`address_street`,
				`people_id`,
				`street_index`,
				`address_suburb`,
				`address_state`,
				`address_postcode`,
				`smokealarms_required`,
				`smokealarms_power`,
				`smokealarms_2022_compliant`,
				`smokealarms_company`,
				`smokealarms_last_inspection`,
				`smokealarms_na`,
				`smokealarms_upgrade_preference`,
				`smokealarms_workorder_sent`,
				`smokealarms_workorder_schedule`,
				`people_name`)
				SELECT
					p.id,
					p.address_street,
					p.people_id,
					p.street_index,
					p.address_suburb,
					p.address_state,
					p.address_postcode,
					p.smokealarms_required,
					p.smokealarms_power,
					p.smokealarms_2022_compliant,
					p.smokealarms_company,
					p.smokealarms_last_inspection,
					p.smokealarms_na,
					p.smokealarms_upgrade_preference,
					p.smokealarms_workorder_sent,
					p.smokealarms_workorder_schedule,
					people.name people_name
					FROM properties p
						LEFT JOIN people on p.people_id = people.id';

			if ($activeProperties || $company) {
				/**
				 * currently this blow the machine memory
				 */
				$_sql = sprintf(
					'%s WHERE %s',
					$__sql,
					implode( ' AND ', $conditions)

				);
				if ( $debug) \sys::logSQL( sprintf('<%s> %s', $_sql, __METHOD__));

				$this->Q( $_sql);

			}

		}

		$_sql = 'SELECT
				id,
				address_street,
				street_index,
				properties_id
			FROM tmp
			WHERE street_index = "" OR street_index IS NULL';
		if ( $res = $this->Result( $_sql)) {
			$res->dtoSet( function( $dto) {
				if ( !$dto->street_index) {
					if ( $s = strings::street_index( $dto->address_street)) {

						// \sys::logger( sprintf('<%s> %s', $s, __METHOD__));
            $this->db->Update( 'tmp',
              [ 'street_index' => $s ],
              'WHERE id = ' . (int)$dto->id,
              $flushCache = false

            );

						$dao = new properties;
						$dao->UpdateByID([
							'street_index' => $s

						], $dto->properties_id);

					}

				}

				return $dto;

			});

		}

		if ( $leaseDetails) {

			if ($debug) \sys::logger( sprintf( '<%s> : %s : %s', 'updating console lease data', \application::timer()->elapsed(), __METHOD__));

			// ADD COLUMN `LeaseFirstStart` DATE,
			// ADD COLUMN `LeaseStart` DATE,
			$this->Q( 'ALTER TABLE tmp
				ADD COLUMN `uid` BIGINT AUTO_INCREMENT FIRST,
				ADD COLUMN `LeaseStop` DATE DEFAULT "0000-00-00"
				ADD PRIMARY KEY (`uid`)');

			$_sql = 'SELECT uid, properties_id FROM tmp';
			if ( $res = $this->Result( $_sql)) {
				$res->dtoSet( function( $dto) use ( $leaseDetails) {
					$key = array_search( $dto->properties_id, array_column( $leaseDetails, 'property_id'));
					if ( $key !== false) {
						if ( strtotime( $leaseDetails[$key]->LeaseStop) > 0) {
							$this->db->Update( 'tmp',
								[ 'LeaseStop' => $leaseDetails[$key]->LeaseStop ],
								'WHERE uid = ' . (int)$dto->uid,
								$flushCache = false

							);
							// \sys::logger( sprintf('<%s> %s', $leaseDetails[$key]->LeaseStop, __METHOD__));

						}

					}

				});

			}

			if ($debug) \sys::logger( sprintf( '<%s> : %s : %s', 'updated console lease data', \application::timer()->elapsed(), __METHOD__));

		}

		return $this->Result( 'SELECT * FROM tmp ORDER BY street_index LIMIT 3000');

	}

	public function getDistinctMakes() {
		$sql = 'SELECT DISTINCT `make` FROM `smokealarm` ORDER BY `make`';
		return $this->Result( $sql);

	}

	public function getDistinctModels() {
		$sql = 'SELECT DISTINCT `model` FROM `smokealarm` ORDER BY `model`';
		return $this->Result( $sql);

	}

	public function getDistinctTypes() {
		$sql = 'SELECT DISTINCT `type` FROM `smokealarm` ORDER BY `type`';
		return $this->Result( $sql);

	}

	public function getForProperty( $id) {
		$_sql = sprintf(
			'SELECT
				*
			FROM
				`smokealarm`
			WHERE
				`properties_id` = %d
			ORDER BY
				`location`',
			$id

		);

		return $this->Result( $_sql);

	}

	public function getCompliantCountForProperty( int $id) : ?object {
		$_sql = sprintf(
			'SELECT
				count(*) "i"
			FROM `smokealarm`
			WHERE
				`properties_id` = %d
				AND "compliant" = `status`',
			$id

		);

		// \sys::logSQL( sprintf('<%s> %s', $_sql, __METHOD__));

		$ret = (object)[
			'properties_id' => $id,
			'compliant' => 0

		];

		if ( $res = $this->Result( $_sql)) {
			if ( $dto = $res->dto()) {
				$ret->compliant = $dto->i;

			}

		}

		return $ret;

	}

	public function Insert( $a) {
		$a[ 'created'] = $a['updated'] = self::dbTimeStamp();
		return parent::Insert( $a);

	}

	public function searchMakes( string $term) : array {
		$sql = sprintf(
			'SELECT
				DISTINCT `make` `value`
			FROM
				`smokealarm`
			WHERE
				`make` LIKE "%%%s%%"
			ORDER BY
				`make`',
			$this->escape( $term)

		);

		if ( $res = $this->Result( $sql)) {
			return $res->dtoSet();

		}

		return [];


	}

	public function UpdateByID( $a, $id) {
		$a['updated'] = self::dbTimeStamp();
		return parent::UpdateByID( $a, $id);

  }

}
