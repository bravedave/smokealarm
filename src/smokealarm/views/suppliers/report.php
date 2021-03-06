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
use strings;   ?>

<table class="table table-sm" id="<?= $_table = strings::rand() ?>">
	<thead class="small">
    <tr>
      <td line-number>#</td>
      <td>name</td>
      <td>contact</td>
      <td>phone</td>
      <td>email</td>
    </tr>
  </thead>

  <tbody>
  <?php
  foreach ($this->data->dtoSet as $dto) {

    printf( '<tr data-id="%s">', $dto->id);

    print '<td line-number></td>';
    printf( '<td>%s</td>', $dto->name);
    printf( '<td>%s</td>', $dto->contact);
    printf( '<td>%s</td>', $dto->phone);
    printf( '<td>%s</td>', $dto->email);

    print '</tr>';

  } ?>
  </tbody>

	<?php if ( !(int)currentUser::restriction( 'smokealarm-company')) {	?>
		<tfoot class="d-print-none">
			<tr>
				<td colspan="5" class="text-right">
					<button type="button" class="btn btn-outline-secondary" id="<?= $addBtn = strings::rand() ?>"><i class="bi bi-plus bi-2x"></i></a>

				</td>

			</tr>

		</tfoot>

	<?php	} ?>

</table>
<script>
( _ => $(document).ready( () => {
	$(document).on( 'add-smokealarm-supplier', e => {
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

	<?php if ( !(int)currentUser::restriction( 'smokealarm-company')) {	?>
		$('#<?= $addBtn ?>').on( 'click', e => { $(document).trigger( 'add-smokealarm-supplier'); });
	<?php }	// if ( !(int)currentUser::restriction( 'smokealarm-company'))	?>

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
					action : 'delete-smokealarm-supplier',
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

			_context.append( $('<a href="#"><i class="bi bi-trash"></i>delete</a>').on( 'click', function( e) {
				e.stopPropagation();e.preventDefault();

				_context.close();

				_tr.trigger( 'delete');

			}));

			_context.open( e);

		});

	});

})) (_brayworth_);
</script>
