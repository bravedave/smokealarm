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

use strings; ?>

<div class="form-group row d-print-none" id="<?= $srch = strings::rand() ?>envelope">
	<div class="col">
    <input type="search" class="form-control" autofocus id="<?= $srch ?>" />

	</div>

</div>

<table class="table table-sm" id="<?= $_table = strings::rand() ?>">
	<thead class="small">
    <tr>
      <td class="align-bottom text-center" line-number>#</td>
      <td class="align-bottom">location</td>
      <td class="align-bottom">make</td>
      <td class="align-bottom">model</td>
      <td class="align-bottom">type</td>
      <td class="align-bottom">expiry</td>
      <td class="align-bottom text-center" title="2022 Compliant & alarm status">
        2022<br>
        status

      </td>

    </tr>

  </thead>

  <tbody>
  <?php
  $pid = -1;
  foreach ($this->data->dtoSet as $dto) {
    if ( $pid != $dto->properties_id) {
      $pid = $dto->properties_id;
      printf( '<tr data-property_id="%s">', $dto->properties_id);

      $addr = [$dto->address_street];
      if ( $dto->address_suburb) $addr[] = $dto->address_suburb;
      if ( $dto->address_postcode) $addr[] = $dto->address_postcode;
      printf(
        '<td colspan="7"><div class="row">
          <div class="col">%s</div>
          <div class="col-sm-3">power : %s<div>
        </div></td>',
        implode( ' ', $addr),
        $dto->smokealarms_power

      );

      printf(
        '<td class="text-center %s">%s</td>',
        $dto->smokealarms_2022_compliant ? 'text-success' : '',
        $dto->smokealarms_2022_compliant ? strings::html_tick : '&nbsp;'

      );

      print '</tr>';

    }

    printf( '<tr
      data-id="%s"
      data-address="%s">',
      $dto->id,
      htmlentities( $dto->address_street)

    );

    print '<td class="text-center" line-number></td>';
    printf( '<td>%s</td>', $dto->location);
    printf( '<td>%s</td>', $dto->make);
    printf( '<td>%s</td>', $dto->model);
    printf( '<td>%s</td>', $dto->type);
    printf( '<td>%s</td>', strings::asLocalDate( $dto->expiry));
    printf( '<td class="text-center">%s</td>', $dto->status);

    print '</tr>';

  } ?>
  </tbody>

	<tfoot class="d-print-none">
		<tr>
			<td colspan="8" class="text-right">
				<button type="button" class="btn btn-outline-secondary" id="<?= $addBtn = strings::rand() ?>"><i class="fa fa-plus"></i></a>

			</td>

		</tr>

	</tfoot>

</table>
<script>
( _ => {
  $(document).ready( () => {
    $(document).on( 'add-smokealarm', e => {
      _.get.modal( _.url('<?= $this->route ?>/edit'))
      .then( m => m.on( 'success', e => window.location.reload()));

    });

    $('#<?= $_table ?>')
    .on('update-line-numbers', function(e) {
      let t = 0;
      $('> tbody > tr:not(.d-none) >td[line-number]', this).each( ( i, e) => {
        $(e).data('line', i+1).html( i+1);
        t++;

      });

      $('> thead > tr >td[line-number]', this).html( t);

    })
		.trigger('update-line-numbers');

    $('#<?= $addBtn ?>').on( 'click', e => { $(document).trigger( 'add-smokealarm'); });

    $('#<?= $_table ?> > tbody > tr').each( ( i, tr) => {

      $(tr)
      .addClass( 'pointer' )
			.on( 'delete', function( e) {
				let _tr = $(this);

				_.ask({
					headClass: 'text-white bg-danger',
					text: 'Are you sure ?',
					title: 'Confirm Delete',
					buttons : {
						yes : function(e) {
							$(this).modal('hide');
							_tr.trigger( 'delete-confirmed');

						}

					}

				});

			})
			.on( 'delete-confirmed', function(e) {
				let _tr = $(this);
				let _data = _tr.data();

				_.post({
					url : _.url('<?= $this->route ?>'),
					data : {
						action : 'delete-smokealarm',
						id : _data.id

					},

				}).then( d => {
					if ( 'ack' == d.response) {
						_tr.remove();
						$('#<?= $_table ?>').trigger('update-line-numbers');

					}
					else {
						_.growl( d);

					}

				});

      })
      .on( 'copy', function(e) {
        let _tr = $(this);
        let _data = _tr.data();

        _.get.modal( _.url('<?= $this->route ?>/edit/' + _data.id + '/copy'))
        .then( modal => modal.on( 'success', e => window.location.reload()));

      })
      .on( 'edit', function(e) {
        let _tr = $(this);
        let _data = _tr.data();

        if ( !!_data.id) {
          _.get.modal( _.url('<?= $this->route ?>/edit/' + _data.id))
          .then( modal => modal.on( 'success', e => window.location.reload()));

        }
        else if ( !!_data.property_id) {
          _.get.modal( _.url('<?= $this->route ?>/editproperty/' + _data.property_id))
          .then( modal => modal.on( 'success', e => window.location.reload()));

        }

      })
      .on( 'click', function(e) {
        e.stopPropagation(); e.preventDefault();

        $(this).trigger( 'edit');

      })
			.on( 'contextmenu', function( e) {
				if ( e.shiftKey)
					return;

				e.stopPropagation();e.preventDefault();

				let _tr = $(this);
				let _data = _tr.data();

				_.hideContexts();
				let _context = _.context();

				_context.append( $('<a href="#"><b>edit</b></a>').on( 'click', function( e) {
          e.stopPropagation();e.preventDefault();

					_context.close();

					_tr.trigger( 'edit');

				}));

        if ( !!_data.id) {
          _context.append( $('<a href="#"><i class="fa fa-copy"></i>copy</a>').on( 'click', function( e) {
            e.stopPropagation();e.preventDefault();

            _context.close();

            _tr.trigger( 'copy');

          }));

          _context.append( $('<a href="#"><i class="fa fa-trash"></i>delete</a>').on( 'click', function( e) {
            e.stopPropagation();e.preventDefault();

            _context.close();

            _tr.trigger( 'delete');

          }));

        }

				_context.open( e);

			});

    });

    let srchidx = 0;
    $('#<?= $srch ?>').on( 'keyup', function( e) {
      let idx = ++srchidx;
      let txt = this.value;

      let _tbl = $('#<?= $_table ?>');
      let _tbl_data = _tbl.data();

      $('#<?= $_table ?> > tbody > tr').each( ( i, tr) => {
        if ( idx != srchidx) return false;

        let _tr = $(tr);
        let _data = _tr.data();
        let properties_id = Number( _data.properties_id);

        if ( '' == txt.trim()) {
          _tr.removeClass( 'd-none');

        }
        else {
          let str = _data.address;
          if ( str.match( new RegExp(txt, 'gi'))) {
            _tr.removeClass( 'd-none');

          }
          else {
            str = _tr.text();
            if ( str.match( new RegExp(txt, 'gi'))) {
              _tr.removeClass( 'd-none');

            }
            else {
              _tr.addClass( 'd-none');

            }

          }

        }

      });

      $('#<?= $_table ?>').trigger( 'update-line-numbers');

    });

  });

}) (_brayworth_);
</script>
