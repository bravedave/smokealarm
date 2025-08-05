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

use cms\{currentUser, strings, theme};
use dvc\icon;

$dto = $this->data->dto; ?>

<form id="<?= $_form = strings::rand() ?>" autocomplete="off">
  <input type="hidden" name="action" value="save-properties">
  <input type="hidden" name="smokealarms_company_id" value="<?= $dto->smokealarms_company_id ?>">
  <input type="hidden" name="id" value="<?= $dto->id ?>">

  <div class="modal fade" tabindex="-1" role="dialog" id="<?= $_modal = strings::rand() ?>" aria-labelledby="<?= $_modal ?>Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header py-2 <?= theme::modalHeader() ?>">
          <h5 class="modal-title" id="<?= $_modal ?>Label">
            <?php
            $addr = [$dto->address_street];
            if ($dto->address_suburb) $addr[] = $dto->address_suburb;
            if ($dto->address_postcode) $addr[] = $dto->address_postcode;

            print implode(' ', $addr);

            ?></h5>
          <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="form-row mb-2"><!-- smokealarms_required -->
            <div class="col-sm-4 col-form-label" for="<?= $uid = strings::rand() ?>">Required 2022</div>
            <div class="col-md-3">
              <input type="number" class="form-control" name="smokealarms_required" value="<?= $dto->smokealarms_required ?>" id="<?= $uid ?>">

            </div>

          </div>

          <div class="form-row mb-2"><!-- smokealarms_power -->
            <div class="col-sm-4">Power</div>
            <div class="col">
              <div class="form-check form-check-inline">
                <input type="radio" class="form-check-input" name="smokealarms_power" value="battery" required <?php if ('battery' == $dto->smokealarms_power) print 'checked' ?> id="<?= $uid = strings::rand() ?>">

                <label class="form-check-label" for="<?= $uid ?>">battery</label>

              </div>

              <div class="form-check form-check-inline">
                <input type="radio" class="form-check-input" name="smokealarms_power" value="mains" required <?php if ('mains' == $dto->smokealarms_power) print 'checked' ?> id="<?= $uid = strings::rand() ?>">

                <label class="form-check-label" for="<?= $uid ?>">mains</label>

              </div>

              <div class="form-check form-check-inline">
                <input type="radio" class="form-check-input" name="smokealarms_power" value="combination" required <?php if ('combination' == $dto->smokealarms_power) print 'checked' ?> id="<?= $uid = strings::rand() ?>">

                <label class="form-check-label" for="<?= $uid ?>">combination</label>

              </div>

            </div>

          </div>

          <div class="form-row mb-2"><!-- smokealarms_2022_compliant -->
            <div class="offset-sm-4 col">
              <div class="form-check form-check-inline">
                <input type="checkbox" class="form-check-input" name="smokealarms_2022_compliant" value="yes" <?php if ('yes' == $dto->smokealarms_2022_compliant) print 'checked' ?> id="<?= $uid = strings::rand() ?>">

                <label class="form-check-label" for="<?= $uid ?>">2022 Compliant</label>

              </div>

            </div>

          </div>

          <div class="form-row mb-2"><!-- smokealarms_company -->
            <div class="col-sm-4 col-form-label" for="<?= $uid = strings::rand() ?>">Company</div>
            <div class="col">
              <input type="text" class="form-control" name="smokealarms_company" value="<?= $dto->smokealarms_company ?>" <?php if ((int)currentUser::restriction('smokealarm-company')) print 'readonly'; ?> id="<?= $uid ?>">

            </div>

          </div>

          <div class="form-row mb-2"><!-- smokealarms_annual -->
            <div class="col-4 col-form-label text-truncate" for="<?= $uid = strings::rand() ?>">Annual Month</div>
            <div class="col">
              <input type="month" name="smokealarms_annual" class="form-control" <?php if ('yes' != currentUser::restriction('smokealarm-admin')) print 'readonly'; ?> id="?<?= $uid ?>" <?php
                                                                                                                                                                                          if (preg_match('@^[0-9]{4}-[0-9]{2}$@', $dto->smokealarms_annual)) printf(' value="%s"', $dto->smokealarms_annual);
                                                                                                                                                                                          ?>>

            </div>

          </div>

          <div class="form-row mb-2"><!-- smokealarms_last_inspection -->
            <div class="col-4 col-form-label text-truncate" for="<?= $uid = strings::rand() ?>">Last inspection</div>
            <div class="col">
              <div class="input-group">

                <input type="date" class="form-control" name="smokealarms_last_inspection" value="<?= $dto->smokealarms_last_inspection ?>" id="<?= $uid ?>">

                <div class="input-group-append" id="<?= $uid ?>today">
                  <button type="button" tabindex="-1" title="today" class="btn input-group-text"><?= icon::get(icon::calendar_day) ?></button>

                </div>

              </div>

            </div>

          </div>
          <script>
            (_ => {
              $('#<?= $uid ?>today').on('click', e => {
                e.stopPropagation();

                $('#<?= $uid ?>').val(_.dayjs().format('YYYY-MM-DD')).focus();

              });

            })(_brayworth_);
          </script>

          <div class="row">
            <div class="col mb-2 js-documents">&nbsp;</div>
          </div>

          <div class="row g-2">
            <div class="col mb-2 js-upload-errors">
            </div>
          </div>
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
    (_ => {
      const form = $('#<?= $_form ?>');
      const modal = $('#<?= $_modal ?>');

      const tagSet = (file, tag) => {

        _.post({
          url: _.url('<?= $this->route ?>'),
          data: {
            action: 'tag-set-for-property',
            properties_id: <?= (int)$dto->id ?>,
            file: file,
            tag: tag
          },
        }).then(d => {

          if ('ack' == d.response) {

            form.trigger('load-documents');
            modal.trigger('success');
          } else {

            _.growl(d);
          }

          return d;
        });
      };

      const tags = table => {

        _.post({
          url: _.url('<?= $this->route ?>'),
          data: {
            action: 'tags-get-available'
          },
        }).then(d => {

          if ('ack' == d.response) {
            // console.log( d.tags);

            table.find('tbody > tr').each((i, tr) => $(tr)
              .on('view', function(e) {
                let _tr = $(this);
                let _data = _tr.data();

                window.open(_.url('<?= $this->route ?>/documentView/<?= (int)$dto->id ?>?d=' + encodeURIComponent(_data.file)))
              })
              .addClass('pointer')
              .on('click', function(e) {
                e.stopPropagation();
                e.preventDefault();

                $(this).trigger('view');
              })
              .on('contextmenu', function(e) {

                if (e.shiftKey) return;
                const _context = _.context(e);

                const _tr = $(this);
                const _data = _tr.data();

                _context.append($('<a href="#"><strong>view</strong></a>').on('click', function(e) {
                  e.stopPropagation();
                  e.preventDefault();

                  _context.close();
                  _tr.trigger('view');
                }));

                $.each(d.tags, (i, tag) => {

                  let ctrl = $('<a href="#"></a>').html(tag).on('click', function(e) {
                    e.stopPropagation();
                    e.preventDefault();

                    _context.close();
                    tagSet(_data.file, tag);
                  });

                  if (tag == _data.tag) ctrl.prepend('<i class="bi bi-check"></i>');
                  _context.append(ctrl);
                })

                _context.append('<hr>');
                _context.append($('<a href="#">clear tag</a>').on('click', function(e) {
                  e.stopPropagation();
                  e.preventDefault();

                  _context.close();
                  tagSet(_data.file, '');
                }));

                _context.append($('<a href="#"><i class="bi bi-trash"></i>delete document</a>').on('click', function(e) {
                  e.stopPropagation();
                  e.preventDefault();

                  _context.close();

                  _.ask({
                    headClass: 'text-white bg-danger',
                    title: 'confirm delete',
                    text: 'are you sure ?',
                    buttons: {
                      yes: function() {
                        $(this).modal('hide');

                        // console.log( _data.file);
                        _.post({
                          url: _.url('<?= $this->route ?>'),
                          data: {
                            action: 'document-delete-for-property',
                            properties_id: <?= (int)$dto->id ?>,
                            file: _data.file,

                          },

                        }).then(d => {
                          _.growl(d);
                          $('#<?= $_form ?>').trigger('load-documents');

                        });

                      }

                    }

                  });

                }));

                _context.open(e);

              }));
          } else {

            _.growl(d);
          }
        });
      };

      form.on('load-documents', (e) => {

        form.find('.js-documents').append('<div class="spinner-grow spinner-grow-sm d-block mx-auto my-1"></div>');

        _.post({
          url: _.url('<?= $this->route ?>'),
          data: {
            action: 'document-get-for-property',
            properties_id: <?= (int)$dto->id ?>
          },
        }).then(d => {

          if ('ack' == d.response) {
            if (d.data.length > 0) {

              let table = $(`<table class="table table-sm">
                <thead class="small">
                  <tr>
                    <td>name</td>
                    <td>size</td>
                    <td>tag</td>
                  </tr>
                </thead>;
              </table>`);

              let tbody = $('<tbody></tbody>').appendTo(table);
              $.each(d.data, (i, file) => tbody.append(
                `<tr data-file="${file.name}" data-tag="${file.tag}">
                  <td>${file.name}</td>
                  <td>${file.size}</td>
                  <td>${file.tag}</td>
                </tr>`));

              form.find('.js-documents').empty().append(table);
              tags(table);
            } else {

              form.find('.js-documents').empty();
            }

          } else {

            _.growl(d);
          }
        });
      });

      // console.log( <?= $dto->smokealarms_tags ?>);

      modal.on('shown.bs.modal', e => {

        <?php if (!(int)currentUser::restriction('smokealarm-company')) { ?>

          form.find('input[name="smokealarms_company"]').autofill({
            autoFocus: true,
            source: _.search.alarmCompany,
            select: (e, ui) => {
              let o = ui.item;
              form.find('input[name="smokealarms_company_id"]').val(o.id);
            },
          });
        <?php } ?>

        form
          .on('submit', function(e) {
            let _form = $(this);
            let _data = _form.serializeFormJSON();

            _.post({
              url: _.url('<?= $this->route ?>'),
              data: _data,
            }).then(d => {

              if ('ack' == d.response) {
                modal.trigger('success');
                modal.modal('hide');

              } else {

                _.growl(d);
              }
            });

            return false;
          });

        (c => {
          _.fileDragDropHandler.call(c, {
            url: _.url('<?= $this->route ?>'),
            postData: {
              action: 'document-upload',
              properties_id: <?= (int)$dto->id ?>
            },
            onUpload: d => {

              _.growl(d);
              form.trigger('load-documents');

              $.each(d.bad, (i, bad) => {
                const row = `<div class="row text-danger"><div class="col-4 mb-2">${bad.name}</div><div class="col mb-2">${bad.result}</div></div>`;
                modal.find('.js-upload-errors').append(row);
              });
            }

          });

        })(_.fileDragDropContainer().appendTo('#<?= $_form ?> div[upload]'));

        form.trigger('load-documents');
      });
    })(_brayworth_);
  </script>
</form>