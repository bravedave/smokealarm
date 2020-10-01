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
      <td>type</td>
    </tr>
  </thead>

  <tbody>
  <?php
  foreach ($this->data->dtoSet as $dto) {

    printf( '<tr data-id="%s">', $dto->id);

    print '<td line-number></td>';
    printf( '<td>%s</td>', $dto->type);

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
  $(document).ready( () => {
    $(document).on( 'add-smokealarm-type', e => {
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

    console.log( 'aa');

    $('#<?= $addBtn ?>').on( 'click', e => { $(document).trigger( 'add-smokealarm-type'); });

    $('#<?= $_table ?> > tbody > tr').each( ( i, tr) => {

      $(tr)
      .addClass( 'pointer' )
      .on( 'edit', function(e) {
        let _tr = $(this);
        let _data = _tr.data();

        _.get.modal( _.url('<?= $this->route ?>/edit/' + _data.id))
        .then( modal => modal.on( 'success', e => window.location.reload()));

      })
      .on( 'click', function(e) {
        e.stopPropagation(); e.preventDefault();

        $(this).trigger( 'edit');

      });

    });

  });

}) (_brayworth_);
</script>
