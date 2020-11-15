<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
**/	?>

<nav class="navbar navbar-expand-md navbar-dark bg-dark" role="navigation" >
	<div class="container-fluid">
    <div class="navbar-brand" ><?= $this->data->title	?></div>

    <ul class="ml-auto navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="<?= strings::url() ?>">
          <?= dvc\icon::get( dvc\icon::house ) ?>

        </a>

      </li>

      <li class="nav-item pt-1">
        <a class="nav-link pb-0" href="<?= strings::url('smokealarm') ?>">Smoke Alarm</a>

      </li>

      <li class="nav-item pt-1">
        <a class="nav-link pb-0" href="<?= strings::url('photolog') ?>">PhotoLog</a>

      </li>

      <li class="nav-item pt-1 dropdown">
        <a class="nav-link pb-0 dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Admin

        </a>

        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="<?= strings::url('properties') ?>">Properties</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="<?= strings::url('beds') ?>">Beds</a>
          <a class="dropdown-item" href="<?= strings::url('baths') ?>">Baths</a>
          <a class="dropdown-item" href="<?= strings::url('property_type') ?>">Property Type</a>
          <a class="dropdown-item" href="<?= strings::url('postcodes') ?>">Postcodes</a>

        </div>

      </li>

      <li class="nav-item">
        <a class="nav-link" href="https://github.com/bravedave/smokealarm">
          <?= dvc\icon::get( dvc\icon::github ) ?>

        </a>

      </li>

    </ul>

  </div>

</nav>
