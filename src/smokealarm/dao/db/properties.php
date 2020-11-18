<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace dao;

$dbc = \sys::dbCheck( 'properties');

$dbc->defineField( 'smokealarms_tags', 'text');
$dbc->defineField( 'smokealarms_required', 'int');
$dbc->defineField( 'smokealarms_power', 'varchar');
$dbc->defineField( 'smokealarms_2022_compliant', 'varchar');
$dbc->defineField( 'smokealarms_company', 'varchar');
$dbc->defineField( 'smokealarms_company_id', 'bigint');
$dbc->defineField( 'smokealarms_last_inspection', 'date');

$dbc->check();
