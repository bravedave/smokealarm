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

use cms\{currentUser, keyregister, strings, useroptions};  ?>

<div id="<?= $_spinner = strings::rand() ?>" class="text-center p-5"><br><br><br><br><br><br></div>
<script>
  (_ => {
    let spinner = () => {
      let p = $('#<?= $_spinner ?>');

      if (p.parent().length > 0) {
        let _s = $('<div class="spinner-grow text-secondary ml-2"></div>')
          .appendTo('#<?= $_spinner ?>');

        setTimeout(() => spinner(), 1200);
      }

    };

    spinner();

  })(_brayworth_);
</script>
<div class="fade" id="<?= $_wrapper = strings::rand() ?>">
  <div class="form-row mb-2 d-print-none" id="<?= $srch = strings::rand() ?>envelope">
    <div class="col">
      <input type="search" class="form-control" autofocus id="<?= $srch ?>">

    </div>

  </div>

  <style media="screen">
    @media screen {
      .full-sorter {
        position: absolute;
        inset: 0;
        z-index: 994;
        display: grid;
        background: #fff;
        height: 100vh;
      }

      .full-sorter:before,
      .full-sorter:after {
        content: "";
        grid-area: 1/1;
        background: repeating-linear-gradient(-45deg, #000 0 5px, #0000 0 10px);
        opacity: 0.1;
      }

      .full-sorter:after {
        content: "sorting...";
        font-family: monospace;
        font-weight: bold;
        font-size: 15vmin;
        display: grid;
        place-items: center;
        -webkit-background-clip: text;
        background-clip: text;
        color: #ddd;
        opacity: 1;
        mask: linear-gradient(90deg, #0000 30%, #000, #0000 70%) right/300% 100%;
        -webkit-mask: linear-gradient(90deg, #0000 30%, #000, #0000 70%) right/300% 100%;
        animation: f 1s infinite linear;
      }

      @keyframes f {

        80%,
        100% {
          -webkit-mask-position: left;
        }
      }
    }

    @media screen and (max-width: 767px) {
      div[data-role="content-primary"] {
        padding: 0 0 1.5rem 0 !important;

      }

      #<?= $srch ?>envelope>.col {
        padding: .2rem 20px;
      }

    }
  </style>

  <div class="accordion" id="<?= $_accordion = strings::rand() ?>">
    <div class="card">
      <div class="card-header p-0" id="<?= $_heading = strings::rand() ?>">
        <div class="btn-group d-flex">
          <div class="btn btn-secondary btn-sm flex-fill" style="cursor: default;">
            <div class="form-row">
              <div class="col text-left text-truncate" id="<?= $_uidSortByAddress = strings::rand() ?>">address</div>
              <div class="col-lg-3 text-left d-none d-lg-block">
                <div class="form-row">
                  <div class="col text-truncate" id="<?= $_uidSortByCompany = strings::rand() ?>">company</div>
                  <div class="col-3 d-none d-xl-block text-center text-truncate" id="<?= $_uidSortInspectionMonth = strings::rand() ?>">month</div>
                  <div class="col-6 col-xl-5 text-truncate" id="<?= $_uidSortLastInspection = strings::rand() ?>">last inspection</div>
                  <div class="col-1">&nbsp;</div>
                </div>
              </div>
              <div class="col-3 col-lg-1 col-xl-3 text-left">
                <div class="form-row">
                  <div class="col d-none d-xl-block text-truncate">upgrade</div>
                  <div class="col-3 d-none d-xl-block text-center text-truncate" id="<?= $_uidSortByWorkOrder = strings::rand() ?>">w/o</div>
                  <div class="col text-center text-truncate" id="<?= $_uidSortLeaseStart = strings::rand() ?>">L.Start</div>

                </div>

              </div>

              <div class="col-lg-4 col-md-3 d-none d-lg-block">
                <div class="form-row">
                  <div class="col-5 text-center d-none d-md-block px-0 text-truncate" id="<?= $_uidSortLeaseEnd = strings::rand() ?>">L.End</div>
                  <div class="col-2 d-none d-md-block text-center">PM</div>

                  <div class="col-3 text-center d-flex">
                    <div class="d-md-none">#</div>
                    <div class="d-none d-md-block flex-fill text-center text-truncate" title="count">count</div>

                  </div>

                  <div class="col text-center px-0 text-truncate"><?= strings::html_tick ?>&nbsp;<br class="d-md-none">2022</div>

                </div>

              </div>

            </div>

          </div>

          <?php if ($this->data->na) {  ?>
            <div class="btn btn-secondary btn-sm flex-grow-0"><i class="bi bi-archive text-muted"></i></div>
          <?php } ?>

          <div class="btn btn-secondary btn-sm flex-grow-0"><i class="bi bi-circle text-muted"></i></div>

        </div>

      </div>

    </div>

    <?php
    $items = [];
    $index = -1;
    $pid = -1;
    foreach ($this->data->dtoSet as $dto) {
      if ($pid != $dto->properties_id) {
        $addr = [strings::GoodStreetString($dto->address_street)];
        if ($dto->address_suburb) $addr[] = $dto->address_suburb;
        // if ( $dto->address_postcode) $addr[] = $dto->address_postcode;

        $item = (object)[
          'properties_id' => $pid = $dto->properties_id,
          'address' => implode(' ', $addr),
          'street_index' => $dto->street_index,
          'forrent' => $dto->forrent,
          'people_id' => $dto->people_id,
          'people_name' => $dto->people_name,
          'smokealarms_power' => $dto->smokealarms_power,
          'smokealarms_required' => $dto->smokealarms_required,
          'smokealarms_2022_compliant' => $dto->smokealarms_2022_compliant,
          'smokealarms_company' => $dto->smokealarms_company,
          'smokealarms_annual' => $dto->smokealarms_annual,
          'smokealarms_last_inspection' => $dto->smokealarms_last_inspection,
          'smokealarms_tags' => $dto->smokealarms_tags,
          'smokealarms_na' => $dto->smokealarms_na,
          'smokealarms_upgrade_preference' => $dto->smokealarms_upgrade_preference,
          'smokealarms_workorder_sent' => $dto->smokealarms_workorder_sent,
          'smokealarms_workorder_schedule' => $dto->smokealarms_workorder_schedule,
          'alarms' => 0,
          'LeaseStart' => '',
          'LeaseFirstStart' => '',
          'LeaseStop' => '',
          'PropertyManager' => '',

        ];

        $item->LeaseStart = $dto->LeaseStart;
        $item->LeaseFirstStart = $dto->LeaseFirstStart;
        $item->LeaseStop = $dto->LeaseStop;
        $item->PropertyManager = $dto->PropertyManager;

        $items[] = $item;
        $index = count($items) - 1; // the last item

      }

      if ($index > -1 && \in_array($dto->status, config::smokealarm_status_compliant)) $items[$index]->alarms++;
    }

    // \sys::dump( $this->data->dtoSet);
    // \sys::dump( $items);

    $stats = (object)[
      'properties' => 0,
      'compliant' => 0,
      'notcompliant' => 0,

    ];
    $dao = new dao\properties;
    foreach ($items as $item) {
      $stats->properties++;

      $expired = $warning = false;
      if (($et = \strtotime($item->smokealarms_last_inspection)) > 0) {
        $etx = \strtotime(config::smokealarm_valid_time, $et);
        if (date('Y-m-d', $etx) < date('Y-m-d')) {
          // \sys::logger( sprintf('<%s> %s', time() - $etx, __METHOD__));
          $expired = true;
        } else {
          $etx = \strtotime(config::smokealarm_warn_time, $et);
          if (date('Y-m-d', $etx) < date('Y-m-d')) {
            $warning = true;
          }
        }
      }

      $btnClass = 'btn-light';
      if ($expired) {
        $btnClass = 'btn-danger';
      } elseif ($warning) {
        $btnClass = 'btn-warning';
      }

      printf(
        '<div class="card"
          data-address="%s"
          data-company="%s"
          data-inspect="%s"
          data-lease_start="%s"
          data-lease_stop="%s"
          data-smokealarms_annual="%s"
          data-workorder_sent="%s">',
        htmlentities($item->street_index),
        htmlentities($item->smokealarms_company),
        $item->smokealarms_last_inspection,
        strtotime($item->LeaseStart) < strtotime($item->LeaseFirstStart) ? $item->LeaseFirstStart : $item->LeaseStart,
        $item->LeaseStop,
        $item->smokealarms_annual,
        strtotime($item->smokealarms_workorder_sent) > 0 ? 'yes' : 'no'
      );  ?>
      <div class="card-header p-0" id="<?= $_heading = strings::rand() ?>">
        <div class="d-flex">
          <button class="btn <?= $btnClass ?> btn-sm flex-fill" type="button"
          data-toggle="collapse" data-target="#<?= $_collapse = strings::rand() ?>"
          data-properties_id="<?= $item->properties_id ?>"
          data-forrent="<?= $item->forrent ?>"
          data-address="<?= htmlentities($item->address) ?>"
          data-people_id="<?= $item->people_id ?>"
          data-people_name="<?= htmlentities($item->people_name) ?>"
          data-na="<?= $item->smokealarms_na ? 'yes' : 'no' ?>" data-workorder_sent="<?= strtotime($item->smokealarms_workorder_sent) > 0 ? 'yes' : 'no' ?>" aria-expanded="false" aria-controls="<?= $_collapse ?>">
            <?php
            $complianceClass = '';
            $complianceHtml = '';
            $upgradePref = $item->smokealarms_upgrade_preference;
            if ('yes' == $item->smokealarms_2022_compliant) {
              $complianceClass = 'text-success';
              $complianceHtml = strings::html_tick;
              $upgradePref = 'compliant';
              $stats->compliant++;
            } else {
              $stats->notcompliant++;
              if ($item->alarms < $item->smokealarms_required) {
                $complianceHtml = sprintf('<span class="badge badge-danger">%s</span>', $item->alarms - $item->smokealarms_required);
              }
            }

            $fakeProperty = (object)[
              'id' => $item->properties_id,
              'smokealarms_tags' => $item->smokealarms_tags

            ];

            $hasCert = $dao->hasSmokeAlarmComplianceCertificate($fakeProperty);

            $wo = strtotime($item->smokealarms_workorder_sent) > 0 ? strings::html_tick : '&nbsp;';
            $woTitle = sprintf('title="%s"', strings::asLocalDate($item->smokealarms_workorder_sent));
            if (strtotime($item->smokealarms_workorder_schedule) > 0) {
              $wo = sprintf(
                '<i class="bi bi-clock" data-toggle="popover" data-trigger="hover" data-content="%s" title="Workorder Scheduled"></i>',
                strings::asLocalDate($item->smokealarms_workorder_schedule)

              );
              $woTitle = '';
            }

            $leaseStart = '';
            $leaseEnd = '';
            $leaseStart = sprintf(
              '<div class="col text-center text-truncate" title="%s">%s</div>',
              $item->LeaseStart,
              strings::asLocalDate(strtotime($item->LeaseStart) < strtotime($item->LeaseFirstStart) ? $item->LeaseFirstStart : $item->LeaseStart)

            );

            $leaseEnd = sprintf(
              '<div class="col-5 d-none d-md-block text-center">%s</div>
                    <div class="col-2 d-none d-md-block text-center">%s</div>',
              strings::asLocalDate($item->LeaseStop),
              strings::initials($item->PropertyManager)

            );

            printf(
              '<div class="form-row">
                    <div class="col text-left text-truncate" address>%s</div>
                    <div class="col-lg-3 text-left d-none d-lg-block">
                      <div class="form-row">
                        <div class="col text-truncate" company>%s</div>
                        <div class="col-3 d-none d-xl-block text-truncate text-center" title="%s" annual>%s</div>
                        <div class="col-6 col-xl-5 text-truncate" last_inspection>%s</div>
                        <div class="col-1" certificate title="%s">%s</div>
                      </div>

                    </div>

                    <div class="col-3 col-lg-1 col-xl-3 text-left">
                      <div class="form-row">
                        <div class="col d-none d-xl-block text-truncate" upgrade-pref>%s</div>
                        <div class="col-3 d-none d-xl-block text-center" %s work-order>%s</div>
                        %s

                      </div>

                    </div>

                    <div class="col-lg-4 col-md-3 d-none d-lg-block">
                      <div class="form-row">
                        %s
                        <div class="col-3 text-center" compliant>%s</div>
                        <div class="col text-center %s" compliance>%s</div>

                      </div>

                    </div>

                  </div>',
              $item->address,
              $item->smokealarms_company,
              $item->smokealarms_annual,
              strtotime($item->smokealarms_annual) > 0 ? date('n/y', strtotime($item->smokealarms_annual)) : '&nbsp;',
              strings::asLocalDate($item->smokealarms_last_inspection),
              $hasCert ? 'has certificate' : 'no certificate',
              $hasCert ? strings::html_tick : '&nbsp;',
              $upgradePref,
              $woTitle,
              $wo,
              $leaseStart,
              $leaseEnd,
              $item->alarms,
              $complianceClass,
              $complianceHtml

            );

            ?>

          </button>

          <?php if ($this->data->na) {  ?>
            <div class="btn btn-sm <?= $btnClass ?> flex-grow-0"><i class="bi bi-archive<?= $item->smokealarms_na ? '-fill' : '' ?>"></i></div>
          <?php } ?>
          <button type="button" class="btn btn-sm <?= $btnClass ?> flex-grow-0" edit-property><i class="bi bi-pencil"></i></button>

        </div>

      </div>

      <div id="<?= $_collapse ?>" class="collapse" aria-labelledby="<?= $_heading ?>" data-parent="#<?= $_accordion ?>" data-properties_id="<?= $item->properties_id ?>">

        <div class="card-body px-2 px-md-3 py-1 py-md-2"></div>

      </div>


    <?php
      print '</div class="card">';
    }  ?>

  </div>

</div>
<script>
  (_ => {
    let cursor = 'url(<?= \dvc\icon::base64_data(\dvc\icon::arrow_down_up) ?>),auto';

    $('#<?= $_uidSortByAddress ?>')
      .css('cursor', cursor)
      .on('click', e => {
        e.stopPropagation();
        e.preventDefault();
        $('#<?= $_accordion ?>').trigger('sort-address');

      });

    $('#<?= $_uidSortByCompany ?>')
      .css('cursor', cursor)
      .on('click', e => {
        e.stopPropagation();
        e.preventDefault();
        $('#<?= $_accordion ?>').trigger('sort-company');

      });

    $('#<?= $_uidSortInspectionMonth ?>')
      .css('cursor', cursor)
      .on('click', e => {
        e.stopPropagation();
        e.preventDefault();
        $('#<?= $_accordion ?>').trigger('sort-annual-month');

      });

    $('#<?= $_uidSortLastInspection ?>')
      .css('cursor', cursor)
      .on('click', e => {
        e.stopPropagation();
        e.preventDefault();
        $('#<?= $_accordion ?>').trigger('sort-last-inspection');

      });

    $('#<?= $_uidSortByWorkOrder ?>')
      .css('cursor', cursor)
      .on('click', e => {
        e.stopPropagation();
        e.preventDefault();
        $('#<?= $_accordion ?>').trigger('sort-last-work-order');

      });

    $('#<?= $_uidSortLeaseStart ?>')
      .css('cursor', cursor)
      .on('click', e => {
        e.stopPropagation();
        e.preventDefault();
        $('#<?= $_accordion ?>').trigger('sort-lease-start');

      });

    $('#<?= $_uidSortLeaseEnd ?>')
      .css('cursor', cursor)
      .on('click', e => {
        e.stopPropagation();
        e.preventDefault();
        $('#<?= $_accordion ?>').trigger('sort-lease-stop');

      });

    let sortFunc = (a, b, key, sorttype) => {
      let ae = $(a).data(key);
      let be = $(b).data(key);

      if ('numeric' == sorttype) {
        if ('undefined' == typeof ae) ae = 0;
        if ('undefined' == typeof be) be = 0;
        return (Number(ae) - Number(be));

      } else {
        if ('undefined' == typeof ae) ae = '';
        if ('undefined' == typeof be) be = '';
        return (String(ae).toUpperCase().localeCompare(String(be).toUpperCase()));

      }

    };

    let getKeySet = property => {
      return new Promise(resolve => {
        _
          .post({
            url: _.url('keyregister'),
            data: {
              action: 'get-keys-for-property',
              id: property

            },

          })
          .then(d => {
            if ('ack' == d.response) {
              $.each(d.data, (i, keyset) => {
                if (<?= keyregister\config::keyset_management ?> == keyset.keyset_type) {
                  resolve(keyset);

                }

              });

            }

          });
      });

    };

    $('#<?= $_accordion ?>')
      .on('-sort-hide-', e => _.hourglass.on())
      .on('-sort-reveal-', e => _.hourglass.off())
      .on('sort-address', function(e) {
        $(this).trigger('-sort-hide-');
        setTimeout(() => {
          let _me = $(this);
          let _data = _me.data();
          let order = 'desc' == String(_data.order) ? "asc" : "desc";

          _me.data('order', order);

          let items = $('> div[data-address]', this);
          items.sort((a, b) => sortFunc(a, b, 'address', 'string'));

          if (order == "desc") {
            let first = $('> div', this).first();
            $.each(items, (i, el) => $(el).insertAfter(first));
          } else {
            $.each(items, (i, el) => _me.append(el));

          }

          $(this).trigger('-sort-reveal-');

        }, 200);
      })
      .on('sort-annual-month', function(e) {
        $(this).trigger('-sort-hide-');
        setTimeout(() => {
          let _me = $(this);
          let _data = _me.data();
          let order = 'desc' == String(_data.order) ? "asc" : "desc";

          _me.data('order', order);

          let items = $('> div[data-smokealarms_annual]', this);
          items.sort((a, b) => sortFunc(a, b, 'smokealarms_annual', 'string'));

          if (order == "desc") {
            let first = $('> div', this).first();
            $.each(items, (i, el) => $(el).insertAfter(first));
          } else {
            $.each(items, (i, el) => _me.append(el));

          }
          $(this).trigger('-sort-reveal-');

        }, 200);

      })
      .on('sort-company', function(e) {
        $(this).trigger('-sort-hide-');
        setTimeout(() => {
          let _me = $(this);
          let _data = _me.data();
          let order = 'desc' == String(_data.order) ? "asc" : "desc";

          _me.data('order', order);

          let items = $('> div[data-company]', this);
          items.sort((a, b) => sortFunc(a, b, 'company', 'string'));

          if (order == "desc") {
            let first = $('> div', this).first();
            $.each(items, (i, el) => $(el).insertAfter(first));
          } else {
            $.each(items, (i, el) => _me.append(el));

          }

          $(this).trigger('-sort-reveal-');

        }, 200);
      })
      .on('sort-last-inspection', function(e) {
        $(this).trigger('-sort-hide-');
        setTimeout(() => {
          let _me = $(this);
          let _data = _me.data();
          let order = 'desc' == String(_data.order) ? "asc" : "desc";

          _me.data('order', order);

          let items = $('> div[data-inspect]', this);
          items.sort((a, b) => sortFunc(a, b, 'inspect', 'string'));

          if (order == "desc") {
            let first = $('> div', this).first();
            $.each(items, (i, el) => $(el).insertAfter(first));
          } else {
            $.each(items, (i, el) => _me.append(el));

          }

          $(this).trigger('-sort-reveal-');

        }, 200);
      })
      .on('sort-lease-start', function(e) {
        $(this).trigger('-sort-hide-');
        setTimeout(() => {
          let _me = $(this);
          let _data = _me.data();
          let order = 'desc' == String(_data.order) ? "asc" : "desc";

          _me.data('order', order);

          let items = $('> div[data-lease_start]', this);
          items.sort((a, b) => sortFunc(a, b, 'lease_start', 'string'));

          if (order == "desc") {
            let first = $('> div', this).first();
            $.each(items, (i, el) => $(el).insertAfter(first));
          } else {
            $.each(items, (i, el) => _me.append(el));

          }

          $(this).trigger('-sort-reveal-');

        }, 200);
      })
      .on('sort-lease-stop', function(e) {
        $(this).trigger('-sort-hide-');
        setTimeout(() => {
          let _me = $(this);
          let _data = _me.data();
          let order = 'desc' == String(_data.order) ? "asc" : "desc";

          _me.data('order', order);

          let items = $('> div[data-lease_stop]', this);
          items.sort((a, b) => sortFunc(a, b, 'lease_stop', 'string'));

          if (order == "desc") {
            let first = $('> div', this).first();
            $.each(items, (i, el) => $(el).insertAfter(first));
          } else {
            $.each(items, (i, el) => _me.append(el));

          }
          $(this).trigger('-sort-reveal-');

        }, 200);

      })
      .on('sort-last-work-order', function(e) {
        $(this).trigger('-sort-hide-');
        setTimeout(() => {
          let _me = $(this);
          let _data = _me.data();
          let order = 'desc' == String(_data.order) ? "asc" : "desc";

          _me.data('order', order);

          let items = $('> div[data-workorder_sent]', this);
          items.sort((a, b) => sortFunc(a, b, 'workorder_sent', 'string'));

          if (order == "desc") {
            let first = $('> div', this).first();
            $.each(items, (i, el) => $(el).insertAfter(first));
          } else {
            $.each(items, (i, el) => _me.append(el));

          }

          $(this).trigger('-sort-reveal-');

        }, 200);
      });

    $('#<?= $_accordion ?> button[data-properties_id]')
      .on('edit', function(e) {
        let _me = $(this);
        let _data = _me.data();

        _.get.modal(_.url('<?= $this->route ?>/editproperty/' + _data.properties_id))
          .then(modal => modal.on('success', e => _me.trigger('refresh')));

      })
      .on('not-applicable', function(e) {
        let _me = $(this);
        let _data = _me.data();

        _.post({
          url: _.url('<?= $this->route ?>'),
          data: {
            action: 'mark-property-na',
            id: _data.properties_id,
            value: 'yes' == String(_data.na) ? 0 : 1

          },

        }).then(d => {
          if ('ack' == d.response) {
            _me.data('na', d.na);

            // console.log( d.na);

            $('.bi-archive, .bi-archive-fill', _me.closest('.card-header'))
              .removeClass('bi-archive bi-archive-fill')
              .addClass('yes' == String(d.na) ? 'bi-archive-fill' : 'bi-archive');

            // console.log( $('.bi-archive, .bi-archive-fill', _me.closest('.card-header')));

          } else {
            _.growl(d);

          }

        });


      })
      .on('refresh', function(e) {
        let _me = $(this);
        let _data = _me.data();

        // console.log( _data);
        /*
        _brayworth_.post({
          url : _brayworth_.url('smokealarm'),
          data : {
            action : 'get-property-by-id',
            id : 1
          }
        }).then( d => console.log(d));
          */

        _.post({
          url: _.url('<?= $this->route ?>'),
          data: {
            action: 'get-property-by-id',
            id: _data.properties_id

          },

        }).then(d => {
          if ('ack' == d.response) {

            // console.log( d);

            $('[address]', _me).html(d.address);
            $('[compliant]', _me).html(d.compliant);
            $('[power]', _me).html(d.dto.smokealarms_power);
            $('[company]', _me).html(d.dto.smokealarms_company);
            if ('yes' == d.dto.smokealarms_2022_compliant) {
              $('[upgrade-pref]', _me).html('compliant');

            } else {
              $('[upgrade-pref]', _me).html(d.dto.smokealarms_upgrade_preference);

            }

            let wod = _.dayjs(d.smokealarms_workorder_schedule);
            if (wod.isValid() && wod.unix() > 0) {
              let icon = $('<i class="bi bi-clock" title="Workorder Scheduled"></i>');
              icon.attr({
                'data-trigger': 'hover',
                'data-content': wod.format('L'),

              });

              $('[work-order]', _me)
                .html('')
                .append(icon)
                .removeAttr('title');

              icon.popover();

            } else {
              if ('yes' == d.smokealarms_workorder_sent) {
                // console.log( 'workorder-sent');
                $('[work-order]', _me)
                  .html('<?= strings::html_tick ?>')
                  .attr('title', d.smokealarms_workorder_date);

              } else {
                // console.log( 'workorder-clear');
                $('[work-order]', _me).html('&nbsp;').removeAttr('title');

              }

            }

            _me.data('workorder_sent', d.smokealarms_workorder_sent);
            _me.closest('.card').data('workorder_sent', d.smokealarms_workorder_sent);

            // console.log(d.dto);
            let dla = _.dayjs(d.dto.smokealarms_annual);
            // console.log('annual', dla.isValid() && dla.unix() > 0 ? dla.format('L') : '');
            $('[annual]', _me).html(dla.format('M/YY'));

            let dli = _.dayjs(d.dto.smokealarms_last_inspection);
            $('[last_inspection]', _me).html(dli.isValid() && dli.unix() > 0 ? dli.format('L') : '');

            if ('yes' == d.dto.smokealarms_2022_compliant) {
              $('[compliance]', _me)
                .addClass('text-success')
                .html('<?= strings::html_tick ?>');

            } else if (Number(d.compliant) < Number(d.dto.smokealarms_required)) {
              $('[compliance]', _me)
                .removeClass('text-success')
                .html('')
                .append($('<span class="badge badge-danger"></span>').html(Number(d.compliant) - Number(d.dto.smokealarms_required)));

            } else {
              $('[compliance]', _me)
                .removeClass('text-success')
                .html('&nbsp;');

            }

            if ('yes' == d.hasSmokeAlarmComplianceCertificate) {
              $('[certificate]', _me)
                .attr('title', 'has certificate')
                .html('<?= strings::html_tick ?>');

            } else {
              $('[certificate]', _me)
                .attr('title', 'no certificate')
                .html('&nbsp;');

            }

            if (d.dto.smokealarm_expired) {
              $('> button', _me.parent()).removeClass('btn-warning').addClass('btn-danger');

            } else if (d.dto.smokealarm_warning) {
              $('> button', _me.parent()).removeClass('btn-danger').addClass('btn-warning');

            } else {
              $('> button', _me.parent()).removeClass('btn-danger btn-warning');

            }
            // console.log( d);

          } else {
            _.growl(d);

          }

        });

      })
      .on('contextmenu', function(e) {
        if (e.shiftKey)
          return;

        e.stopPropagation();
        e.preventDefault();

        _brayworth_.hideContexts();

        let _me = $(this);
        let _data = _me.data();
        let _context = _brayworth_.context();

        _context.append($('<a href="#"><strong>Open/Close</strong></a>').on('click', function(e) {
          e.stopPropagation();
          e.preventDefault();

          _context.close();
          $(_data.target).collapse('toggle');

        }));

        _context.append($('<a href="#">Edit Property</a>').on('click', function(e) {
          e.stopPropagation();
          e.preventDefault();

          _context.close();
          _me.trigger('edit');

        }));

        <?php if ('yes' == currentUser::restriction('smokealarm-admin')) {  ?>
          _context.append($('<a href="#">Upgrade Preference</a>').on('click', function(e) {
            e.stopPropagation();
            e.preventDefault();

            _context.close();
            _me.trigger('upgrade-preferences');

          }));

          if ('yes' == _data.workorder_sent) {
            _context.append($('<a href="#"><i class="bi bi-check"></i>Workorder Sent</a>').on('click', function(e) {
              e.stopPropagation();
              e.preventDefault();

              _context.close();
              _me.trigger('workorder-sent-clear');

            }));

          } else {
            _context.append($('<a href="#">Workorder Sent</a>').on('click', function(e) {
              e.stopPropagation();
              e.preventDefault();

              _context.close();
              _me.trigger('workorder-sent');

            }));

          }

          _context.append(
            $('<a href="#">Schedule Next Inspection</a>')
            .on('click', e => {
              e.stopPropagation();
              e.preventDefault();

              _context.close();
              _me.trigger('workorder-schedule');

            })
          );

          _context.append(
            $('<a href="#"></a>')
            .on('click', e => {
              e.stopPropagation();
              e.preventDefault();

              _context.close();
              _me.trigger('set-annual-month-last');

            })
            .on('recon', function(e) {
              let d = _.dayjs();
              $(this).html('Set annual month to ' + d.subtract(1, 'month').format('MMM YYYY'))

            })
            .trigger('recon')
          );

          _context.append(
            $('<a href="#"></a>')
            .on('click', e => {
              e.stopPropagation();
              e.preventDefault();

              _context.close();
              _me.trigger('set-annual-month-now');

            })
            .on('recon', function(e) {
              let d = _.dayjs();
              $(this).html('Set annual month to ' + d.format('MMM YYYY'))

            })
            .trigger('recon')
          );

          _context.append($('<a href="#"><i class="bi bi-eraser"></i>Clear Workorder Data</a>').on('click', function(e) {
            e.stopPropagation();
            e.preventDefault();

            _context.close();
            _me.trigger('workorder-clear');

          }));

          let ctrl = $('<a href="#">Not Applicable</a>').on('click', function(e) {
            e.stopPropagation();
            e.preventDefault();

            _context.close();
            _me.trigger('not-applicable');

          });

          if ('yes' == String(_data.na)) ctrl.prepend('<i class="bi bi-check"></i>');
          _context.append(ctrl);

        <?php } ?>

        let url = _.url('property/view/' + _data.properties_id);
        <?php if (currentUser::option(useroptions::properties_show_rental_if_rental) == 'yes') { ?>

          if ('1' == this.dataset.forrent) {

            url = _.url('properties/forrent/?idx=' + _data.properties_id);
          }
        <?php } ?>
        _context.append.a({
          html: 'goto ' + _data.address,
          href: url,
          target: '_blank'
        });

        if (Number(_data.people_id) > 0) {
          _context.append(
            $('<a href="#" target="_blank">goto ' + _data.people_name + '</a>')
            .attr('href', _.url('person/view/' + _data.people_id))
            .on('click', e => _context.close())

          );

        }

        _context.open(e);

      })
      .on('set-annual-month-last', function(e) {
        let _me = $(this);
        let _data = _me.data();

        _.post({
          url: _.url('<?= $this->route ?>'),
          data: {
            action: 'set-annual-month-last',
            id: _data.properties_id

          },

        }).then(d => {
          _.growl(d);
          if ('ack' == d.response) {
            let dla = _.dayjs(d.date);
            // console.log('annual', dla.isValid() && dla.unix() > 0 ? dla.format('L') : '');
            $('[annual]', _me).html(dla.format('M/YY'));
          }

        });

      })
      .on('set-annual-month-now', function(e) {
        let _me = $(this);
        let _data = _me.data();

        _.post({
          url: _.url('<?= $this->route ?>'),
          data: {
            action: 'set-annual-month-now',
            id: _data.properties_id

          },

        }).then(d => {
          _.growl(d);
          if ('ack' == d.response) {
            let dla = _.dayjs(d.date);
            // console.log('annual', dla.isValid() && dla.unix() > 0 ? dla.format('L') : '');
            $('[annual]', _me).html(dla.format('M/YY'));
          }

        });

      })
      .on('upgrade-preferences', function(e) {
        let _me = $(this);
        let _data = _me.data();

        _.get.modal(_.url('<?= $this->route ?>/editUpgradePreferences/' + _data.properties_id))
          .then(modal => modal.on('success', e => _me.trigger('refresh')));

      })
      .on('workorder-clear', function(e) {
        let _me = $(this);
        let _data = _me.data();

        _.post({
          url: _.url('<?= $this->route ?>'),
          data: {
            action: 'workorder-clear',
            id: _data.properties_id

          },

        }).then(d => {
          if ('ack' == d.response) {
            _me.trigger('refresh');

          } else {
            _.growl(d);

          }

        });

      })
      .on('workorder-schedule', function(e) {
        let _me = $(this);
        let _data = _me.data();

        _.get.modal(_.url('<?= $this->route ?>/editScheduleWorkorder/' + _data.properties_id))
          .then(modal => modal.on('success', e => _me.trigger('refresh')));

      })
      .on('workorder-sent', function(e) {
        let _me = $(this);
        let _data = _me.data();

        _.post({
          url: _.url('<?= $this->route ?>'),
          data: {
            action: 'save-properties-workorder-sent',
            id: _data.properties_id
          },

        }).then(d => {
          if ('ack' == d.response) {
            _me.trigger('refresh');

          } else {
            _.growl(d);

          }

        });

      })
      .on('workorder-sent-clear', function(e) {
        let _me = $(this);
        let _data = _me.data();

        _.post({
          url: _.url('<?= $this->route ?>'),
          data: {
            action: 'save-properties-workorder-sent-clear',
            id: _data.properties_id
          },

        }).then(d => {
          if ('ack' == d.response) {
            _me.trigger('refresh');

          } else {
            _.growl(d);

          }

        });


      });

    $('#<?= $_accordion ?> button[edit-property]')
      .on('click', function(e) {
        e.stopPropagation();
        e.preventDefault();

        let _me = $(this);
        _me.siblings('button[data-properties_id]').trigger('edit');

      });

    $('#<?= $_accordion ?> > .card > .collapse')
      .on('lookup-tenant', function(e) {
        let _me = $(this);
        let _data = _me.data();

        (() => {
          let row = $('<div class="form-row"></div>');

          row.append('<div class="col-md-2 col-xl-1 text-truncate col-form-label" title=ckey">key</div>');
          let col = $('<div class="col"></div>').appendTo(row);
          let _row = $('<div class="form-row mb-2"></div>').appendTo(col);

          let nc = $('<input type="text" readonly class="form-control">');
          getKeySet(_data.properties_id)
            .then(keySet => nc.val(keySet.keyset));

          $('<div class="col-md-5 mb-1 mb-md-0"></div>').append(nc).appendTo(_row);

          $('.card-body', this).prepend(row);

        })();

        (() => {
          let row = $('<div class="form-row"></div>');
          $('.card-body', this).prepend(row);

          row.append('<div class="col-md-2 col-xl-1 text-truncate col-form-label pb-0" title="owner">owner/s</div>');
          let notaries = $('<div class="col"></div>').appendTo(row);

          _cms_
            .getPropertyContacts(_data.properties_id)
            .then(d => {
              $.each(d.notaries, (i, notary) => {

                if ('1' == String(notary.owner)) {

                  let row = $('<div class="form-row"></div>');
                  let ig, ctrl;

                  ctrl = $('<input type="text" readonly class="form-control">')
                    .val(notary.name);

                  $('<div class="col mb-2"></div>')
                    .append(ctrl)
                    .appendTo(row);

                  ctrl = $('<input type="text" readonly class="form-control">')
                    .val(notary.email);

                  $('<div class="col-md-4 mb-2"></div>')
                    .append(ctrl)
                    .appendTo(row);

                  ig = $('<div class="input-group"></div>');
                  $('<div class="col-md-3 mb-2"></div>')
                    .append(ig)
                    .appendTo(row);

                  ctrl = $('<input type="text" readonly class="form-control">')
                    .val(notary.mobile)
                    .appendTo(ig);

                  if (String(notary.mobile).IsMobilePhone()) {
                    ctrl.val(String(notary.mobile).AsMobilePhone());

                    if (_.browser.isMobileDevice) {
                      $('<a class="input-group-text"><i class="bi bi-chat-dots"></i></a>')
                        .attr('href', 'sms://' + String(notary.mobile).replace(/[^0-9]/g, ''))
                        .appendTo(ig);

                      $('<a class="input-group-text"><i class="bi bi-telephone"></i></a>')
                        .attr('href', 'tel://' + String(notary.mobile).replace(/[^0-9]/g, ''))
                        .appendTo(ig);

                    }
                  }

                  row
                    .appendTo(notaries);

                }

              });

            });

        })();
        /*-- --[get-tenants-for-property]-- --*/
        // _me.trigger('lookup-tenant-console');
        // return;

        _
          .post({
            url: _.url('<?= $this->route ?>'),
            data: {
              action: 'get-tenants-for-property',
              properties_id: _data.properties_id

            }

          })
          .then(d => {
            if ('ack' == d.response) {
              if (true) {
                // if (_cms_.currentUser.isDavid) {

                // if (!String.prototype.AsPhone) {
                //   String.prototype.AsPhone = function() {
                //     return this.isMobilePhone() ?
                //       this.AsMobilePhone() : this.AsLocalPhone();
                //   };

                // }

                // _me.trigger('lookup-tenant-console');
                let row = $('<div class="form-row"></div>');
                row.append('<div class="col-md-2 col-xl-1 text-truncate col-form-label pb-0" title="co tenants">tenants</div>');
                $('.card-body', this).prepend(row);

                let col = $('<div class="col"></div>').appendTo(row);
                let emails = [];
                let mobiles = [];
                $.each(d.tenants, (i, tenant) => {
                  // console.log(tenant);
                  let _row = $('<div class="form-row mb-2"></div>').appendTo(col);

                  let nc = $('<input type="text" readonly class="form-control">')
                    .val(tenant.name);
                  let ec = $('<input type="text" readonly class="form-control">')
                    .val(tenant.email);
                  let pc = $('<input type="text" readonly class="form-control">')
                    .val(String(tenant.phone).AsPhone());
                  // console.log(this);

                  if (_.browser.isMobileDevice) {
                    if (String(tenant.phone).IsMobilePhone()) {
                      let g = $('<div class="input-group"></div>');
                      g.append(pc);

                      let a = $('<a class="input-group-text"><i class="bi bi-chat-dots"></i></a>')
                      a.attr('href', 'sms://' + String(tenant.phone).replace(/[^0-9]/g, ''));
                      $('<div class="input-group-append"></div>')
                        .append(a)
                        .appendTo(g);

                      a = $('<a class="input-group-text"><i class="bi bi-telephone"></i></a>')
                      a.attr('href', 'tel://' + String(tenant.phone).replace(/[^0-9]/g, ''));
                      $('<div class="input-group-append"></div>')
                        .append(a)
                        .appendTo(g);

                      pc = g;

                    }

                    <?php
                    if ('yes' != currentUser::restriction('smoke-alarm')) {
                      // this part only runs if not a mobile device
                      print '} else {'; ?>

                      if (String(tenant.email).isEmail()) {
                        emails.push(_.email.rfc922({
                          name: tenant.name,
                          email: tenant.email
                        }));

                        let g = $('<div class="input-group"></div>');
                        g.append(ec);

                        $('<button type="button" class="btn input-group-text"><i class="bi bi-cursor"></i></button>')
                          .on('click', function(e) {
                            e.stopPropagation();

                            window.top._cms_.email.activate({
                              to: _.email.rfc922({
                                name: tenant.name,
                                email: tenant.email
                              }),
                              propertyID: _data.properties_id
                            });

                          })
                          .appendTo(
                            $('<div class="input-group-append"></div>')
                            .appendTo(g)

                          );

                        ec = g;

                      }

                      if (String(tenant.phone).IsMobilePhone()) {
                        mobiles.push(tenant.phone);

                        let g = $('<div class="input-group"></div>');
                        g.append(pc);

                        $('<button type=""button" class="btn input-group-text"><i class="bi bi-chat-dots"></i></button>')
                          .on('click', function(e) {
                            e.stopPropagation();
                            e.preventDefault();

                            _cms_.modal
                              .sms()
                              .then(m => {
                                $('form', m).trigger('add.recipient', tenant.phone);
                                return m;

                              });

                          })
                          .appendTo(
                            $('<div class="input-group-append"></div>')
                            .appendTo(g)

                          );

                        pc = g;

                      }

                    <?php } ?>

                  }

                  $('<div class="col-md-5 mb-1 mb-md-0"></div>').append(nc).appendTo(_row);
                  $('<div class="col-md-4 mb-1 mb-md-0"></div>').append(ec).appendTo(_row);
                  $('<div class="col-md-3 mb-1 mb-md-0"></div>').append(pc).appendTo(_row);

                });

                <?php if ('yes' != currentUser::restriction('smoke-alarm')) { ?>
                  let _row = $('<div class="form-row mb-2"></div>').appendTo(col);
                  $('<div class="col-md-5"></div>').appendTo(_row);
                  $('<div class="col-md-4 text-right"></div>')
                    .on('recon', function(e) {
                      if (emails.length > 0) {
                        $('<button class="btn btn-light" type="button"><i class="bi bi-cursor-fill"></i></button>')
                          .on('click', function(e) {
                            e.stopPropagation();

                            window.top._cms_.email.activate({
                              to: emails.join(','),
                              propertyID: _data.properties_id
                            });

                          })
                          .appendTo(this);

                      }

                    })
                    .trigger('recon')
                    .appendTo(_row);

                  $('<div class="col-md-3 mb-1 mb-md-0 text-right"></div>')
                    .on('recon', function(e) {
                      if (mobiles.length > 0) {
                        $('<button class="btn btn-light" type="button"><i class="bi bi-chat-dots-fill"></i></button>')
                          .on('click', function(e) {
                            e.stopPropagation();

                            _cms_.modal
                              .sms()
                              .then(m => {
                                $.each(mobiles, (i, mobile) => {
                                  $('form', m).trigger('add.recipient', mobile);

                                })
                                return m;

                              });

                          })
                          .appendTo(this);

                      }

                    })
                    .trigger('recon')
                    .appendTo(_row);
                <?php } ?>

                // } else {
                //   _me.trigger('lookup-tenant-console')

              }

              // } else {
              //   _me.trigger('lookup-tenant-console')

            }
          })
        /*-- --[get-tenants-for-property]-- --*/

      })
      .on('lookup-tenant-console', function(e) {
        let _me = $(this);
        let _data = _me.data();

        _.post({
          url: _.url('<?= $this->route ?>'),
          data: {
            action: 'get-tenant-of-property',
            properties_id: _data.properties_id

          },

        }).then(d => {
          if ('ack' == d.response) {
            /**-- [owner/tenants] --*/
            // console.log(d);

            let data = d.data;
            // console.log(data);
            /** tenant */
            let row = $('<div class="form-row"></div>');

            row.append('<div class="col-md-2 col-xl-1 text-truncate col-form-label pb-0" title="co tenants">tenants</div>');
            let col = $('<div class="col"></div>').appendTo(row);
            let _row = $('<div class="form-row mb-2"></div>').appendTo(col);

            let nc = $('<input type="text" readonly class="form-control bg-transparent">').val(data.Name);
            let ec = $('<input type="text" readonly class="form-control bg-transparent">').val(data.Email);
            let pc = $('<input type="text" readonly class="form-control bg-transparent">').val(String(data.Mobile).AsMobilePhone());
            if (_.browser.isMobileDevice) {
              if (String(data.Mobile).IsMobilePhone()) {
                let g = $('<div class="input-group"></div>');
                g.append(pc);

                let a = $('<a class="input-group-text"><i class="bi bi-chat-dots"></i></a>')
                a.attr('href', 'sms://' + String(data.Mobile).replace(/[^0-9]/g, ''));
                $('<div class="input-group-append"></div>')
                  .append(a)
                  .appendTo(g);

                a = $('<a class="input-group-text"><i class="bi bi-telephone"></i></a>')
                a.attr('href', 'tel://' + String(data.Mobile).replace(/[^0-9]/g, ''));
                $('<div class="input-group-append"></div>')
                  .append(a)
                  .appendTo(g);

                pc = g;

              }

            }

            $('<div class="col-md-5 mb-1 mb-md-0"></div>').append(nc).appendTo(_row);
            $('<div class="col-md-4 mb-1 mb-md-0"></div>').append(ec).appendTo(_row);
            $('<div class="col-md-3 mb-1 mb-md-0"></div>').append(pc).appendTo(_row);

            if (data.cotens.length > 0) {
              $.each(data.cotens, (i, o) => {
                // console.log( o);

                let _row = $('<div class="form-row mb-2"></div>').appendTo(col);

                let nc = $('<div class="form-control bg-transparent"></div>').html(o.name);
                let ec = $('<div class="form-control bg-transparent"></div>').html(o.Email);
                let pc = $('<div class="form-control bg-transparent"></div>').html(String(o.Mobile).AsMobilePhone());

                if (_.browser.isMobileDevice) {
                  if (String(o.Mobile).IsMobilePhone()) {
                    let g = $('<div class="input-group"></div>');
                    g.append(pc);

                    let a = $('<a class="input-group-text"><i class="bi bi-chat-dots"></i></a>')
                    a.attr('href', 'sms://' + String(o.Mobile).replace(/[^0-9]/g, ''));
                    $('<div class="input-group-append"></div>')
                      .append(a)
                      .appendTo(g);

                    a = $('<a class="input-group-text"><i class="bi bi-telephone"></i></a>')
                    a.attr('href', 'tel://' + String(o.Mobile).replace(/[^0-9]/g, ''));
                    $('<div class="input-group-append"></div>')
                      .append(a)
                      .appendTo(g);

                    pc = g;

                  }

                }

                $('<div class="col-md-5 mb-1 mb-md-0"></div>').append(nc).appendTo(_row);
                $('<div class="col-md-4 mb-1 mb-md-0"></div>').append(ec).appendTo(_row);
                $('<div class="col-md-3 mb-1 mb-md-0"></div>').append(pc).appendTo(_row);

              });

            }

            $('.card-body', this).prepend(row);
            /**-- [owner/tenants] --*/

          }

        });

      })
      .on('refresh-summary', function(e) {
        e.stopPropagation();

        let _me = $(this);
        let card = _me.closest('.card');
        if (card.length > 0) {
          // console.log( card[0]);
          let btn = $('button[data-properties_id]', card)
          if (btn.length > 0) {
            btn.trigger('refresh');

          }
          // let _data = _me.data();

        }

      })
      .on('reload', function(e) {
        e.stopPropagation();

        let _me = $(this);
        let _data = _me.data();
        // console.log( 'reload ....');

        let indicator = $('<div class="text-center"></div>');
        let sp = '<div class="spinner-grow spinner-grow-sm" role="status"><span class="sr-only">Loading...</span></div>&nbsp;';
        indicator
          .append(sp)
          .append(sp)
          .append(sp)
          .prependTo(_me);

        let url = _.url('<?= $this->route ?>/propertyalarms/' + _data.properties_id);
        $('> .card-body', this).html('').load(url, d => {

          indicator.remove();
          _me.trigger('lookup-tenant');

        });

      })
      .on('show.bs.collapse', function() {
        $(this).trigger('reload');

      });

    let srchidx = 0;
    $('#<?= $srch ?>')
      .on('keyup', function(e) {
        let idx = ++srchidx;
        let txt = this.value;

        if ('' == txt.trim()) {
          $('#<?= $_accordion ?> > .card.d-none').removeClass('d-none');

        } else {
          $('#<?= $_accordion ?> button[data-toggle="collapse"]').each((i, btn) => {
            if (idx != srchidx) return false;

            let _btn = $(btn);
            let _card = _btn.closest('.card');
            let _data = _btn.data();

            let str = _data.address;
            if (str.match(new RegExp(txt, 'gi'))) {
              _card.removeClass('d-none');

            } else {
              _card.addClass('d-none');

            }

          });

        }

      });

    $(document)
      .ready(() => {

        $('#<?= $_accordion ?> [data-toggle="popover"]').popover();
        $('#<?= $_spinner ?>').remove();
        $('#<?= $_wrapper ?>').removeClass('fade');
        $(document).trigger('smokealarm-stats', <?= json_encode($stats) ?>);
      });

  })(_brayworth_);
</script>