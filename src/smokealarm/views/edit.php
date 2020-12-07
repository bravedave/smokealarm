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

use strings;
use sys;

$dto = $this->data->dto;

$_uidImage = strings::rand(); ?>

<style>
@media screen and (min-width: 768px) {
  #<?= $_uidImage ?>uploader .has-advanced-upload::before {
    content: "drag alarm image here to upload";

  }

}
</style>

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
          <div class="form-row row mb-2"><!-- Address -->
            <div class="col">
              <label class="mb-0" for="<?= $_uid = strings::rand() ?>">Address</label>
              <input type="text" name="address_street" class="form-control"
                placeholder="property"
                id="<?= $_uid ?>"
                value="<?= $dto->address_street ?>">

            </div>

          </div>

          <div class="form-row row mb-2"><!-- Alarm Image -->
            <div class="offset-sm-3 col-sm-6">
              <div class="row" id="<?= $_uidImage ?>"></div>

            </div>

          </div>

          <div class="form-row row mb-2"><!-- Alarm Image Uploaded -->
            <div class="offset-sm-3 col-sm-6" id="<?= $_uidImage ?>uploader"></div>

          </div>

          <div class="form-row row mb-2"><!-- Location -->
            <label class="col-2 col-form-label text-truncate" for="<?= $_uidLocation = strings::rand() ?>">location</label>
            <div class="col">
              <select name="location" class="form-control" id="<?= $_uidLocation ?>" required>
                <option value=""></option>
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

          <div class="form-row row mb-2"><!-- Make -->
            <label class="col-2 col-form-label text-truncate" for="<?= $_uid = strings::rand() ?>">make</label>
            <div class="col">
              <input type="text" name="make" class="form-control"
                placeholder="make" required
                id="<?= $_uid ?>"
                value="<?= $dto->make ?>">

            </div>

          </div>

          <div class="form-row row mb-2"><!-- Model -->
            <label class="col-2 col-form-label text-truncate" for="<?= $_uid = strings::rand() ?>">model</label>
            <div class="col">
              <input type="text" name="model" class="form-control"
                placeholder="model"
                id="<?= $_uid ?>"
                value="<?= $dto->model ?>">

            </div>

          </div>

          <div class="form-row row mb-2"><!-- Type -->
            <label class="col-2 col-form-label text-truncate" for="<?= $_uid = strings::rand() ?>">type</label>
            <div class="col">
              <div class="input-group">
                <input type="text" name="type" class="form-control"
                  placeholder="type" required
                  id="<?= $_uid ?>"
                  value="<?= $dto->type ?>">

                <div class="input-group-append">
                  <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                    id="<?= $_uid ?>toggle"></button>
                  <div class="dropdown-menu" id="<?= $_uid ?>items">
                    <?php
                    $_dao = new dao\smokealarm;
                    $_dtoSet = $_dao->dtoSet( $_dao->getDistinctTypes());
                    foreach ($_dtoSet as $_dto) {
                      printf(
                        '<a class="dropdown-item" href="#" data-value="%s">%s</a>',
                        \htmlentities( $_dto->type),
                        \htmlentities( $_dto->type)

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
                        console.log( $('#<?= $_uid ?>items'));
                        $('#<?= $_uid ?>toggle').dropdown('hide');

                      })

                    });

                  });
                  </script>

                </div>

              </div>

            </div>

          </div>

          <div class="form-row row mb-2"><!-- Expiry -->
            <label class="col-2 col-form-label text-truncate" for="<?= $_uid = strings::rand() ?>">expiry</label>
            <div class="col">
              <input type="date" name="expiry" class="form-control" required
                id="<?= $_uid ?>"
                value="<?php if ( strtotime( $dto->expiry) > 0 ) print $dto->expiry; ?>">

            </div>

          </div>

          <div class="form-row row mb-2"><!-- Connect -->
            <label class="col-2 col-form-label text-truncate" for="<?= $_uid = strings::rand() ?>">connect</label>
            <div class="col">
              <select class="form-control" title="connect" name="connect" id="<?= $_uid ?>">
                <option value=""></option>
                <?php
                foreach (config::smokealarm_connect as $v) {
                  printf(
                    '<option value="%s" %s>%s</option>',
                    $v,
                    $v == $dto->connect ? 'selected' : '',
                    $v

                  );

                }

                ?>

              </select>

            </div>

          </div>

          <div class="form-row row mb-2"><!-- Status -->
            <label class="col-2 col-form-label text-truncate" for="<?= $_uid = strings::rand() ?>">status</label>
            <div class="col">
              <select class="form-control" title="smoke alarm status" name="status" required id="<?= $_uid ?>">
                <option value=""></option>
                <?php
                if ( 'compliant' == $dto->status) print '<option value="compliant" selected>compliant</option>';
                if ( 'non compliant' == $dto->status) print '<option value="non compliant" selected>non compliant</option>';
                if ( 'required' == $dto->status) print '<option value="required" selected>required</option>';

                foreach (config::smokealarm_status as $v) {
                  printf(
                    '<option value="%s" %s>%s</option>',
                    $v,
                    $v == $dto->status ? 'selected' : '',
                    $v

                  );

                }

                ?>

              </select>

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
    $('input[name="address_street"]', '#<?= $_form ?>').autofill({
      autoFocus : true,
      source: _.search.alarm_address,
      select: function(event, ui) {
        let o = ui.item;
        $('input[name="properties_id"]', '#<?= $_form ?>').val( o.id);

      },

    });

    $('input[name="make"]', '#<?= $_form ?>').autofill({
      autoFocus : true,
      source: _.search.alarmMake,
      select: function(event, ui) {
        let o = ui.item;

      },

    });

    $('#<?= $_uidLocation ?>').on( 'change', e => {
      $('#<?= $_form ?>').trigger( 'get-photolog-images-of-alarm');
      $('#<?= $_form ?>').trigger( 'setup-uploader');

    });

    let clearAlarmImages = () => {
      return new Promise( resolve => {

        let el = $('#<?= $_uidImage ?>');
        el.html('');

        resolve( el);

      });

    };

    $('#<?= $_form ?>')
    .on( 'get-photolog-images-of-alarm', function(e) {
      let _form = $(this);
      let _data = _form.serializeFormJSON();

      clearAlarmImages().then( parent => {
        if ( '' == _data.location) return;

        _.post({
          url : _.url('<?= $this->route ?>'),
          data : {
            action : 'get-photolog-images-of-alarm',
            properties_id : _data.properties_id,
            location : _data.location

          }

        }).then( d => {
          if ( 'ack' == d.response) {
            $.each( d.alarms, ( i, alarm) => {
              let img = $('<img class="img-fluid">');
              img.attr( 'src', d.alarm.url);

              $('<div clas="col-4"></div>').append(img).appendTo( parent);
              img.on( 'contextmenu', function( e) {
                if ( e.shiftKey)
                  return;

                e.stopPropagation();e.preventDefault();

                _.hideContexts();

                let _context = _.context();

                _context.append( $('<a href="#">clear image</a>').on( 'click', function( e) {
                  e.stopPropagation();e.preventDefault();

                  _.post({
                    url : _.url( '<?= config::$PHOTOLOG_ROUTE ?>'),
                    data : {
                      action : 'set-alarm-location-clear',
                      id : d.alarm.photolog.id,
                      file : d.alarm.description

                    },

                  }).then( d => {
                    if ( 'ack' == d.response) {
                      _form.trigger('get-photolog-images-of-alarm');

                    }
                    else {
                      _.growl( d);

                    }

                  });

                  _context.close()

                }));

                _context.open( e);

              });

            });

          }
          else {
            _.growl( d);
            console.log( d);

          }

        });

      });

    })
    .on( 'setup-uploader', function( e) {
      <?php
      if ( $dto->id) {

        $diskSpace = sys::diskspace();
        if ( !$diskSpace->exceeded) {	?>
          let _form = $(this);
          let _data = _form.serializeFormJSON();

          $('#<?= $_uidImage ?>uploader').html( '');
          if ( !!_data.location) {
            ( c => {

              c.appendTo( '#<?= $_uidImage ?>uploader');

              _.fileDragDropHandler.call( c, {
                url : _.url( '<?= config::$PHOTOLOG_ROUTE ?>'),
                queue : false,
                multiple : false,
                postData : {
                  action : 'upload',
                  tag : 'smokealarm',
                  location : _data.location,
                  smokealarm_id : <?= $dto->id ?>
                },
                onUpload : d => {
                  if ( 'ack' == d.response) {
                    $('#<?= $_form ?>').trigger( 'get-photolog-images-of-alarm');

                  }

                }

              });

            }) ( _.fileDragDropContainer({fileControl : true}));

          }

      <?php	}	// if ( !$diskSpace->exceeded)

      } // $dto->id ?>

    })
    .on( 'submit', function( e) {
      let _form = $(this);
      let _data = _form.serializeFormJSON();

      // console.log( _data);
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

    $('#<?= $_form ?>').trigger( 'get-photolog-images-of-alarm');
    $('#<?= $_form ?>').trigger( 'setup-uploader');

  }))( _brayworth_);
  </script>

</form>
