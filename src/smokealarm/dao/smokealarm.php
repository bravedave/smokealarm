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

use dao\_dao;
use db;
use PropertyUtility;

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

	public function getOrderedByStreet() : ?object {

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
				people.name people_name
			FROM `smokealarm` sa
				LEFT JOIN properties p on p.id = sa.properties_id
				LEFT JOIN people on p.people_id = people.id';

		$this->Q( 'DROP TABLE IF EXISTS tmp');
		$this->Q( sprintf( 'CREATE TEMPORARY TABLE tmp AS %s', $_sql));

		$sql = 'SELECT
				id,
				address_street,
				street_index,
				properties_id
			FROM tmp
			WHERE street_index = "" OR street_index IS NULL';
		if ( $res = $this->Result( $sql)) {
			$res->dtoSet( function( $dto) {
				if ( !$dto->street_index) {
					if ( $s = PropertyUtility::street_index( $dto->address_street)) {

						// \sys::logger( sprintf('<%s> %s', $s, __METHOD__));
						$this->db->Update( 'tmp', [ 'street_index' => $s ], 'WHERE id = ' . (int)$dto->id);

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
				AND "compliant" == `status`',
			$id

		);

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
		$a[ 'created'] = $a['updated'] = db::dbTimeStamp();
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
		$a['updated'] = db::dbTimeStamp();
		return parent::UpdateByID( $a, $id);

  }

}
