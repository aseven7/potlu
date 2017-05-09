                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
          <div class="pull-right">
            PotluFramework.
          </div>
          <div class="clearfix"></div>
        </footer>
        <div class="clearfix"></div>
        <!-- /footer content -->
      </div>
    </div>
    
    <!-- template purpose -->
    <script id="inlineEditForm" type="text/template">
      <input type="hidden" name="KEY" value="<%= KEY %>" />
      <input type="hidden" name="COLUMN" value="<%= COLUMN %>" />
      <span class="input-group">
        <input class="form-control" type="text" name="VALUE" value="<%= VALUE %>" />
        <span class="input-group-btn">
          <button type="submit" name="inline_edit" value="<%= KEY %>" class="btn btn-md btn-primary"><span class="fa fa-check"></span></button>
          <button type="button" id="inlineFormCloseButton" class="btn btn-md btn-danger"><span class="fa fa-times"></span></button>
        </span>
      </span>
    </script>
    <!-- template purpose -->

    <!-- beans script -->
    <script>
    <?php
      if(isset($beans)) {
        $beansPrint = print_r($beans, true);
        echo "var beans = " . json_encode($beans) . ";"; 
      }
    ?>
    </script>
    <!-- beans script -->

    <?php if( function_exists('hook_load_footer') ): ?>
      <?php hook_load_footer(); ?>
    <?php endif; ?>

    <!-- jQuery -->
    <script src="<?= base_url('js/jquery.min.js') ?>"></script>
    <!-- Bootstrap -->
    <script src="<?= base_url('js/bootstrap.min.js') ?>"></script>
    <!-- FastClick -->
    <script src="<?= base_url('js/fastclick.js') ?>"></script>
    <!-- NProgress -->
    <script src="<?= base_url('js/nprogress.js') ?>"></script>
    <!-- Custom Theme Scripts -->
    <script src="<?= base_url('js/admin.min.js') ?>"></script>
    <!-- NumeralJS Scripts -->
    <script src="<?= base_url('js/numeral.min.js') ?>"></script>
    <!-- iCheck -->
    <script src="<?= base_url('js/iCheck/icheck.min.js') ?>"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="<?= base_url('js/moment/moment.min.js') ?>"></script>
    <script src="<?= base_url('js/bootstrap-daterangepicker/daterangepicker.js') ?>"></script>
    <!-- EJS -->
    <script src="<?= base_url('js/underscore.min.js') ?>"></script>
    <script src="<?= base_url('js/bootstrap3-typeahead.min.js') ?>"></script>
    <!-- Appliction Scripts -->
    <script src="<?= base_url('js/application.js') ?>"></script>


    <?php if( function_exists('hook_load_footer_script') ): ?>
      <?php hook_load_footer_script(); ?>
    <?php endif; ?>

	
  </body>
</html>