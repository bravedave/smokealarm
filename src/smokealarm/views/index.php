<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
 * styleguide : https://codeguide.co/
*/

namespace smokealarm;

use strings;
?>

<ul class="nav flex-column">
  <li class="h5"><a href="<?= strings::url( 'smokealarm') ?>"><?= config::label ?></a></li>
  <li class="nav-item">
    <a class="nav-link" href="<?= strings::url( 'smokealarmtypes') ?>"><?= config::label ?> Types</a>

  </li>

</ul>
