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
                <th class="column-title">SKU </th>
                <th class="column-title">Code </th>
                <th class="column-title">Name Product </th>
                <th class="column-title">Stock </th>
                <th style="width:20%" class="column-title">Selling Price </th>
                <th style="width:10%" class="column-title no-link last"><span class="nobr">Action</span>
                </th>
                <th class="bulk-actions" colspan="6">
                  <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($datagrid as $key => $row) : 
                $stock = TransactionHelper::GetStockProduct($row['UID']);
              ?>
              <tr>
                  <td class="a-center ">
                    <input type="checkbox" class="flat" name="table_records" value="<?= $row['UID'] ?>">
                  </td>
                  <td>
                  	<a class="inline-edit" data-key="<?= $row['UID'] ?>" data-value="<?= $row['SKU'] ?>" data-column="SKU" href="#"><?= $row['SKU'] ?></a>
                  </td>
                  <td>
                  	<a class="inline-edit" data-key="<?= $row['UID'] ?>" data-value="<?= $row['CDPROD'] ?>" data-column="CDPROD" href="#"><?= $row['CDPROD'] ?></a>
                  </td>
                  <td>
                  	<a class="inline-edit" data-key="<?= $row['UID'] ?>" data-value="<?= $row['NMPROD'] ?>" data-column="NMPROD" href="#"><?= $row['NMPROD'] ?></a>
                  </td>
                  <td class="text-right"><?= number_format($stock, 0) ?></td>
                  <td class="text-right">
                  	<a class="inline-edit" data-key="<?= $row['UID'] ?>" data-value="<?= $row['SELLPRC'] ?>" data-column="SELLPRC" href="#">Rp. <?= number_format($row['SELLPRC'], 0) ?></a>
                  </td>
                  <td>
                    <div class="btn-group">
                      <button type="submit" name="update" value="<?= $row['UID'] ?>" class="btn-xs btn btn-primary"><span class="fa fa-pencil"></span></button>
                      <button onclick="return confirm('Are you sure to remove selected record ?')" type="submit" name="delete" value="<?= $row['UID'] ?>" class="btn-xs btn btn-danger"><span class="fa fa-trash-o"></span></button>
                      <button type="submit" name="duplicate" value="<?= $row['UID'] ?>" class="btn-xs btn btn-success"><span class="fa fa-copy"></span></button>
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
          <fieldset role="fieldset">
          	<legend>Product Information</legend>
	          <div class="form-group">
	            <label class="control-label col-md-2 col-sm-2 col-xs-12" >SKU
	            </label>
	            <div class="col-md-10 col-sm-10 col-xs-12">
	            <input name="SKU" type="text" class="form-control col-md-7 col-xs-12">
	            </div>
	          </div>
	          <div class="form-group">
	            <label class="control-label col-md-2 col-sm-2 col-xs-12" >Code<span class="required">*</span>
	            </label>
	            <div class="col-md-10 col-sm-10 col-xs-12">
	            <input name="CDPROD" type="text" class="form-control col-md-7 col-xs-12">
	            </div>
	          </div>
	          <div class="form-group">
	            <label class="control-label col-md-2 col-sm-2 col-xs-12" >Name <span class="required">*</span>
	            </label>
	            <div class="col-md-10 col-sm-10 col-xs-12">
	            <input name="NMPROD" type="text" class="form-control col-md-7 col-xs-12">
	            </div>
	          </div>
	          <div class="form-group">
	            <label class="control-label col-md-2 col-sm-2 col-xs-12" >Warehouse
	            </label>
	            <div class="col-md-10 col-sm-10 col-xs-12">
	            <select name="WAREHS" class="form-control col-md-7 col-xs-12">
	            	<?php foreach($dataWarehouse as $key => $value): ?>
	            		<option value="<?= $value->UID ?>"><?= $value->NM . ', ' . $value->CITY ?></option>
	            	<?php endforeach; ?>
	            </select>
	            </div>
	          </div>
	          <div class="form-group">
	            <label class="control-label col-md-2 col-sm-2 col-xs-12" >Category
	            </label>
	            <div class="col-md-10 col-sm-10 col-xs-12">
	            <select name="CATGRY" class="form-control col-md-7 col-xs-12">
	            	<?php foreach($dataCategory as $key => $value): ?>
	            		<option value="<?= $value->UID ?>"><?= $value->NMCATGRY ?></option>
	            	<?php endforeach; ?>
	            </select>
	            </div>
	          </div>
	          <div class="form-group">
	            <label class="control-label col-md-2 col-sm-2 col-xs-12" >Brand
	            </label>
	            <div class="col-md-10 col-sm-10 col-xs-12">
	            <select name="BRAND" class="form-control col-md-7 col-xs-12">
	            	<?php foreach($dataBrand as $key => $value): ?>
	            		<option value="<?= $value->UID ?>"><?= $value->NMBRAND ?></option>
	            	<?php endforeach; ?>
	            </select>
	            </div>
	          </div>
	          <div class="form-group">
	            <label class="control-label col-md-2 col-sm-2 col-xs-12" >Status
	            </label>
	            <div class="col-md-10 col-sm-10 col-xs-12">
	            <select name="STATUS" class="form-control col-md-7 col-xs-12">
	            	<option value="0">Not Active</option>
	            	<option value="1">Active</option>
	            </select>
	            </div>
	          </div>
	          <div class="form-group">
	            <label class="control-label col-md-2 col-sm-2 col-xs-12">Description
	            </label>
	            <div class="col-md-10 col-sm-10 col-xs-12">
	            <textarea name="DESCRIPTION" rows="5" class="form-control col-md-7 col-xs-12"></textarea> 
	            </div>
	          </div>
	          <div class="form-group">
	            <label class="control-label col-md-2 col-sm-2 col-xs-12">Base price
	            </label>
	            <div class="col-md-10 col-sm-10 col-xs-12">
	            <input name="BASEPRC" type="text" class="text-right form-control col-md-7 col-xs-12">
	            </div>
	          </div>
	          <div class="form-group">
	            <label class="control-label col-md-2 col-sm-2 col-xs-12">Selling price
	            </label>
	            <div class="col-md-10 col-sm-10 col-xs-12">
	            <input name="SELLPRC" type="text" class="text-right form-control col-md-7 col-xs-12">
	            </div>
	          </div>
	          <?php if($beans['UID']): ?>
	          <div class="form-group">
	            <label class="control-label col-md-2 col-sm-2 col-xs-12">Margin
	            </label>
	            <div class="col-md-10 col-sm-10 col-xs-12">
	            <input name="MARGIN" readonly="true" type="text" class="text-right form-control col-md-7 col-xs-12">
	            </div>
	          </div>
	          <div class="form-group">
	            <label class="control-label col-md-2 col-sm-2 col-xs-12">Margin (%)
	            </label>
	            <div class="col-md-10 col-sm-10 col-xs-12">
	            <input name="MARGINPRS" readonly="true" type="text" class="text-right form-control col-md-7 col-xs-12">
	            </div>
	          </div>
	      	<?php endif; ?>
          </fieldset>

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