<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
 * styleguide : https://codeguide.co/
*/  ?>
<table class="table table-sm" id="<?= $_table = strings::rand() ?>">
	<thead class="small">
    <tr>
      <td line-number>#</td>
      <td>address</td>
      <td>type</td>
      <td>location</td>
      <td>make</td>
      <td>model</td>
      <td>expiry</td>
      <td>status</td>
      <td>power</td>
    </tr>
  </thead>

  <tbody>
  <?php
  foreach ($this->data->dtoSet as $dto) {

    printf( '<tr data-id="%s">', $dto->id);

    print '<td line-number></td>';
    printf( '<td>%s</td>', $dto->address_street);
    printf( '<td>%s</td>', $dto->type);
    printf( '<td>%s</td>', $dto->location);
    printf( '<td>%s</td>', $dto->make);
    printf( '<td>%s</td>', $dto->model);
    printf( '<td>%s</td>', strings::asLocalDate( $dto->expiry));
    printf( '<td>%s</td>', $dto->status);
    printf( '<td>%s</td>', $dto->power);

    print '</tr>';

  } ?>
  </tbody>

	<tfoot class="d-print-none">
		<tr>
			<td colspan="9" class="text-right">
				<button type="button" class="btn btn-outline-secondary" id="<?= $addBtn = strings::rand() ?>"><i class="fa fa-plus"></i></a>

			</td>

		</tr>

	</tfoot>

</table>
<script>
( _ => {
  if ('undefined' == typeof _.search)
      _.search = {};

  if ('undefined' == typeof _.search.address) {
      _.search.address = (request, response) => {
          _.post({
              url: window.location.href,
              data: {
                  action: 'search-properties',
                  term: request.term

              },

          }).then(d => response('ack' == d.response ? d.data : []));

      };

  }

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

        _.get.modal( _.url('<?= $this->route ?>/edit/' + _data.id))
        .then( modal => modal.on( 'success', e => window.location.reload()));

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

				_context.open( e);

			});

    });

  });

}) (_brayworth_);
</script>
