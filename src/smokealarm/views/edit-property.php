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

$dto = $this->data->dto; ?>

<form id="<?= $_form = strings::rand() ?>" autocomplete="off">
  <input type="hidden" name="action" value="save-properties">
  <input type="hidden" name="id" value="<?= $dto->id ?>">

  <div class="modal fade" tabindex="-1" role="dialog" id="<?= $_modal = strings::rand() ?>" aria-labelledby="<?= $_modal ?>Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header bg-secondary text-white py-2">
          <h5 class="modal-title" id="<?= $_modal ?>Label"><?= $this->title ?></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group row">
            <div class="col">
              <div class="form-control">
                <?php
                $addr = [$dto->address_street];
                if ($dto->address_suburb) $addr[] = $dto->address_suburb;
                if ($dto->address_postcode) $addr[] = $dto->address_postcode;

                print implode( ' ', $addr);

                ?>
              </div>

            </div>

          </div>

          <div class="form-group row"><!-- smokealarms_power -->
            <div class="col-sm-3 col-form-label" for="<?= $uid = strings::rand() ?>">Power</div>
            <div class="col-md-3">
              <input type="number" class="form-control" name="smokealarms_required"
                value="<?= $dto->smokealarms_required ?>"
                id="<?= $uid ?>">

            </div>

          </div>

          <div class="form-group row"><!-- smokealarms_power -->
            <div class="col-sm-3">Power</div>
            <div class="col">
              <div class="form-check form-check-inline">
                <input type="radio" class="form-check-input" name="smokealarms_power"
                  value="battery" required
                  <?php if ( 'battery' == $dto->smokealarms_power) print 'checked' ?>
                  id="<?= $uid = strings::rand() ?>">

                <label class="form-check-label" for="<?= $uid ?>">battery</label>

              </div>

              <div class="form-check form-check-inline">
                <input type="radio" class="form-check-input" name="smokealarms_power"
                  value="mains" required
                  <?php if ( 'mains' == $dto->smokealarms_power) print 'checked' ?>
                  id="<?= $uid = strings::rand() ?>">

                <label class="form-check-label" for="<?= $uid ?>">mains</label>

              </div>

              <div class="form-check form-check-inline">
                <input type="radio" class="form-check-input" name="smokealarms_power"
                  value="combination" required
                  <?php if ( 'combination' == $dto->smokealarms_power) print 'checked' ?>
                  id="<?= $uid = strings::rand() ?>">

                <label class="form-check-label" for="<?= $uid ?>">combination</label>

              </div>

            </div>

          </div>

          <div class="form-group row"><!-- smokealarms_2022_compliant -->
            <div class="offset-sm-3 col">
              <div class="form-check form-check-inline">
                <input type="checkbox" class="form-check-input" name="smokealarms_2022_compliant"
                  value="yes"
                  <?php if ( 'yes' == $dto->smokealarms_2022_compliant) print 'checked' ?>
                  id="<?= $uid = strings::rand() ?>">

                <label class="form-check-label" for="<?= $uid ?>">2022 Compliant</label>

              </div>

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
  ( _ => {
    $(document).ready( () => {

      $('#<?= $_form ?>')
      .on( 'submit', function( e) {
        let _form = $(this);
        let _data = _form.serializeFormJSON();
        let _modalBody = $('.modal-body', _form);

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

    });

  }) (_brayworth_);
  </script>

</form>
