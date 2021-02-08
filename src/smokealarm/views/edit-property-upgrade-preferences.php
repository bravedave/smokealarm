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
use strings;

$dto = $this->data->dto; ?>

<form id="<?= $_form = strings::rand() ?>" autocomplete="off">
  <input type="hidden" name="action" value="save-properties-upgrade-preferences">
  <input type="hidden" name="id" value="<?= $dto->id ?>">

  <div class="modal fade" tabindex="-1" role="dialog" id="<?= $_modal = strings::rand() ?>" aria-labelledby="<?= $_modal ?>Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header bg-secondary text-white py-2">
          <h5 class="modal-title" id="<?= $_modal ?>Label"><?php
            $addr = [$dto->address_street];
            if ($dto->address_suburb) $addr[] = $dto->address_suburb;
            if ($dto->address_postcode) $addr[] = $dto->address_postcode;

            print implode( ' ', $addr);

            ?></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="row">
            <div class="offset-2 col-8">
              <label class="mb-2">Upgrade Preference</label>
              <div class="form-check">
                <input type="radio" class="form-check-input" name="smokealarms_upgrade_preference"
                  value="Proceed 2022"
                  <?php if ( 'Proceed 2022' == $dto->smokealarms_upgrade_preference) print 'checked'; ?>
                  id="<?= $_uid = strings::rand() ?>">

                <label class="form-check-label" for="<?= $_uid ?>">Proceed 2022</label>

              </div>

              <div class="form-check">
                <input type="radio" class="form-check-input" name="smokealarms_upgrade_preference"
                  value="Split"
                  <?php if ( 'Split' == $dto->smokealarms_upgrade_preference) print 'checked'; ?>
                  id="<?= $_uid = strings::rand() ?>">

                <label class="form-check-label" for="<?= $_uid ?>">Split</label>

              </div>

              <div class="form-check">
                <input type="radio" class="form-check-input" name="smokealarms_upgrade_preference"
                  value="Wait"
                  <?php if ( 'Wait' == $dto->smokealarms_upgrade_preference) print 'checked'; ?>
                  id="<?= $_uid = strings::rand() ?>">

                <label class="form-check-label" for="<?= $_uid ?>">Wait</label>

              </div>

              <div class="form-check">
                <input type="radio" class="form-check-input" name="smokealarms_upgrade_preference"
                  value="EO"
                  <?php if ( 'EO' == $dto->smokealarms_upgrade_preference) print 'checked'; ?>
                  id="<?= $_uid = strings::rand() ?>">

                <label class="form-check-label" for="<?= $_uid ?>">EO</label>

              </div>

              <div class="form-check">
                <input type="radio" class="form-check-input" name="smokealarms_upgrade_preference"
                  value="Quote"
                  <?php if ( 'Quote' == $dto->smokealarms_upgrade_preference) print 'checked'; ?>
                  id="<?= $_uid = strings::rand() ?>">

                <label class="form-check-label" for="<?= $_uid ?>">Quote</label>

              </div>

              <div class="form-check">
                <input type="radio" class="form-check-input" name="smokealarms_upgrade_preference"
                  value="Owner"
                  <?php if ( 'Owner' == $dto->smokealarms_upgrade_preference) print 'checked'; ?>
                  id="<?= $_uid = strings::rand() ?>">

                <label class="form-check-label" for="<?= $_uid ?>">Owner to Upgrade</label>

              </div>

              <div class="form-check">
                <input type="radio" class="form-check-input" name="smokealarms_upgrade_preference"
                  value="wait-tenant"
                  <?php if ( 'wait-tenant' == $dto->smokealarms_upgrade_preference) print 'checked'; ?>
                  id="<?= $_uid = strings::rand() ?>">

                <label class="form-check-label" for="<?= $_uid ?>">Await Tenant</label>

              </div>

              <div class="form-check">
                <input type="radio" class="form-check-input" name="smokealarms_upgrade_preference"
                  value="wait-lease-exp"
                  <?php if ( 'wait-lease-exp' == $dto->smokealarms_upgrade_preference) print 'checked'; ?>
                  id="<?= $_uid = strings::rand() ?>">

                <label class="form-check-label" for="<?= $_uid ?>">Await Lease Exp</label>

              </div>

              <?php if ( $dto->smokealarms_upgrade_preference) { ?>
                <br>
                <div class="form-check">
                  <input type="radio" class="form-check-input" name="smokealarms_upgrade_preference"
                    value=""
                    id="<?= $_uid = strings::rand() ?>">

                  <label class="form-check-label" for="<?= $_uid ?>">clear preference</label>

                </div>

              <?php } if ( $dto->smokealarms_upgrade_preference) ?>

            </div>

          </div>


        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save</button>

        </div>

      </div>

    </div>

  </div>

  <script>
  ( _ => $(document).ready( () => {
    $('#<?= $_form ?>')
    .on( 'submit', function( e) {
      let _form = $(this);
      let _data = _form.serializeFormJSON();

      _.post({
        url : _.url('<?= $this->route ?>'),
        data : _data,

      }).then( d => {
        if ( 'ack' == d.response) {
          $('#<?= $_modal ?>').trigger( 'success');
          $('#<?= $_modal ?>').modal( 'hide');

        }
        else {
          _.growl( d);

        }

      });

      return false;

    });

  })) (_brayworth_);
  </script>

</form>
