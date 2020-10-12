<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/  ?>

<ul class="nav flex-column">
  <li class="h5"><a href="<?= strings::url( $this->route) ?>">Admin</a></li>
  <!-- li class="nav-item"><a class="nav-link" href="<?= strings::url( 'people') ?>">People</a></li -->
  <li class="nav-item"><a class="nav-link" href="<?= strings::url( 'properties') ?>">Property</a></li>
  <li class="nav-item"><a class="nav-link" href="<?= strings::url( 'postcodes') ?>">Postcodes</a></li>
  <li class="nav-item"><a class="nav-link" href="<?= strings::url( 'beds') ?>">Beds</a></li>
  <li class="nav-item"><a class="nav-link" href="<?= strings::url( 'baths') ?>">Baths</a></li>
  <li class="nav-item"><a class="nav-link" href="<?= strings::url( 'property_type') ?>">Property type</a></li>
  <!-- li class="nav-item"><a class="nav-link" href="<?= strings::url( 'users') ?>">Users</a></li -->
  <li class="nav-item"><a class="nav-link" href="<?= strings::url() ?>"><i class="fa fa-fw fa-reply" style="margin-left: -1.1rem"></i>back</a></li>

</ul>
