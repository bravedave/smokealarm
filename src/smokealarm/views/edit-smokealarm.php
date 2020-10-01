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

<div id="<?= $_wrap = strings::rand() ?>">
  <form id="<?= $_form = strings::rand() ?>" autocomplete="off">
    <input type="hidden" name="action" value="save-smokealarm">
    <input type="hidden" name="id" value="<?= $dto->id ?>">
    <input type="hidden" name="properties_id" value="<?= $dto->properties_id ?>">

    <div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="<?= $_modal = strings::rand() ?>" aria-labelledby="<?= $_modal ?>Label" aria-hidden="true">
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
                <label for="<?= $_uid = strings::rand() ?>">Address</label>
                <input type="text" name="address_street" class="form-control"
                  placeholder="property"
                  id="<?= $_uid ?>"
                  value="<?= $dto->address_street ?>">

              </div>

            </div>

            <div class="form-group row">
              <div class="col">
                <label for="<?= $_uid = strings::rand() ?>">Type</label>
                <input type="text" name="type" class="form-control"
                  placeholder="type"
                  id="<?= $_uid ?>"
                  value="<?= $dto->type ?>">

              </div>

            </div>

            <div class="form-group row">
              <div class="col">
                <label for="<?= $_uid = strings::rand() ?>">Location</label>
                <input type="text" name="location" class="form-control"
                  placeholder="location"
                  id="<?= $_uid ?>"
                  value="<?= $dto->location ?>">

              </div>

            </div>

            <div class="form-group row">
              <div class="col">
                <label for="<?= $_uid = strings::rand() ?>">Make</label>
                <input type="text" name="make" class="form-control"
                  placeholder="make"
                  id="<?= $_uid ?>"
                  value="<?= $dto->make ?>">

              </div>

            </div>

            <div class="form-group row"><!-- Model -->
              <label class="col-sm-3 col-form-label" for="<?= $_uid = strings::rand() ?>">Model</label>
              <div class="col">
                <input type="text" name="model" class="form-control"
                  placeholder="model" required
                  id="<?= $_uid ?>"
                  value="<?= $dto->model ?>">

              </div>

            </div>

            <div class="form-group row"><!-- Expiry -->
              <label class="col-sm-3 col-form-label" for="<?= $_uid = strings::rand() ?>">Expiry</label>
              <div class="col">
                <input type="date" name="expiry" class="form-control"
                  placeholder="expiry" required
                  id="<?= $_uid ?>"
                  value="<?php if ( strtotime( $dto->expiry) > 0 ) print $dto->expiry; ?>">

              </div>

            </div>

            <div class="form-group row">
              <div class="col">
                <label for="<?= $_uid = strings::rand() ?>">Status</label>
                <input type="text" name="status" class="form-control"
                  placeholder="status"
                  id="<?= $_uid ?>"
                  value="<?= $dto->status ?>">

              </div>

            </div>

            <div class="form-group row"><!-- Power -->
              <div class="col-sm-3 form-check-label">Power</div>
              <div class="col">
                <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" name="power"
                    value="battery" required
                    <?php if ( 'battery' == $dto->power) print 'checked' ?>
                    id="<?= $uid = strings::rand() ?>">

                  <label class="form-check-label" for="<?= $uid ?>">battery</label>

                </div>

                <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" name="power"
                    value="mains" required
                    <?php if ( 'mains' == $dto->power) print 'checked' ?>
                    id="<?= $uid = strings::rand() ?>">

                  <label class="form-check-label" for="<?= $uid ?>">mains</label>

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

  </form>
  <script>
  ( _ => {
    $(document).ready( () => {

      $('#<?= $_modal ?>').on( 'hidden.bs.modal', e => { $('#<?= $_wrap ?>').remove(); });
      $('#<?= $_modal ?>').modal( 'show');

      $('input[name="address_street"]', '#<?= $_form ?>').autofill({
        autoFocus : true,
        source: _.search.address,
        select: function(event, ui) {
          let o = ui.item;
          $('input[name="properties_id"]', '#<?= $_form ?>').val( o.id);

        },

      });

      $('#<?= $_form ?>')
      .on( 'submit', function( e) {
        let _form = $(this);
        let _data = _form.serializeFormJSON();
        let _modalBody = $('.modal-body', _form);

        console.log( _data);
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

</div>