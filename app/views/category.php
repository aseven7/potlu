<?php require_once( APP_PATH . '\\views\\_header.php' ) ?>

<div class="x_content">
<?php if(isset($disableMessage) && !$disableMessage) : ?>
  <?php messageBoard($message) ?>
<?php elseif($beans['showOnBoard']): ?>
  <?php messageBoard($message) ?>
<?php endif; ?>
</div>

<div class="x_panel">
  <div class="x_content">
    <form method="post" class="form">
      <div class="btn-group">
        <a class="btn btn-primary" data-toggle="modal" href='#registermodal'>Register</a>
        <button id="deleteBulk" type="submit" name="delete_bulk" class="btn btn-danger" >Remove Selected</button>
      </div>
      <div class="clearfix"></div>
      <br>
      <div class="table-responsive">

          <div class="pull-right">
            <div>Total record : <?= count($datagrid) ?> row (s)</div>
          </div>
          <div class="clearfix"></div>
          
          <table class="table table-striped jambo_table bulk_action">
            <thead>
              <tr class="headings">
                <th style="width:1%">
                  <input type="checkbox" id="check-all" class="flat" >
                </th>
                <th class="column-title">Name </th>
                <th class="column-title">Description </th>
                <th style="width:10%" class="column-title no-link last"><span class="nobr">Action</span>
                </th>
                <th class="bulk-actions" colspan="4">
                  <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($datagrid as $key => $row) : ?>
              <tr>
                  <td class="a-center ">
                    <input type="checkbox" class="flat" name="table_records" value="<?= $row['UID'] ?>">
                  </td>
                  <td>
                    <a class="inline-edit" data-key="<?= $row['UID'] ?>" data-value="<?= $row['NMCATGRY'] ?>" data-column="NMCATGRY" href="#"><?= $row['NMCATGRY'] ?></a>
                  </td>
                  <td>
                    <a class="inline-edit" data-key="<?= $row['UID'] ?>" data-value="<?= $row['DESCRIPTION'] ?>" data-column="DESCRIPTION" href="#"><?= $row['DESCRIPTION'] ?></a>
                  </td>
                  <td>
                    <div class="btn-group">
                      <button type="submit" name="update" value="<?= $row['UID'] ?>" class="btn-xs btn btn-primary"><span class="fa fa-pencil"></span></button>
                      <button onclick="return confirm('Are you sure to remove selected record ?')" type="submit" name="delete" value="<?= $row['UID'] ?>" class="btn-xs btn btn-danger"><span class="fa fa-trash-o"></span></button>
                    </div>
                  </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="registermodal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="" method="post" data-parsley-validate class="form form-horizontal form-label-left">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Register <?= $module ?></h4>
        </div>
        <div class="modal-body">
          <?php if(!$beans['showOnBoard']) messageBoard($message) ?>
          <input type="hidden" name="UID">
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Name <span class="required">*</span>
            </label>
            <div class="col-md-9 col-sm-9 col-xs-12">
            <input name="NMCATGRY" type="text"  class="form-control col-md-7 col-xs-12">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Description
            </label>
            <div class="col-md-9 col-sm-9 col-xs-12">
            <textarea name="DESCRIPTION" rows="5" class="form-control col-md-7 col-xs-12"></textarea> 
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
          <button type="submit" name="register" value="submit" class="btn btn-primary"><span class="fa fa-check"></span> Register</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php function hook_load_footer_script() { ?>
<script>
  $(function(){
    if( beans.TYPEBEANS != null ) {
      if( beans.TYPEBEANS == 'RECOVERY' ) {
        $('#registermodal').modal('show');
      }
    }
    
    $('#registermodal').on('hide.bs.modal', function(e) {
      window.location.href = window.location.href;
    });

    $('#deleteBulk').on('click', function(e){
      e.preventDefault();
      var form = $(this).parents('form').eq(0);
      var delete_bulk_target = new Array();
      $('input[name="table_records"]:checked').each(function() {
        var checked = $(this).val();
        delete_bulk_target.push(checked);
      });

      if(delete_bulk_target.length > 0 && confirm('Are you sure to remove selected record (s) ?')) {
        form.append('<input name="delete_bulk" type="hidden" value="' + delete_bulk_target.join(',') + '" />');
        form.submit();
      }

    });
  })
</script>
<?php } ?>
<?php require_once( APP_PATH . '\\views\\_footer.php' ) ?>