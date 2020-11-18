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
use dvc\icon;
use strings;

$dto = $this->data->dto; ?>

<form id="<?= $_form = strings::rand() ?>" autocomplete="off">
  <input type="hidden" name="action" value="save-smokealarm-supplier">
  <input type="hidden" name="id" value="<?= $dto->id ?>">

  <div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="<?= $_modal = strings::rand() ?>" aria-labelledby="<?= $_modal ?>Label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header bg-secondary text-white py-2">
          <h5 class="modal-title" id="<?= $_modal ?>Label"><?= $this->title ?></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-row mb-2">
            <div class="col">
              <label for="<?= $_uid = strings::rand() ?>">Supplier Name</label>
              <input type="text" name="name" class="form-control"
                placeholder="name"
                <?php if ( (int)currentUser::restriction( 'smokealarm-company')) print 'readonly'; ?>
                id="<?= $_uid ?>"
                value="<?= $dto->name ?>">

            </div>

          </div>

          <div class="form-row mb-2">
            <div class="col">
              <label for="<?= $_uid = strings::rand() ?>">Contact</label>
              <input type="text" name="contact" class="form-control"
                placeholder="contact"
                id="<?= $_uid ?>"
                value="<?= $dto->contact ?>">

            </div>

          </div>

          <div class="form-row mb-2">
            <div class="col">
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text"><?= icon::get( icon::phone) ?></div>
                </div>

                <input type="text" name="phone" class="form-control"
                  placeholder="phone"
                  id="<?= $_uid ?>"
                  value="<?= $dto->phone ?>">

              </div>

            </div>

          </div>

          <div class="form-row mb-2">
            <div class="col">
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text">@</div>
                </div>

                <input type="text" name="email" class="form-control"
                  placeholder="email"
                  id="<?= $_uid ?>"
                  value="<?= $dto->email ?>">

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
  ( _ => $(document).ready( () => {
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

  }))( _brayworth_);
  </script>

</form>
