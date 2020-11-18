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
  <input type="hidden" name="action" value="save-properties">
  <input type="hidden" name="smokealarms_company_id" value="<?= $dto->smokealarms_company_id ?>">
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
          <div class="form-row mb-2"><!-- smokealarms_required -->
            <div class="col-sm-4 col-form-label" for="<?= $uid = strings::rand() ?>">Required 2022</div>
            <div class="col-md-3">
              <input type="number" class="form-control" name="smokealarms_required"
                value="<?= $dto->smokealarms_required ?>"
                id="<?= $uid ?>">

            </div>

          </div>

          <div class="form-row mb-2"><!-- smokealarms_power -->
            <div class="col-sm-4">Power</div>
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

          <div class="form-row mb-2"><!-- smokealarms_2022_compliant -->
            <div class="offset-sm-4 col">
              <div class="form-check form-check-inline">
                <input type="checkbox" class="form-check-input" name="smokealarms_2022_compliant"
                  value="yes"
                  <?php if ( 'yes' == $dto->smokealarms_2022_compliant) print 'checked' ?>
                  id="<?= $uid = strings::rand() ?>">

                <label class="form-check-label" for="<?= $uid ?>">2022 Compliant</label>

              </div>

            </div>

          </div>

          <div class="form-row mb-2"><!-- smokealarms_company -->
            <div class="col-sm-4 col-form-label" for="<?= $uid = strings::rand() ?>">Company</div>
            <div class="col">
              <input type="text" class="form-control" name="smokealarms_company"
                value="<?= $dto->smokealarms_company ?>"
                <?php if ( (int)currentUser::restriction( 'smokealarm-company')) print 'readonly'; ?>
                id="<?= $uid ?>">

            </div>

          </div>

          <div class="form-row mb-2"><!-- smokealarms_last_inspection -->
            <div class="col-sm-4 col-form-label text-truncate" for="<?= $uid = strings::rand() ?>">Last inspection</div>
            <div class="col">
              <input type="date" class="form-control" name="smokealarms_last_inspection"
                value="<?= $dto->smokealarms_last_inspection ?>"
                id="<?= $uid ?>">

            </div>

          </div>

          <div class="row">
            <div class="col" id="<?= $_uid = strings::rand() ?>">&nbsp;</div>

          </div>
          <script>
          $(document).ready( () => { ( _ => {

            let tagSet = (file, tag) => {
              _.post({
                url : _.url('<?= $this->route ?>'),
                data : {
                  action : 'tag-set-for-property',
                  properties_id : <?= (int)$dto->id ?>,
                  file : file,
                  tag : tag

                },

              }).then( d => {
                if ( 'ack' == d.response) {
                  $('#<?= $_form ?>').trigger('load-documents');
                  $('#<?= $_modal ?>').trigger( 'success');

                }
                else {
                  _.growl( d);

                }

                return d;

              });

            };

            let tags = table => {
              _.post({
                url : _.url('<?= $this->route ?>'),
                data : {
                  action : 'tags-get-available'

                },

              }).then( d => {
                if ( 'ack' == d.response) {
                  // console.log( d.tags);

                  let tagContext = function( e) {
                    if ( e.shiftKey)
                      return;

                    e.stopPropagation();e.preventDefault();

                    _brayworth_.hideContexts();

                    let _tr = $(this);
                    let _data = _tr.data();
                    let _context = _brayworth_.context();

                    _context.append( $('<a href="#"><strong>view</strong></a>').on( 'click', function( e) {
                      e.stopPropagation();e.preventDefault();

                      _context.close();
                      _tr.trigger( 'view');

                    }));

                    $.each( d.tags, ( i, tag) => {
                      let ctrl = $('<a href="#"></a>').html( tag).on( 'click', function( e) {
                        e.stopPropagation();e.preventDefault();

                        _context.close();
                        tagSet( _data.file, tag);

                      });

                      if ( tag == _data.tag) ctrl.prepend('<i class="fa fa-check"></i>');

                      _context.append( ctrl);

                    })

                    _context.append( '<hr>');
                    _context.append( $('<a href="#">clear tag</a>').on( 'click', function( e) {
                      e.stopPropagation();e.preventDefault();

                      _context.close();
                      tagSet( _data.file, '');

                    }));

                    _context.append( $('<a href="#"><i class="fa fa-trash"></i>delete document</a>').on( 'click', function( e) {
                      e.stopPropagation();e.preventDefault();

                      _context.close();

                      _brayworth_.ask({
                        headClass : 'text-white bg-danger',
                        title : 'confirm delete',
                        text : 'are you sure ?',
                        buttons : {
                          yes : function() {
                            $(this).modal('hide');

                            // console.log( _data.file);
                            _.post({
                              url : _.url('<?= $this->route ?>'),
                              data : {
                                action : 'document-delete-for-property',
                                properties_id : <?= (int)$dto->id ?>,
                                file : _data.file,

                              },

                            }).then( d => {
                              _.growl( d);
                              $('#<?= $_form ?>').trigger('load-documents');

                            });

                          }

                        }

                      });

                    }));

                    _context.open( e);

                  };

                  $('tbody > tr', table)
                  .each( (i, tr) => {
                    $(tr)
                    .on( 'view', function( e) {
                      let _tr = $(this);
                      let _data = _tr.data();

                      window.open( _.url('<?= $this->route ?>/documentView/<?= (int)$dto->id ?>?d=' + encodeURIComponent(_data.file)))

                    })
                    .addClass('pointer')
                    .on( 'click', function( e) {
                      e.stopPropagation();e.preventDefault();

                      $(this).trigger( 'view');

                    })
                    .on( 'contextmenu', tagContext)

                  });

                }
                else {
                  _.growl( d);

                }

              });

            };

            $('#<?= $_form ?>').on('load-documents', (e) => {
              $('#<?= $_uid ?>').append('<div class="spinner-grow spinner-grow-sm d-block mx-auto my-1"></div>');
              _.post({
                url : _.url('<?= $this->route ?>'),
                data : {
                  action : 'document-get-for-property',
                  properties_id : <?= (int)$dto->id ?>

                },

              }).then( d => {
                if ( 'ack' == d.response) {
                  if ( d.data.length > 0) {
                    let table = $('<table class="table table-sm"></table>');
                    let thead = $('<thead class="small"></thead>').appendTo( table);
                    $('<tr></tr>')
                      .appendTo( thead)
                      .append('<td>name</td>')
                      .append('<td>size</td>')
                      .append('<td>tag</td>');

                    let tbody = $('<tbody></tbody>').appendTo( table);
                    $.each( d.data, (i, file) => {

                      let tr = $('<tr></tr>')
                        .data( 'file', file.name)
                        .data( 'tag', file.tag)
                        .appendTo( tbody);

                      $('<td></td>').html( file.name).appendTo( tr);
                      $('<td></td>').html( file.size).appendTo( tr);
                      $('<td></td>').html( file.tag).appendTo( tr);
                      // console.log( file);

                    });

                    $('#<?= $_uid ?>').html('').append( table);

                    tags( table);

                  }
                  else {
                    $('#<?= $_uid ?>').html('');

                  }

                }
                else {
                  _.growl( d);

                }

              });

            });

            // console.log( <?= $dto->smokealarms_tags ?>);

          }) (_brayworth_); });
          </script>

        </div>

        <div class="modal-footer">
          <div class="flex-fill" upload>
            <div class="progress mb-2 d-none">
              <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>

            </div>

          </div>

          <button type="submit" class="btn btn-primary">Save</button>

        </div>

      </div>

    </div>

  </div>

  <script>
  ( _ => $(document).ready( () => {
    <?php if ( !(int)currentUser::restriction( 'smokealarm-company')) { ?>
      $('input[name="smokealarms_company"]', '#<?= $_form ?>').autofill({
        autoFocus : true,
        source: _.search.alarmCompany,
        select: (e, ui) => {
          let o = ui.item;
          $('input[name="smokealarms_company_id"]', '#<?= $_form ?>').val( o.id);

        },

      });

    <?php } // if ( !(int)currentUser::restriction( 'smokealarm-company')) { ?>

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

    ( c => {
      _.fileDragDropHandler.call( c, {
        url : _.url( '<?= $this->route ?>'),
        postData : {
          action : 'document-upload',
          properties_id : <?= (int)$dto->id ?>

        },
        onUpload : d => {
          _.growl( d);
          $('#<?= $_form ?>').trigger('load-documents');

        }

      });

    })( _.fileDragDropContainer().appendTo( '#<?= $_form ?> div[upload]'));

    $('#<?= $_form ?>').trigger('load-documents');

  })) (_brayworth_);
  </script>

</form>
