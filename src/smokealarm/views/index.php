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
  #<?= $_uid = strings::rand() ?>.nav-link>.bi:first-child {
    margin-left: -1rem;
    width: 1rem;
  }
</style>
<ul class="nav flex-column" id="<?= $_uid ?>">
  <li class="h5"><a href="<?= strings::url('smokealarm') ?>"><?= config::label ?></a></li>
  <li class="nav-item"><a class="nav-link" href="<?= strings::url('smokealarmlocations') ?>">Locations</a></li>
  <li class="nav-item"><a class="nav-link" href="<?= strings::url('smokealarmsuppliers') ?>">Suppliers</a></li>
  <?php
  if (isset($this->data->showimport) && $this->data->showimport) { ?>
    <li class="nav-item pl-2"><a class="nav-link small" href="#" id="<?= $_uid = strings::rand() ?>"><em>Extract Suppliers from Dataset</em></a></li>
    <script>
      (_ => $(document).ready(() => {
        $('#<?= $_uid ?>').on('click', function(e) {
          e.stopPropagation();
          e.preventDefault();

          _.post({
            url: _.url('<?= $this->route ?>'),
            data: {
              action: 'suppliers-extract'

            },

          }).then(d => {
            if ('ack' == d.response) {
              window.location.reload();

            } else {
              _.growl(d);

            }

          });

        })

      }))(_brayworth_);
    </script>

  <?php
  } ?>

  <?php if (isset($this->data->na) && $this->data->na) { ?>
    <li class="nav-item"><a class="nav-link" href="<?= strings::url($this->route . '?na=yes') ?>"><i class="bi bi-check"></i>Include N/A</a></li>
  <?php } else {  ?>
    <li class="nav-item"><a class="nav-link" href="<?= strings::url($this->route . '?na=yes') ?>">Include N/A</a></li>
  <?php } // if ( $this->data->na)
  ?>

  <?php if (!(int)currentUser::restriction('smokealarm-company')) { ?>
    <li class="nav-item"><a class="nav-link" href="<?= strings::url('smokealarm/propertyalarms') ?>">All Alarms</a></li>
  <?php } // if ( !(int)currentUser::restriction( 'smokealarm-company'))
  ?>

</ul>


<?php if (!(int)currentUser::restriction('smokealarm-company')) { ?>
  <div class="mt-4" id="<?= $_uid = strings::rand() ?>"></div>
  <script>
    (_ => $(document).on('smokealarm-stats', (e, stats) => {
      let row = caption => {
        return $('<div class="form-row mb-2"></div>')
          .append(
            $('<div class="col-4 text-truncate"></div>')
            .html(caption)
          )
          .appendTo('#<?= $_uid ?>');

      };

      row('Statistics');

      $('<div class="col"></div>')
        .html(stats.properties)
        .appendTo(row('Properties'));

      $('<div class="col"></div>')
        .html(stats.compliant)
        .appendTo(row('Compliant'));

      $('<div class="col"></div>')
        .html(stats.notcompliant)
        .appendTo(row('Not compliant'));

      // console.log(stats);
    }))(_brayworth_);
  </script>
<?php } ?>
