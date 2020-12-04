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

<table class="table table-sm" id="<?= $_table = strings::rand() ?>">
	<thead class="small">
    <tr>
      <td class="align-bottom text-center" line-number>#</td>
      <td class="align-bottom">location</td>
      <td class="align-bottom d-none d-md-table-cell">make</td>
      <td class="align-bottom d-none d-md-table-cell">model</td>
      <td class="align-bottom d-none d-md-table-cell">type</td>
      <td class="align-bottom d-none d-lg-table-cell">connect</td>
      <td class="align-bottom">expiry</td>
      <td class="align-bottom d-none d-md-table-cell text-center">status</td>

    </tr>

  </thead>

  <tbody>
  <?php
  $pid = -1;
  foreach ($this->data->dtoSet as $dto) {

    printf( '<tr
      data-id="%s"
      data-address="%s">',
      $dto->id,
      htmlentities( $dto->address_street)

    );

    print '<td class="text-center" line-number></td>';
    $bits = [$dto->location];
    $bits[] = sprintf('<div class="d-md-none">&nbsp;%s</div>', $dto->make);
    if ( $dto->model) $bits[] = sprintf('<div class="d-md-none">&nbsp;%s</div>', $dto->model);
    $bits[] = sprintf('<div class="d-md-none">&nbsp;%s</div>', $dto->type);

    printf( '<td>%s</td>', implode( $bits));
    printf( '<td class="d-none d-md-table-cell">%s</td>', $dto->make);
    printf( '<td class="d-none d-md-table-cell">%s</td>', $dto->model);
    printf( '<td class="d-none d-md-table-cell">%s</td>', $dto->type);
    printf( '<td class="d-none d-lg-table-cell">%s</td>', $dto->connect);
    printf( '<td>%s<div class="d-md-none">%s</div></td>', strings::asLocalDate( $dto->expiry), $dto->status);
    printf( '<td class="d-none d-md-table-cell text-center">%s</td>', $dto->status);

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

<div class="row">
  <div class="col position-relative mb-2">
    <textarea name="smokealarm_notes" class="form-control" data-version="0" data-checked="no"
      placeholder="notes ..."
      id="<?= $_notes = strings::rand()  ?>"><?= $this->data->notes ?></textarea>

    <button type="button" class="btn btn-primary rounded-circle position-absolute d-none" save style="right: 15px; top: -12px">
      <i class="fa fa-save"></i>

    </button>

  </div>

  <?php if ( $this->data->certificate) { ?>
    <div class="col-md-3">
    <?php
      $url = strings::url( sprintf(
        '%s/documentView/%d?d=%s',
        $this->route,
        $this->data->property->id,
        \urlencode( $this->data->certificate)

      ));

      if ( strings::endswith( $this->data->certificate, '.pdf')) {
        printf(
          '<a class="btn btn-light btn-block" target="_blank" href="%s"><i class="fa fa-file-pdf-o text-danger"></i> compliance cert</a>',
          $url

        );

      }
      else {
        printf(
          '<a target="_blank" href="%s"><img class="img-fluid" alt="compliance certificate" src="%s"></a>',
          $url,
          $url

        );

      }

    ?>
    </div>

  <?php } // if ( $this->data->hasCertificate) ?>

</div>

<script>
( _ => $(document).ready( () => {
  $('#<?= $_table ?>').on( 'add-smokealarm', e => {
    _.get.modal( _.url('<?= $this->route ?>/edit?pid=<?= $this->data->property->id ?>'))
    .then( m => m.on( 'success', e => $('#<?= $_table ?>').trigger( 'reload')));

  });

  $('#<?= $_table ?>')
  .on('reload', function(e) {
    let _me = $(this);
    let _container = _me.closest('.collapse');
    _container.trigger('reload');

  })
  .on('update-line-numbers', function(e) {
    let t = 0;
    $('> tbody > tr:not(.d-none) >td[line-number]', this).each( ( i, e) => {
      $(e).data('line', i+1).html( i+1);
      t++;

    });

    $('> thead > tr >td[line-number]', this).html( t);

  })
  .trigger('update-line-numbers');

  $('#<?= $addBtn ?>').on( 'click', e => $('#<?= $_table ?>').trigger( 'add-smokealarm'));

  let click = function(e) {
    e.stopPropagation(); e.preventDefault();

    $(this).trigger( 'edit');

  };

  let contextMenu = function( e) {
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

      _context.append( $('<a href="#"><i class="fa fa-archive"></i>archive</a>').on( 'click', function( e) {
        e.stopPropagation();e.preventDefault();

        _context.close();

        _tr.trigger( 'archive');

      }));

    }

    _context.open( e);

  };

  $('#<?= $_table ?> > tbody > tr').each( ( i, tr) => {

    $(tr)
    .addClass( 'pointer' )
    .on( 'archive', function( e) {
      let _tr = $(this);

      _.ask({
        headClass: 'text-white bg-danger',
        text: 'Are you sure ?',
        title: 'Confirm Archive',
        buttons : {
          yes : function(e) {
            $(this).modal('hide');
            _tr.trigger( 'archive-confirmed');

          }

        }

      });

    })
    .on( 'archive-confirmed', function(e) {
      let _tr = $(this);
      let _data = _tr.data();

      _.post({
        url : _.url('<?= $this->route ?>'),
        data : {
          action : 'archive-smokealarm',
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
      .then( modal => modal.on( 'success', e => $('#<?= $_table ?>').trigger( 'reload')));

    })
    .on( 'edit', function(e) {
      let _tr = $(this);
      let _data = _tr.data();

      _.get.modal( _.url('<?= $this->route ?>/edit/' + _data.id))
      .then( modal => modal.on( 'success', e => $('#<?= $_table ?>').trigger( 'reload')));
      console.error('edit ...');

    })
    .on( 'click', _.browser.isMobileDevice ? contextMenu : click)
    .on( 'contextmenu', contextMenu);

  });

  $('#<?= $_notes ?>')
  .autoResize()
  .on( 'keypress', function( e) {
    let _me = $(this);
    _me.trigger( 'changed');

  })
  .on( 'changed', function( e) {
    let _me = $(this);
    let _data = _me.data();
    if ( 'yes' == _data.changed) return;

    _me
    .data('version', Number(_data.version) +1)
    .data('changed', 'yes');
    _me.siblings('button[save]').removeClass('d-none');

  })
  .on( 'saved', function( e) {
    let _me = $(this);
    _me.siblings('button[save]').addClass('d-none');

  })
  .on( 'change', function( e) {
    let _me = $(this);
    let _data = _me.data();
    let version = _data.vesion;

    _me.data('changed', 'no');
    _.post({
      url : _.url('<?= $this->route ?>'),
      data : {
        action : 'save-notes',
        id : <?= (int)$this->data->property->id ?>,
        text : _me.val()

      },

    }).then( d => {
      if ( 'ack' == d.response) {
        let _data = _me.data();
        if ( version == _data.vesion) _me.trigger( 'saved');

      }
      else {
        _.growl( d);

      }

    });

  });

})) (_brayworth_);
</script>