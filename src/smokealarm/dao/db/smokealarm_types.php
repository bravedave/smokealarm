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

$dbc = \sys::dbCheck( 'smokealarm_types');

$dbc->defineField( 'type', 'varchar');

$dbc->check();
