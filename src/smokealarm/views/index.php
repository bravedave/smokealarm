<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace smokealarm;

use currentUser;
use strings;  ?>

<style>
#<?= $_uid = strings::rand() ?> .nav-link > .bi:first-child { margin-left: -1rem; width: 1rem; }
</style>
<ul class="nav flex-column" id="<?= $_uid ?>">
  <li class="h5"><a href="<?= strings::url( 'smokealarm') ?>"><?= config::label ?></a></li>
  <li class="nav-item"><a class="nav-link" href="<?= strings::url( 'smokealarmlocations') ?>">Locations</a></li>
  <li class="nav-item"><a class="nav-link" href="<?= strings::url( 'smokealarmsuppliers') ?>">Suppliers</a></li>
  <?php
  if ( isset( $this->data->showimport) &&$this->data->showimport) { ?>
    <li class="nav-item pl-2"><a class="nav-link small" href="#" id="<?= $_uid = strings::rand() ?>"><em>Extract Suppliers from Dataset</em></a></li>
    <script>
    ( _ => $(document).ready( () => {
      $('#<?= $_uid ?>').on( 'click', function( e) {
        e.stopPropagation();e.preventDefault();

        _.post({
          url : _.url('<?= $this->route ?>'),
          data : {
            action : 'suppliers-extract'

          },

        }).then( d => {
          if ( 'ack' == d.response) {
            window.location.reload();

          }
          else {
            _.growl( d);

          }

        });

      })

    }))( _brayworth_);
    </script>

  <?php
  } ?>

  <?php if ( isset($this->data->na) && $this->data->na) { ?>
    <li class="nav-item"><a class="nav-link" href="<?= strings::url( $this->route . '?na=yes') ?>"><i class="bi bi-check"></i>Include Inactive</a></li>
  <?php }
    else {  ?>
    <li class="nav-item"><a class="nav-link" href="<?= strings::url( $this->route . '?na=yes') ?>">Include Inactive</a></li>
  <?php } // if ( $this->data->na) ?>

  <?php if ( !(int)currentUser::restriction( 'smokealarm-company')) { ?>
  <li class="nav-item"><a class="nav-link" href="<?= strings::url( 'smokealarm/propertyalarms') ?>">All Alarms</a></li>
  <?php } // if ( !(int)currentUser::restriction( 'smokealarm-company')) ?>

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
            action : _me.prop('checked') ? 'set-option-exclude-inactive' : 'set-option-exclude-inactive-undo'

          },

        }).then( d => window.location.reload());

      });

    }) (_brayworth_);
    </script>

  <?php } ?>

</ul>
