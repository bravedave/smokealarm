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

use strings;  ?>

<style media="screen">
@media screen and (max-width: 767px){
  div[data-role="content-primary"] {
    padding : 0 0 1.5rem 0!important;

  }

}
</style>
<div class="accordion" id="<?= $_accordion = strings::rand() ?>">
  <div class="card">
    <div class="card-header p-0" id="<?= $_heading = strings::rand() ?>">
      <div class="btn-group d-flex">
        <button class="btn btn-secondary btn-block" type="button">
          <div class="row">
            <div class="col text-left align-self-end">address</div>
            <div class="col-3 text-left d-none d-md-table-column align-self-end">power</div>
            <div class="col-1 text-center d-flex">
              <div class="d-md-none align-self-end">#</div>
              <div class="d-none d-md-block align-self-end">count</div>

            </div>

            <div class="col-2 col-md-1 text-center px-0 align-self-end">req 2022</div>
            <div class="col-2 col-md-1 text-center px-0 align-self-end"><?= strings::html_tick ?>&nbsp;<br class="d-md-none">2022</div>

          </div>

        </button>

        <button class="btn btn-secondary" type="button"><i class="fa fa-circle text-muted"></i></button>

      </div>

    </div>

  </div>

  <?php
  $items = [];
  $index = -1;
  $pid = -1;
  foreach ($this->data->dtoSet as $dto) {
    if ( $pid != $dto->properties_id) {
      $addr = [$dto->address_street];
      if ( $dto->address_suburb) $addr[] = $dto->address_suburb;
      if ( $dto->address_postcode) $addr[] = $dto->address_postcode;

      $items[] = (object)[
        'properties_id' => $pid = $dto->properties_id,
        'address' => implode( ' ', $addr),
        'people_id' => $dto->people_id,
        'people_name' => $dto->people_name,
        'smokealarms_power' => $dto->smokealarms_power,
        'smokealarms_required' => $dto->smokealarms_required,
        'smokealarms_2022_compliant' => $dto->smokealarms_2022_compliant,
        'alarms' => 0

      ];

      $index = count( $items) -1; // the last item

    }

    if ( $index > -1 && 'compliant' == $dto->status) $items[$index]->alarms ++;

  }

  // \sys::dump( $this->data->dtoSet);
  // \sys::dump( $items);

  foreach ( $items as $item) {  ?>
    <div class="card">
      <div class="card-header p-0" id="<?= $_heading = strings::rand() ?>">
        <h2 class="mb-0 d-flex">
          <button class="btn btn-light btn-block" type="button"
            data-toggle="collapse"
            data-target="#<?= $_collapse = strings::rand() ?>"
            data-properties_id="<?= $item->properties_id ?>"
            data-address= "<?= htmlentities( $item->address) ?>""
            data-people_id="<?= $item->people_id ?>"
            data-people_name="<?= htmlentities( $item->people_name) ?>"
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

              printf(
                '<div class="row">
                  <div class="col text-left text-truncate" address>%s</div>
                  <div class="col-3 text-left d-none d-md-table-column" power>%s</div>
                  <div class="col-1 text-center" compliant>%s</div>
                  <div class="col-2 col-md-1 text-center" required>%s</div>
                  <div class="col-2 col-md-1 text-center %s" compliance>%s</div>
                </div>',
                $item->address,
                $item->smokealarms_power,
                $item->alarms,
                $item->smokealarms_required,
                $complianceClass,
                $complianceHtml

              );

            ?>

          </button>

          <button type="button" class="btn btn-light" edit-property><i class="fa fa-pencil"></i></button>

        </h2>

      </div>

      <div id="<?= $_collapse ?>" class="collapse"
        aria-labelledby="<?= $_heading ?>"
        data-parent="#<?= $_accordion ?>"
        data-properties_id="<?= $item->properties_id ?>">

        <div class="card-body px-0 px-md-3 py-1 py-md-2"></div>

      </div>

    </div class="card">

  <?php
  }  ?>

</div>
<script>
  ( _ => {
    $(document).ready( () => {
      $('#<?= $_accordion ?> button[data-properties_id]')
      .on( 'edit', function( e) {
        let _me = $(this);
        let _data = _me.data();

        _.get.modal( _.url('<?= $this->route ?>/editproperty/' + _data.properties_id))
        .then( modal => modal.on( 'success', e => _me.trigger( 'refresh')));

      })
      .on( 'refresh', function( e) {
        let _me = $(this);
        let _data = _me.data();

        _.post({
          url : _.url('<?= $this->route ?>'),
          data : {
            action : 'get-property-by-id',
            id : _data.properties_id

          },

        }).then( d => {
          if ( 'ack' == d.response) {
            // console.log( d);

            $('[address]', _me).html( d.dto.address_street);
            $('[compliant]', _me).html( d.compliant);
            $('[required]', _me).html( d.dto.smokealarms_required);
            $('[power]', _me).html( d.dto.smokealarms_power);

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

      $('#<?= $_accordion ?> button[data-toggle="collapse"]')
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

      });

      $('#<?= $_accordion ?> > .card > .collapse')
      .on('reload', function() {
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
        $('.card-body', this).load( url, d => indicator.remove());

      })
      .on('show.bs.collapse', function() {
        $(this).trigger('reload');

      });

    });

  }) (_brayworth_);
</script>
