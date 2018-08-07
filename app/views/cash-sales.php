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
                <th class="column-title">Cash Order No. </th>
                <th class="column-title">Sales Date </th>
                <th class="column-title">Memo </th>
                <th class="column-title">Total </th>
                <th style="width:10%" class="column-title no-link last"><span class="nobr">Action</span>
                </th>
                <th class="bulk-actions" colspan="4">
                  <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($datagrid as $key => $row) :
              	$detail = ORM::for_table('sales_transaction_detail')->raw_query("SELECT sum(QTY * PRICE - DISCOUNT) as TOTALSLS FROM sales_transaction_detail WHERE PUID = :puid", ['puid' => $row['UID']])->find_one();
              	$totalSales = 0;

              	if($detail->TOTALSLS) $totalSales = $detail->TOTALSLS;
              ?>
              <tr>
                  <td class="a-center ">
                    <input type="checkbox" class="flat" name="table_records" value="<?= $row['UID'] ?>">
                  </td>
                  <td><?= $row['TRANSNO'] ?></td>
                  <td><?= $row['TRANSDATE'] ?></td>
                  <td><?= $row['MEMO'] ?></td>
                  <td>Rp. <?= number_format($totalSales, 0) ?></td>
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
          <fieldset>
          	<legend>Cash Sales</legend>
	          <div class="form-group">
	            <label class="control-label col-md-2 col-sm-2 col-xs-12">ORDER NO
	            </label>
	            <div class="col-md-10 col-sm-10 col-xs-12">
	            <input name="TRANSNO" type="text" class="form-control col-md-7 col-xs-12" readonly="true">
	            </div>
	          </div>
	          <div class="form-group">
	            <label class="control-label col-md-2 col-sm-2 col-xs-12">ORDER DATE
	            </label>
	            <div class="col-md-10 col-sm-10 col-xs-12">
		            <div class="controls">
		                <input type="text" class="calendarpicker form-control has-feedback-left" name="TRANSDATE">
		                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
		                <span id="inputSuccess2Status4" class="sr-only">(success)</span>
		            </div>
	            </div>
	          </div>
	          <div class="form-group">
	            <label class="control-label col-md-2 col-sm-2 col-xs-12">Memo
	            </label>
	            <div class="col-md-10 col-sm-10 col-xs-12">
	            <textarea name="MEMO" rows="5" class="form-control col-md-7 col-xs-12"></textarea> 
	            </div>
	          </div>
          </fieldset>
          <fieldset>
          	<legend>Cash Sales Item</legend>
          	<div class="table-responsive">

	          <table class="table table-striped jambo_table bulk_action">
	            <thead>
	              <tr class="headings">
	                <th class="column-title" style="width:40%">Product </th>
	                <th class="column-title">Price </th>
	                <th class="column-title">Qty </th>
	                <th class="column-title">Discount </th>
	                <th class="column-title">Total </th>
	                <th style="width:10%" class="column-title no-link last"><span class="nobr">Action</span>
	                </th>
	                <th class="bulk-actions">
	                  <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
	                </th>
	              </tr>
	            </thead>
	            <tbody>
	              <?php foreach($dataProduct as $key => $row) : ?>
	              	<tr>
		              	<td>
		              		<input readonly="true" name="PRODNM[]" class="form-control" type="text" data-provide="typeahead" value="<?= $row['PRODNM'] ?>">
	              			<input name="PROD[]" type="hidden" value="<?= $row['PROD'] ?>">
		              	</td>
		              	<td><input readonly="true" name="PRICE[]" class="text-right form-control" type="text" value="<?= $row['PRICE'] ?>"></td>
		              	<td><input readonly="true" name="QTY[]" class="text-right form-control" type="text" value="<?= $row['QTY'] ?>"></td>
		              	<td><input readonly="true" name="DISCOUNT[]" class="text-right form-control" type="text" value="<?= $row['DISCOUNT'] ?>"></td>
		              	<td><input readonly="true" name="TOTAL[]" class="form-control text-right" type="text" value="<?= $row['TOTAL'] ?>"></td>
		              	<td>
	              			<button type="submit" name="deleteRowItem" value="<?= $key ?>" class="btn btn-md btn-danger"><span class="fa fa-trash-o"></span></button>
		              	</td>
	              	</tr>
	              <?php endforeach; ?>
	              <tr>
	              	<td>
	              		<input autocomplete="off" name="PRODNM[]" class="form-control active" type="text" data-provide="typeahead">
	              		<input name="PROD[]" type="hidden">
	              	</td>
	              	<td><input name="PRICE[]" class="text-right form-control" type="text" value="0"></td>
	              	<td><input name="QTY[]" class="text-right form-control" type="text" value="0"></td>
	              	<td><input name="DISCOUNT[]" class="text-right form-control" type="text" value="0"></td>
	              	<td><input name="TOTAL[]" class="text-right form-control" type="text" value="0"></td>
	              	<td>
	              		<button type="submit" name="addMoreItem" value="1" class="btn btn-md btn-primary"><span class="fa fa-plus"></span></button>
	              	</td>
	              </tr>
	            </tbody>

              <tfoot>
                <tr>
                  <td colspan="2">Grand Total</td>
                  <td class="text-right"><span id="totalQuantity"></span></td>
                  <td class="text-right"><span id="totalDiscount"></span></td>
                  <td class="text-right"><span id="totalPrice"></span></td>
                  <td></td>
                </tr>
              </tfoot>
	          </table>
          	</div>

          <div class="clearfix"></div>
        </div>
        <div class="modal-footer">

          <div class="pull-left text-left">
            <?php if(isset($beans['DATEMODIFIED'])): ?>
              <div><span class="fa fa-calendar"></span> Date Modified : <?= $beans['DATEMODIFIED'] ?></div>
              <div><span class="fa fa-calendar"></span> Date Added : <?= $beans['DATEADDED'] ?></div>
              <input type="hidden" name="DATEADDED">
              <input type="hidden" name="DATEMODIFIED">
            <?php endif; ?>
          </div>

          <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
          <button type="submit" name="register" value="1" class="btn btn-primary"><span class="fa fa-check"></span> Register</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php function hook_load_footer_script() { ?>

<script id="itemRow" type="text/template">
	<tr>
		<td></td>
	</tr>
</script>

<script>
  $(function(){
    var calculateTotal = function() {
      var totalQuantity = 0;
      var totalDiscount = 0;
      var totalPrice = 0;

      $('input[name="QTY[]"]').each(function() {
        var quantity = parseInt($(this).val());
        totalQuantity += quantity;
      });
      $('input[name="DISCOUNT[]"]').each(function() {
        var discount = parseInt($(this).val());
        totalDiscount += discount;
      });
      $('input[name="TOTAL[]"]').each(function() {
        var price = $(this).val();
        price = numeral(price).value();
        totalPrice += price;
      });

      $('#totalQuantity').text(numeral(totalQuantity).format('#,#'));
      $('#totalDiscount').text(numeral(totalDiscount).format("#,#"));
      $('#totalPrice').text(numeral(totalPrice).format("#,#"));
    };

    if( beans.TYPEBEANS != null ) {
      if( beans.TYPEBEANS == 'RECOVERY' ) {
        $('#registermodal').modal('show');
      }
    }

    $.get("<?= base_url('services?action=product') ?>", function(data){
      var $productInput = $('input[name="PRODNM[]"].active');

      $productInput.typeahead({ 
      items: 10,
      source:data
      });

      $productInput.change(function() {
        var current = $productInput.typeahead("getActive");
        var $me = $(this);
        var $currentRow = $me.parents('tr').eq(0);

        if (current) {
          if (current.name == $me.val()) {
          	$currentRow.find('input[name="PRICE[]"]').val(current.price);
          	$currentRow.find('input[name="QTY[]"]').val(1);
          	$currentRow.find('input[name="PROD[]"]').val(current.UID);
          } 
        } else {
        // Nothing is active so it is a new value (or maybe empty value)
        }
      });
	},'json');

	$('.calendarpicker').daterangepicker({
	  singleDatePicker: true,
	  singleClasses: "picker_4",
	  locale: {
	  	format: 'YYYY-MM-DD'
	  }
	}, function(start, end, label) {
	  console.log(start.toISOString(), end.toISOString(), label);
	});

    
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

    $('input[name="PRICE[]"], input[name="QTY[]"], input[name="DISCOUNT[]"]').on('change', function(e) {
    	var $tr = $(this).parents('tr').eq(0);
    	var price = $tr.find('input[name="PRICE[]"]').eq(0).val();
    	var qty = $tr.find('input[name="QTY[]"]').eq(0).val();
    	var discount = $tr.find('input[name="DISCOUNT[]"]').eq(0).val();
    	var $total = $tr.find('input[name="TOTAL[]"]').eq(0);
    	var total = 0;

    	price = parseInt(price);
    	qty = parseInt(qty);
    	discount = parseInt(discount);

    	total = price * qty - discount;

    	$total.val(numeral(total).format("#,#"));

      calculateTotal();
    });

    $('input[name="PRICE[]"]').change();

  })
</script>
<?php } ?>
<?php require_once( APP_PATH . '\\views\\_footer.php' ) ?>