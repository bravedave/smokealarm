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

$dbc = \sys::dbCheck( 'smokealarm');

$dbc->defineField( 'location', 'varchar');
$dbc->defineField( 'make', 'varchar');
$dbc->defineField( 'model', 'varchar');
$dbc->defineField( 'type', 'varchar');
$dbc->defineField( 'expiry', 'date');
$dbc->defineField( 'status', 'varchar');
$dbc->defineField( 'properties_id', 'bigint');
$dbc->defineField( 'created', 'datetime');
$dbc->defineField( 'updated', 'datetime');

$dbc->check();
