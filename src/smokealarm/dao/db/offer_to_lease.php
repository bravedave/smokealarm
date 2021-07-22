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

$dbc = \sys::dbCheck('offer_to_lease');

$dbc->defineField('property_id', 'bigint');
$dbc->defineField('lease_start_inaugural', 'date');
$dbc->defineField('lease_start', 'date');
$dbc->defineField('lease_end', 'date');
$dbc->defineField('vacate', 'date');
$dbc->defineField('lessor_signature', 'longblob');
$dbc->defineField('lessor_signature_time', 'datetime');

$dbc->check();
