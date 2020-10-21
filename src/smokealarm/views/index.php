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
    <a class="nav-link" href="<?= strings::url( 'smokealarmlocations') ?>">Locations</a>

  </li>

  <li class="nav-item">
    <a class="nav-link" href="<?= strings::url( 'smokealarm/propertyalarms') ?>">All Alarms</a>

  </li>

  <?php if ( \class_exists('dao\console_properties')) { ?>
    <li class="nav-item">
      <div class="form-check nav-link">
        <input type="checkbox" class="form-check-input" name="ExcludeInactiveProperties"
          <?php if ( 'yes' == \currentUser::option('smokealarm-inactive-exclude')) print 'checked' ?>
          id="<?= $uid = strings::rand() ?>">

        <label class="form-check-label" for="<?= $uid ?>">
          Exclude Inactive Properties

        </label>

      </div>

    </li>

    <script>
    ( _ => {
      $('#<?= $uid ?>').on( 'change', function( e) {
        let _me = $(this);

        _.post({
          url : _.url('<?= $this->route ?>'),
          data : {
            action : _me.prop('checked') ? 'set-option-exclude-inactive-undo' : 'set-option-exclude-inactive'

          },

        }).then( d => window.location.reload());

      });

    }) (_brayworth_);
    </script>

  <?php } ?>

</ul>
