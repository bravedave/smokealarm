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
    <input type="hidden" name="action" value="save-smokealarm-type">
    <input type="hidden" name="id" value="<?= $dto->id ?>">

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
                <label for="<?= $_uid = strings::rand() ?>">Type</label>
                <input type="text" name="type" class="form-control"
                  placeholder="type"
                  id="<?= $_uid ?>"
                  value="<?= $dto->type ?>">

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