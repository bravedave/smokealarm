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

<div class="accordion" id="<?= $_accordion = strings::rand() ?>">
  <div class="card">
    <div class="card-header p-0" id="<?= $_heading = strings::rand() ?>">
      <div class="btn-group d-flex">
        <button class="btn btn-secondary btn-block" type="button">
          <div class="row">
            <div class="col text-left">address</div>
            <div class="col-3 text-left">power</div>
            <div class="col-1 text-center">req</div>
            <div class="col-1 text-center"><?= strings::html_tick ?></div>

          </div>

        </button>

        <button class="btn btn-secondary" type="button"><i class="fa fa-circle text-muted"></i></button>

      </div>

    </div>

  </div>

  <?php
  $pid = -1;
  foreach ($this->data->dtoSet as $dto) {
    if ( $pid != $dto->properties_id) {
      $pid = $dto->properties_id; ?>

      <div class="card">
        <div class="card-header p-0" id="<?= $_heading = strings::rand() ?>">
          <h2 class="mb-0 d-flex">
            <button class="btn btn-light btn-block" type="button"
              data-toggle="collapse"
              data-target="#<?= $_collapse = strings::rand() ?>"
              data-property_id="<?= $dto->properties_id ?>"
              aria-expanded="false" aria-controls="<?= $_collapse ?>">
              <?php
                $addr = [$dto->address_street];
                if ( $dto->address_suburb) $addr[] = $dto->address_suburb;
                if ( $dto->address_postcode) $addr[] = $dto->address_postcode;

                printf(
                  '<div class="row">
                    <div class="col text-left" address>%s</div>
                    <div class="col-3 text-left" power>%s</div>
                    <div class="col-1 text-center" required>%s</div>
                    <div class="col-1 text-center %s" compliance>%s</div>
                  </div>',
                  implode( ' ', $addr),
                  $dto->smokealarms_power,
                  $dto->smokealarms_required,
                  'yes' == $dto->smokealarms_2022_compliant ? 'text-success' : '',
                  'yes' == $dto->smokealarms_2022_compliant ? strings::html_tick : '&nbsp;'

                );

              ?>

            </button>

            <button type="button" class="btn btn-light" edit-property><i class="fa fa-pencil"></i></button>

          </h2>

        </div>

        <div id="<?= $_collapse ?>" class="collapse"
          aria-labelledby="<?= $_heading ?>"
          data-parent="#<?= $_accordion ?>"
          data-property_id="<?= $dto->properties_id ?>">

          <div class="card-body"></div>

        </div>

      </div class="card">

    <?php
    }

  } ?>

</div>
<script>
  ( _ => {
    $(document).ready( () => {
      $('#<?= $_accordion ?> button[data-property_id]')
      .on( 'edit', function( e) {
        let _me = $(this);
        let _data = _me.data();

        _.get.modal( _.url('<?= $this->route ?>/editproperty/' + _data.property_id))
        .then( modal => modal.on( 'success', e => _me.trigger( 'refresh')));

      })
      .on( 'refresh', function( e) {
        let _me = $(this);
        let _data = _me.data();

        _.post({
          url : _.url('<?= $this->route ?>'),
          data : {
            action : 'get-property-by-id',
            id : _data.property_id

          },

        }).then( d => {
          if ( 'ack' == d.response) {
            console.log( d);

            $('[address]', _me).html( d.dto.address_street);
            $('[required]', _me).html( d.dto.smokealarms_required);
            $('[power]', _me).html( d.dto.smokealarms_power);
            $('[compliance]', _me).html( 'yes' == d.dto.smokealarms_2022_compliant ? '<?= strings::html_tick ?>' : '&nbsp;' );

            if ( 'yes' == d.dto.smokealarms_2022_compliant) {
              $('[compliance]', _me).addClass( 'text-success');

            }
            else {
              $('[compliance]', _me).removeClass( 'text-success');

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
        _me.siblings('button[data-property_id]').trigger('edit');

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

        let url = _.url( '<?= $this->route ?>/propertyalarms/' + _data.property_id);
        $('.card-body', this).load( url, d => indicator.remove());

      })
      .on('show.bs.collapse', function() {
        $(this).trigger('reload');

      });

    });

  }) (_brayworth_);
</script>
