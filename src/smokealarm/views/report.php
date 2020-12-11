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
use strings;  ?>

<div class="form-group row d-print-none" id="<?= $srch = strings::rand() ?>envelope">
	<div class="col">
    <input type="search" class="form-control" autofocus id="<?= $srch ?>">

	</div>

</div>

<style media="screen">
@media screen and (max-width: 767px){
  div[data-role="content-primary"] {
    padding : 0 0 1.5rem 0!important;

  }

  #<?= $srch ?>envelope > .col { padding: .2rem 20px; }

}
</style>

<div class="accordion" id="<?= $_accordion = strings::rand() ?>">
  <div class="card">
    <div class="card-header p-0" id="<?= $_heading = strings::rand() ?>">
      <div class="btn-group d-flex">
        <button class="btn btn-secondary btn-sm flex-fill" type="button">
          <div class="row">
            <div class="col text-left text-truncate">address</div>
            <div class="col-3 text-left d-none d-lg-block">
              <div class="row">
                <div class="col text-truncate">company</div>
                <div class="col-7 text-truncate">last inspection</div>
              </div>
            </div>
            <div class="col-1 col-xl-3 text-left d-none d-lg-block">
              <div class="row">
                <div class="col d-none d-xl-block text-truncate">upgrade</div>
                <div class="col-3 d-none d-xl-block text-center text-truncate">w/o</div>
                <div class="col text-truncate">power</div>

              </div>

            </div>

            <div class="col-1 text-center d-flex">
              <div class="d-md-none">#</div>
              <div class="d-none d-md-block text-truncate">count</div>

            </div>

            <div class="col-2 col-md-1 text-center d-none d-md-block px-0 text-truncate">req 2022</div>
            <div class="col-2 col-md-1 text-center px-0 text-truncate"><?= strings::html_tick ?>&nbsp;<br class="d-md-none">2022</div>

          </div>

        </button>

        <button class="btn btn-secondary btn-sm flex-grow-0" type="button"><i class="fa fa-circle text-muted"></i></button>

      </div>

    </div>

  </div>

  <?php
  $items = [];
  $index = -1;
  $pid = -1;
  foreach ($this->data->dtoSet as $dto) {
    if ( $pid != $dto->properties_id) {
      $addr = [ strings::GoodStreetString( $dto->address_street)];
      if ( $dto->address_suburb) $addr[] = $dto->address_suburb;
      // if ( $dto->address_postcode) $addr[] = $dto->address_postcode;

      $items[] = (object)[
        'properties_id' => $pid = $dto->properties_id,
        'address' => implode( ' ', $addr),
        'people_id' => $dto->people_id,
        'people_name' => $dto->people_name,
        'smokealarms_power' => $dto->smokealarms_power,
        'smokealarms_required' => $dto->smokealarms_required,
        'smokealarms_2022_compliant' => $dto->smokealarms_2022_compliant,
        'smokealarms_company' => $dto->smokealarms_company,
        'smokealarms_last_inspection' => $dto->smokealarms_last_inspection,
        'smokealarms_tags' => $dto->smokealarms_tags,
        'smokealarms_na' => $dto->smokealarms_na,
        'smokealarms_upgrade_preference' => $dto->smokealarms_upgrade_preference,
        'smokealarms_workorder_sent' => $dto->smokealarms_workorder_sent,
        'alarms' => 0

      ];

      $index = count( $items) -1; // the last item

    }

    if ( $index > -1 && \in_array( $dto->status, config::smokealarm_status_compliant)) $items[$index]->alarms ++;

  }

  // \sys::dump( $this->data->dtoSet);
  // \sys::dump( $items);

  $dao = new dao\properties;
  foreach ( $items as $item) {
    $expired = $warning = false;
    if ( ( $et = \strtotime( $item->smokealarms_last_inspection)) > 0) {
      $etx = \strtotime( config::smokealarm_valid_time, $et);
      if ( date('Y-m-d', $etx) < date('Y-m-d')) {
        // \sys::logger( sprintf('<%s> %s', time() - $etx, __METHOD__));
        $expired = true;

      }
      else {
        $etx = \strtotime( config::smokealarm_warn_time, $et);
        if ( date('Y-m-d', $etx) < date('Y-m-d')) {
          $warning = true;

        }

      }

    }

    $btnClass = 'btn-light';
    if ( $expired) {
      $btnClass = 'btn-danger';

    }
    elseif ( $warning) {
      $btnClass = 'btn-warning';

    }
    ?>
    <div class="card">
      <div class="card-header p-0" id="<?= $_heading = strings::rand() ?>">
        <div class="d-flex">
          <button class="btn <?= $btnClass ?> btn-sm flex-fill" type="button"
            data-toggle="collapse"
            data-target="#<?= $_collapse = strings::rand() ?>"
            data-properties_id="<?= $item->properties_id ?>"
            data-address= "<?= htmlentities( $item->address) ?>""
            data-people_id="<?= $item->people_id ?>"
            data-people_name="<?= htmlentities( $item->people_name) ?>"
            data-na="<?= $item->smokealarms_na ? 'yes' : 'no' ?>"
            data-workorder_sent="<?= strtotime( $item->smokealarms_workorder_sent) > 0 ? 'yes' : 'no' ?>"
            aria-expanded="false" aria-controls="<?= $_collapse ?>">
            <?php
              $complianceClass = '';
              $complianceHtml = '';
              if ( 'yes' == $item->smokealarms_2022_compliant) {
                $complianceClass = 'text-success';
                $complianceHtml = strings::html_tick;

              }
              elseif ( $item->alarms < $item->smokealarms_required) {
                $complianceClass = 'text-danger';
                $complianceHtml = $item->alarms - $item->smokealarms_required;

              }

              $fakeProperty = (object)[
                'id' => $item->properties_id,
                'smokealarms_tags' => $item->smokealarms_tags

              ];

              $hasCert = $dao->hasSmokeAlarmComplianceCertificate( $fakeProperty);

              printf(
                '<div class="row">
                  <div class="col text-left text-truncate" address>%s</div>
                  <div class="col-3 text-left d-none d-lg-block">
                    <div class="row">
                      <div class="col text-truncate" company>%s</div>
                      <div class="col-6 text-truncate" last_inspection>%s</div>
                      <div class="col-1" certificate title="%s">%s</div>
                    </div>
                  </div>
                  <div class="col-1 col-xl-3 text-left d-none d-lg-block">
                    <div class="row">
                      <div class="col d-none d-xl-block text-truncate" upgrade-pref>%s</div>
                      <div class="col-3 d-none d-xl-block text-center" title="%s" work-order>%s</div>
                      <div class="col text-truncate">%s</div>

                    </div>

                  </div>
                  <div class="col-1 text-center" compliant>%s</div>
                  <div class="col-2 col-md-1 d-none d-md-block text-center" required>%s</div>
                  <div class="col-2 col-md-1 text-center %s" compliance>%s</div>
                </div>',
                $item->address,
                $item->smokealarms_company,
                strings::asLocalDate( $item->smokealarms_last_inspection),
                $hasCert ? 'has certificate' : 'no certificate',
                $hasCert ? strings::html_tick : '&nbsp;',
                $item->smokealarms_upgrade_preference,
                strings::asLocalDate( $item->smokealarms_workorder_sent),
                strtotime( $item->smokealarms_workorder_sent) > 0 ? strings::html_tick : '&nbsp;',
                $item->smokealarms_power,
                $item->alarms,
                $item->smokealarms_na ? 'N/A' : $item->smokealarms_required,
                $complianceClass,
                $complianceHtml

              );

            ?>

          </button>

          <button type="button" class="btn btn-sm <?= $btnClass ?> flex-grow-0" edit-property><i class="fa fa-pencil"></i></button>

        </div>

      </div>

      <div id="<?= $_collapse ?>" class="collapse"
        aria-labelledby="<?= $_heading ?>"
        data-parent="#<?= $_accordion ?>"
        data-properties_id="<?= $item->properties_id ?>">

        <div class="card-body px-md-3 py-1 py-md-2"></div>

      </div>

    </div class="card">

  <?php
  }  ?>

</div>
<script>
  ( _ => $(document).ready( () => {
    $('#<?= $_accordion ?> button[data-properties_id]')
    .on( 'edit', function( e) {
      let _me = $(this);
      let _data = _me.data();

      _.get.modal( _.url('<?= $this->route ?>/editproperty/' + _data.properties_id))
      .then( modal => modal.on( 'success', e => _me.trigger( 'refresh')));

    })
    .on( 'not-applicable', function( e) {
      let _me = $(this);
      let _data = _me.data();

      _.post({
        url : _.url('<?= $this->route ?>'),
        data : {
          action : 'mark-property-na',
          id : _data.properties_id,
          value : 'yes' == String( _data.na) ? 0 : 1

        },

      }).then( d => {
        if ( 'ack' == d.response) {
          _me.data( 'na', d.na);

          $('[required]', _me)
          .html( 'yes' == d.na ? 'N/A' : '-');

        }
        else {
          _.growl( d);

        }

      });


    })
    .on( 'refresh', function( e) {
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
        url : _.url('<?= $this->route ?>'),
        data : {
          action : 'get-property-by-id',
          id : _data.properties_id

        },

      }).then( d => {
        if ( 'ack' == d.response) {

          // console.log( d.dto);

          $('[address]', _me).html( d.address);
          $('[compliant]', _me).html( d.compliant);
          $('[required]', _me).html( d.dto.smokealarms_required);
          $('[power]', _me).html( d.dto.smokealarms_power);
          $('[company]', _me).html( d.dto.smokealarms_company);
          $('[upgrade-pref]', _me).html( d.dto.smokealarms_upgrade_preference);

          $('[work-order]', _me)
          .html( 'yes' == d.smokealarms_workorder_sent ? '<?= strings::html_tick ?>' : '&nbsp;')
          .attr( 'title', d.smokealarms_workorder_date);
          _me.data('workorder_sent', d.smokealarms_workorder_sent);

          $('[last_inspection]', _me).html( _.dayjs( d.dto.smokealarms_last_inspection).format('L'));

          if ( 'yes' == d.dto.smokealarms_2022_compliant) {
            $('[compliance]', _me)
            .removeClass( 'text-danger')
            .addClass( 'text-success')
            .html( '<?= strings::html_tick ?>');

          }
          else if ( Number( d.compliant) < Number( d.dto.smokealarms_required)) {
            $('[compliance]', _me)
            .removeClass( 'text-success')
            .addClass( 'text-danger')
            .html( Number( d.compliant) - Number( d.dto.smokealarms_required));

          }
          else {
            $('[compliance]', _me)
            .removeClass( 'text-success text-danger')
            .html( '&nbsp;' );

          }

          if ( 'yes' == d.hasSmokeAlarmComplianceCertificate) {
            $('[certificate]', _me)
            .attr('title', 'has certificate')
            .html( '<?= strings::html_tick ?>');

          }
          else {
            $('[certificate]', _me)
            .attr('title', 'no certificate')
            .html( '&nbsp;');

          }

          if ( d.dto.smokealarm_expired) {
            $( '> button', _me.parent()).removeClass( 'btn-warning').addClass('btn-danger');

          }
          else if ( d.dto.smokealarm_warning) {
            $( '> button', _me.parent()).removeClass( 'btn-danger').addClass('btn-warning');

          }
          else {
            $( '> button', _me.parent()).removeClass( 'btn-danger btn-warning');

          }
          // console.log( d);

        }
        else {
          _.growl( d);

        }

      });

    })
    .on( 'contextmenu', function( e) {
      if ( e.shiftKey)
        return;

      e.stopPropagation();e.preventDefault();

      _brayworth_.hideContexts();

      let _me = $(this);
      let _data = _me.data();
      let _context = _brayworth_.context();

      _context.append( $('<a href="#"><strong>Open/Close</strong></a>').on( 'click', function( e) {
        e.stopPropagation();e.preventDefault();

        _context.close();
        $( _data.target).collapse('toggle');

      }));

      _context.append( $('<a href="#">Edit Property</a>').on( 'click', function( e) {
        e.stopPropagation();e.preventDefault();

        _context.close();
        _me.trigger('edit');

      }));

      <?php if ( currentUser::isadmin()) {  ?>
        _context.append( $('<a href="#">Upgrade Preference</a>').on( 'click', function( e) {
          e.stopPropagation();e.preventDefault();

          _context.close();
          _me.trigger('upgrade-preferences');

        }));

        if ( 'yes' == _data.workorder_sent) {
          _context.append( $('<a href="#"><i class="fa fa-check"></i>Workorder Sent</a>').on( 'click', function( e) {
            e.stopPropagation();e.preventDefault();

            _context.close();
            _me.trigger('workorder-sent-clear');

          }));

        }
        else {
          _context.append( $('<a href="#">Workorder Sent</a>').on( 'click', function( e) {
            e.stopPropagation();e.preventDefault();

            _context.close();
            _me.trigger('workorder-sent');

          }));

        }

        let ctrl = $('<a href="#">Not Applicable</a>').on( 'click', function( e) {
          e.stopPropagation();e.preventDefault();

          _context.close();
          _me.trigger('not-applicable');

        });

        if ( 'yes' == String( _data.na)) ctrl.prepend( '<i class="fa fa-check"></i>');
        _context.append( ctrl);

      <?php } ?>

      _context.append(
        $('<a href="#">goto ' + _data.address + '</a>')
        .attr( 'href', _.url('property/view/' + _data.properties_id))
        .on( 'click', e => _context.close())

      );

      if ( Number(_data.people_id) > 0) {
        _context.append(
          $('<a href="#">goto ' + _data.people_name + '</a>')
          .attr( 'href', _.url('person/view/' + _data.people_id))
          .on( 'click', e => _context.close())

        );

      }

      _context.open( e);

    })
    .on( 'upgrade-preferences', function( e) {
      let _me = $(this);
      let _data = _me.data();

      _.get.modal( _.url('<?= $this->route ?>/editUpgradePreferences/' + _data.properties_id))
      .then( modal => modal.on( 'success', e => _me.trigger( 'refresh')));

    })
    .on( 'workorder-sent', function( e) {
      let _me = $(this);
      let _data = _me.data();

      _.post({
        url : _.url('<?= $this->route ?>'),
        data : {
          action : 'save-properties-workorder-sent',
          id : _data.properties_id
        },

      }).then( d => {
        if ( 'ack' == d.response) {
          _me.trigger( 'refresh');

        }
        else {
          _.growl( d);

        }

      });

    })
    .on( 'workorder-sent-clear', function( e) {
      let _me = $(this);
      let _data = _me.data();

      _.post({
        url : _.url('<?= $this->route ?>'),
        data : {
          action : 'save-properties-workorder-sent-clear',
          id : _data.properties_id
        },

      }).then( d => {
        if ( 'ack' == d.response) {
          _me.trigger( 'refresh');

        }
        else {
          _.growl( d);

        }

      });


    });

    $('#<?= $_accordion ?> button[edit-property]')
    .on( 'click', function( e) {
      e.stopPropagation();e.preventDefault();

      let _me = $(this);
      _me.siblings('button[data-properties_id]').trigger('edit');

    });

    $('#<?= $_accordion ?> > .card > .collapse')
    .on( 'lookup-tenant', function(e) {
      let _me = $(this);
      let _data = _me.data();

      _.post({
        url : _.url('<?= $this->route ?>'),
        data : {
          action : 'get-tenant-of-property',
          properties_id : _data.properties_id

        },

      }).then( d => {
        if ( 'ack' == d.response) {
          /**-- [owner/tenants] --*/
          console.log( d);

          ( data => {
            /** owner */
            let row = $('<div class="row"></div>');

            row.append('<div class="col-md-2 col-xl-1 text-truncate col-form-label" title=ckey">key</div>');
            let col = $('<div class="col"></div>').appendTo( row);
            let _row = $('<div class="form-group row"></div>').appendTo( col);

            let nc = $('<input type="text" readonly class="form-control bg-transparent">').val( data.Key);

            $('<div class="col-md-5 mb-1 mb-md-0"></div>').append( nc).appendTo( _row);

            $('.card-body', this).prepend( row);

          })( d.data);

          ( data => {
            /** owner */
            let row = $('<div class="row"></div>');

            row.append('<div class="col-md-2 col-xl-1 text-truncate col-form-label pb-0" title="owner">owner</div>');
            let col = $('<div class="col"></div>').appendTo( row);
            let _row = $('<div class="form-group row"></div>').appendTo( col);

            let nc = $('<input type="text" readonly class="form-control bg-transparent">').val( data.OwnerName);
            let ec = $('<input type="text" readonly class="form-control bg-transparent">').val( data.OwnerEmail);
            let pc = $('<input type="text" readonly class="form-control bg-transparent">').val( String( data.OwnerMobile).AsMobilePhone());
            if ( _brayworth_.browser.isMobileDevice) {
              if ( String( data.OwnerMobile).IsMobilePhone()) {
                let g = $('<div class="input-group"></div>');
                g.append( pc);

                let a = $( '<a class="input-group-text"><i class="fa fa-commenting-o"></i></a>')
                a.attr( 'href', 'sms://' + String( data.OwnerMobile).replace( /[^0-9]/g,''));
                $( '<div class="input-group-append"></div>')
                .append( a)
                .appendTo( g);

                a = $( '<a class="input-group-text"><i class="fa fa-phone"></i></a>')
                a.attr( 'href', 'tel://' + String( data.OwnerMobile).replace( /[^0-9]/g,''));
                $( '<div class="input-group-append"></div>')
                .append( a)
                .appendTo( g);

                pc = g;

              }

            }

            $('<div class="col-md-5 mb-1 mb-md-0"></div>').append( nc).appendTo( _row);
            $('<div class="col-md-4 mb-1 mb-md-0"></div>').append( ec).appendTo( _row);
            $('<div class="col-md-3 mb-1 mb-md-0"></div>').append( pc).appendTo( _row);

            $('.card-body', this).prepend( row);

          })( d.data);

          ( data => {
            /** tenant */
            let row = $('<div class="row"></div>');

            row.append('<div class="col-md-2 col-xl-1 text-truncate col-form-label pb-0" title="co tenants">tenants</div>');
            let col = $('<div class="col"></div>').appendTo( row);
            let _row = $('<div class="form-group row"></div>').appendTo( col);

            let nc = $('<input type="text" readonly class="form-control bg-transparent">').val( data.Name);
            let ec = $('<input type="text" readonly class="form-control bg-transparent">').val( data.Email);
            let pc = $('<input type="text" readonly class="form-control bg-transparent">').val( String( data.Mobile).AsMobilePhone());
            if ( _brayworth_.browser.isMobileDevice) {
              if ( String( data.Mobile).IsMobilePhone()) {
                let g = $('<div class="input-group"></div>');
                g.append( pc);

                let a = $( '<a class="input-group-text"><i class="fa fa-commenting-o"></i></a>')
                a.attr( 'href', 'sms://' + String( data.Mobile).replace( /[^0-9]/g,''));
                $( '<div class="input-group-append"></div>')
                .append( a)
                .appendTo( g);

                a = $( '<a class="input-group-text"><i class="fa fa-phone"></i></a>')
                a.attr( 'href', 'tel://' + String( data.Mobile).replace( /[^0-9]/g,''));
                $( '<div class="input-group-append"></div>')
                .append( a)
                .appendTo( g);

                pc = g;

              }

            }

            $('<div class="col-md-5 mb-1 mb-md-0"></div>').append( nc).appendTo( _row);
            $('<div class="col-md-4 mb-1 mb-md-0"></div>').append( ec).appendTo( _row);
            $('<div class="col-md-3 mb-1 mb-md-0"></div>').append( pc).appendTo( _row);

            if ( data.cotens.length >0) {
              $.each( data.cotens, ( i, o) => {
                //~ console.log( o);

                let _row = $('<div class="form-group row"></div>').appendTo( col);

                let nc = $('<div class="form-control bg-transparent"></div>').html( o.name);
                let ec = $('<div class="form-control bg-transparent"></div>').html( o.Email);
                let pc = $('<div class="form-control bg-transparent"></div>').html( String( o.Mobile).AsMobilePhone());

                if ( _brayworth_.browser.isMobileDevice) {
                  if ( String( o.Mobile).IsMobilePhone()) {
                    let g = $('<div class="input-group"></div>');
                    g.append( pc);

                    let a = $( '<a class="input-group-text"><i class="fa fa-commenting-o"></i></a>')
                    a.attr( 'href', 'sms://' + String( o.Mobile).replace( /[^0-9]/g,''));
                    $( '<div class="input-group-append"></div>')
                    .append( a)
                    .appendTo( g);

                    a = $( '<a class="input-group-text"><i class="fa fa-phone"></i></a>')
                    a.attr( 'href', 'tel://' + String( o.Mobile).replace( /[^0-9]/g,''));
                    $( '<div class="input-group-append"></div>')
                    .append( a)
                    .appendTo( g);

                    pc = g;

                  }

                }

                $('<div class="col-md-5 mb-1 mb-md-0"></div>').append( nc).appendTo( _row);
                $('<div class="col-md-4 mb-1 mb-md-0"></div>').append( ec).appendTo( _row);
                $('<div class="col-md-3 mb-1 mb-md-0"></div>').append( pc).appendTo( _row);

              });

            }

            $('.card-body', this).prepend( row);

          })( d.data);
          /**-- [owner/tenants] --*/

        }

      });

    })
    .on('reload', function(e) {
      e.stopPropagation();

      let _me = $(this);
      let _data = _me.data();

      let indicator = $('<div class="text-center"></div>');
      let sp = '<div class="spinner-grow spinner-grow-sm" role="status"><span class="sr-only">Loading...</span></div>&nbsp;';
      indicator
      .append( sp)
      .append( sp)
      .append( sp)
      .prependTo( _me);

      let url = _.url( '<?= $this->route ?>/propertyalarms/' + _data.properties_id);
      $('> .card-body', this).html('').load( url, d => {

        indicator.remove();
        _me.trigger('lookup-tenant');

      });

    })
    .on('show.bs.collapse', function() {
      $(this).trigger('reload');

    });

    let srchidx = 0;
    $('#<?= $srch ?>').on( 'keyup', function( e) {
      let idx = ++srchidx;
      let txt = this.value;

      if ( '' == txt.trim()) {
        $('#<?= $_accordion ?> > .card.d-none').removeClass( 'd-none');

      }
      else {
        $('#<?= $_accordion ?> button[data-toggle="collapse"]').each( ( i, btn) => {
          if ( idx != srchidx) return false;

          let _btn = $(btn);
          let _card = _btn.closest('.card');
          let _data = _btn.data();

          let str = _data.address;
          if ( str.match( new RegExp(txt, 'gi'))) {
            _card.removeClass( 'd-none');

          }
          else {
            _card.addClass( 'd-none');

          }

        });

      }

    });

  }))( _brayworth_);
</script>
