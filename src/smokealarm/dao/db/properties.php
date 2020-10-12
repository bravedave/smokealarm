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

$dbc->defineField( 'people_id', 'bigint');
$dbc->defineField( 'smokealarms_required', 'int');
$dbc->defineField( 'smokealarms_power', 'varchar');
$dbc->defineField( 'smokealarms_2022_compliant', 'varchar');

$dbc->check();
