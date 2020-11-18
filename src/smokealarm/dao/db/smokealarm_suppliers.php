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

$dbc = \sys::dbCheck( 'smokealarm_suppliers');

$dbc->defineField( 'name', 'varchar');
$dbc->defineField( 'contact', 'varchar');
$dbc->defineField( 'phone', 'varchar');
$dbc->defineField( 'email', 'varchar');

$dbc->check();
