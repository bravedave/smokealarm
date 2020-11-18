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

class smokealarm_suppliers extends _dao {
	protected $_db_name = 'smokealarm_suppliers';
	protected $template = __NAMESPACE__ . '\dto\smokealarm_suppliers';

	function extractDataSet() {
		if ( $res = $this->Result( 'SELECT DISTINCT `smokealarms_company` FROM `properties` WHERE "" != `smokealarms_company`')) {
			$dao = new properties;
			$res->dtoSet( function( $dto) use ($dao) {
				$id = $this->Insert([
					'name' => $dto->smokealarms_company,
					'contact' => $dto->smokealarms_company,

				]);

				$dao->Update(
					['smokealarms_company_id' => $id],
					sprintf( 'WHERE smokealarms_company = "%s"', $dao->escape( $dto->smokealarms_company)),
					$flushCache = false

				);

				\sys::logger( sprintf('<%s> %s', $dto->smokealarms_company, __METHOD__));

			});

			$this->db->flushCache();

		}

	}

	public function getAll( $fields = '*', $order = '') {
		if ( $co = (int)currentUser::restriction( 'smokealarm-company')) {
			$order = sprintf( ' WHERE id = %d', $co);

		}

		return parent::getAll( $fields, $order);

	}

	function getCount() : int {
		if ( $res = $this->Result( 'SELECT count(*) as `count` FROM `smokealarm_suppliers`')) {
			if ( $dto = $res->dto()) {
				return $dto->count;


			}

		}

		return 0;

	}

	function search( string $term) : array {
		$sql = sprintf(
			'SELECT
				`id`,
				`name`,
				`name` `value`
			FROM
				`smokealarm_suppliers`
			WHERE
				`name` LIKE "%s%%"',
			$this->escape( $term)

		);

		if ( $res = $this->Result( $sql)) {
			return $res->dtoSet();

		}

		return [];

	}

}
