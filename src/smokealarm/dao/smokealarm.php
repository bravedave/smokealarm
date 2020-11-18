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
			p.smokealarms_2022_compliant
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
			p.smokealarms_2022_compliant
		FROM `smokealarm` sa
			LEFT JOIN properties p on p.id = sa.properties_id';

	public function getOrderedByStreet( bool $excludeInactive = false) : ?object {

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
				p.smokealarms_last_inspection,
				p.smokealarms_tags,
				people.name people_name
			FROM `smokealarm` sa
				LEFT JOIN properties p on p.id = sa.properties_id
				LEFT JOIN people on p.people_id = people.id';

		$conditions = [];
		if ( $co = (int)currentUser::restriction( 'smokealarm-company')) {
			$conditions[] = sprintf( 'p.smokealarms_company_id = %d', $co);

		}

		$activeProperties = [];
    if ( $excludeInactive && \class_exists('dao\console_properties')) {
      $_cp_dao = new \dao\console_properties;
      if ( $_cp_res = $_cp_dao->getActive('properties_id')) {
        $activeProperties = array_map( function( $dto) {
          return $dto->properties_id;

        }, $_cp_res->dtoSet());

        if ( $activeProperties) {
          $conditions[] = sprintf( 'sa.`properties_id` IN (%s)', implode( ',', $activeProperties));
          // \sys::logSQL( sprintf('<%s> %s', $_sql, __METHOD__));
          // \sys::logger( sprintf('<%s> %s', implode( ',', $a), __METHOD__));

        }

      }

		}

		if ( $conditions) {
			$_sql .= sprintf( ' WHERE %s', implode( ' AND ', $conditions));

		}

		$this->Q( 'DROP TABLE IF EXISTS tmp');
		$this->Q( sprintf( 'CREATE TEMPORARY TABLE tmp AS %s', $_sql));

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

      // \sys::logger( sprintf('<%s> %s', implode( ',', $activeProperties), __METHOD__));
      // \sys::logger( sprintf('<%s> %s', count( $activeProperties), __METHOD__));

      if ( $activeProperties) {
        $_sql = sprintf( 'INSERT INTO tmp(
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
            people.name people_name
            FROM properties p
              LEFT JOIN people on p.people_id = people.id
            WHERE p.id IN (%s)', implode( ',', $activeProperties));

          // \sys::logSQL( sprintf('<%s> %s', $_sql, __METHOD__));

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

		return $this->Result( 'SELECT * FROM tmp ORDER BY street_index');

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
