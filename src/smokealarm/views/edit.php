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
          <div class="form-group row"><!-- Address -->
            <div class="col">
              <label class="mb-0" for="<?= $_uid = strings::rand() ?>">Address</label>
              <input type="text" name="address_street" class="form-control"
                placeholder="property"
                id="<?= $_uid ?>"
                value="<?= $dto->address_street ?>">

            </div>

          </div>

          <div class="form-group row"><!-- Type -->
            <label class="col-sm-3 col-form-label" for="<?= $_uid = strings::rand() ?>">Type</label>
            <div class="col">
              <select name="type" class="form-control" id="<?= $_uid ?>" required>
                <option></option>
                <?php
                $_dao = new dao\smokealarm_types;
                $_dtoSet = $_dao->dtoSet( $_dao->getAll());
                foreach ($_dtoSet as $_dto) {
                  printf(
                    '<option value="%s" %s>%s</option>',
                    $_dto->type,
                    $dto->type == $_dto->type ? 'selected' : '',
                    $_dto->type

                  );

                } ?>

              </select>

            </div>

          </div>

          <div class="form-group row"><!-- Location -->
            <label class="col-sm-3 col-form-label" for="<?= $_uid = strings::rand() ?>">Location</label>
            <div class="col">
              <select name="location" class="form-control" id="<?= $_uid ?>" required>
                <option></option>
                <?php
                $_dao = new dao\smokealarm_locations;
                $_dtoSet = $_dao->dtoSet( $_dao->getAll());
                foreach ($_dtoSet as $_dto) {
                  printf(
                    '<option value="%s" %s>%s</option>',
                    $_dto->location,
                    $dto->location == $_dto->location ? 'selected' : '',
                    $_dto->location

                  );

                } ?>

              </select>

            </div>

          </div>

          <div class="form-group row"><!-- Make -->
            <label class="col-sm-3 col-form-label" for="<?= $_uid = strings::rand() ?>">Make</label>
            <div class="col">
              <div class="input-group">
                <input type="text" name="make" class="form-control"
                  placeholder="make" required
                  id="<?= $_uid ?>"
                  value="<?= $dto->make ?>">

                <div class="input-group-append">
                  <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                  <div class="dropdown-menu" id="<?= $_uid ?>items">
                    <?php
                    $_dao = new dao\smokealarm;
                    $_dtoSet = $_dao->dtoSet( $_dao->getDistinctMakes());
                    foreach ($_dtoSet as $_dto) {
                      printf(
                        '<a class="dropdown-item" href="#" data-value="%s">%s</a>',
                        \htmlentities( $_dto->make),
                        \htmlentities( $_dto->make)

                      );

                    } ?>

                  </div>
                  <script>
                  $(document).ready( () => {
                    $('#<?= $_uid ?>items > a').each( ( i, el) => {
                      $(el).on( 'click', function( e) {
                        e.stopPropagation();e.preventDefault();

                        let _el = $(this);
                        let _data = _el.data();

                        $('#<?= $_uid ?>').val( _data.value);
                        $('#<?= $_uid ?>items').dropdown('hide');

                      })

                    });

                  });
                  </script>

                </div>

              </div>

            </div>

          </div>

          <div class="form-group row"><!-- Model -->
            <label class="col-sm-3 col-form-label" for="<?= $_uid = strings::rand() ?>">Model</label>
            <div class="col">
              <div class="input-group">
                <input type="text" name="model" class="form-control"
                  placeholder="model" required
                  id="<?= $_uid ?>"
                  value="<?= $dto->model ?>">

                <div class="input-group-append">
                  <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                  <div class="dropdown-menu" id="<?= $_uid ?>items">
                    <?php
                    $_dao = new dao\smokealarm;
                    $_dtoSet = $_dao->dtoSet( $_dao->getDistinctModels());
                    foreach ($_dtoSet as $_dto) {
                      printf(
                        '<a class="dropdown-item" href="#" data-value="%s">%s</a>',
                        \htmlentities( $_dto->model),
                        \htmlentities( $_dto->model)

                      );

                    } ?>

                  </div>
                  <script>
                  $(document).ready( () => {
                    $('#<?= $_uid ?>items > a').each( ( i, el) => {
                      $(el).on( 'click', function( e) {
                        e.stopPropagation();e.preventDefault();

                        let _el = $(this);
                        let _data = _el.data();

                        $('#<?= $_uid ?>').val( _data.value);
                        $('#<?= $_uid ?>items').dropdown('hide');

                      })

                    });

                  });
                  </script>

                </div>

              </div>

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
            <label class="col-sm-3" for="<?= $_uid = strings::rand() ?>">Status</label>
            <div class="col">
              <div class="form-check form-check-inline">
                <input type="radio" class="form-check-input" name="status"
                  value="compliant" required
                  <?php if ( 'compliant' == $dto->status) print 'checked' ?>
                  id="<?= $uid = strings::rand() ?>">

                <label class="form-check-label" for="<?= $uid ?>">compliant</label>

              </div>

              <div class="form-check form-check-inline">
                <input type="radio" class="form-check-input" name="status"
                  value="non compliant" required
                  <?php if ( 'non compliant' == $dto->status) print 'checked' ?>
                  id="<?= $uid = strings::rand() ?>">

                <label class="form-check-label" for="<?= $uid ?>">non compliant</label>

              </div>

              <div class="form-check form-check-inline">
                <input type="radio" class="form-check-input" name="status"
                  value="required" required
                  <?php if ( 'required' == $dto->status) print 'checked' ?>
                  id="<?= $uid = strings::rand() ?>">

                <label class="form-check-label" for="<?= $uid ?>">required</label>

              </div>

            </div>

          </div>

          <div class="form-group row"><!-- Power -->
            <div class="col-sm-3">Power</div>
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

  <script>
  ( _ => {
    $(document).ready( () => {
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

</form>
