<!DOCTYPE html>
<html>
    <head>
        <title><?= $title ?></title>

        <link rel="stylesheet" href="<?= base_url('css/uikit.min.css') ?>" />
        <link rel="stylesheet" href="<?= base_url('themes.css') ?>" />
        <link href='//fonts.googleapis.com/css?family=Roboto|Open+Sans' rel='stylesheet' type='text/css'>
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">

    </head>
    <body>
    	<nav class="uk-navbar-container" uk-navbar>
			    <div class="uk-navbar-left">
	        		<a href="#" class="uk-navbar-item uk-logo">MY APPS</a>
	        	</div>

			    <div class="uk-navbar-center">
			        <ul class="uk-navbar-nav">
			        	<?php foreach($menu as $name => $router): ?>
			            	<?php if(is_array($router) && $router != ''): ?>
			            		<li>
				            		<a href=""><?= $name ?></a>
				            		<div class="uk-navbar-dropdown">
				            			<ul class="uk-nav uk-navbar-dropdown-nav">
				            				<?php foreach($router as $name1 => $router1): ?>
				            					<li><a href="<?= base_url($router1) ?>"><?= $name1 ?></a></li>
				            				<?php endforeach; ?>
				            			</ul>
				            		</div>
			            		</li>
			            	<?php elseif(!is_array($router)): ?>
			            		<li><a href="<?= base_url($router) ?>"><?= $name ?></a></li>
			            	<?php endif; ?>
			        	<?php endforeach; ?>
			        </ul>
			    </div>

			    <div class="uk-navbar-right">
			        <ul class="uk-navbar-nav">
			            <li><a href="apps/signout">Sign out</a></li>
			        </ul>
			    </div>
		</nav>

		<?php if(isset($showSubTitle) && $showSubTitle == true): ?>
			<?php if(isset($customSubTitle) && $customSubTitle != ""): ?>
				<div class="mini-title"><?php include($customSubTitle) ?></div>
			<?php else: ?>
				<div class="mini-title uk-text-center"><?= $module ?></div>
			<?php endif; ?>
		<?php endif; ?>

    	<div class="uk-container uk-container-center uk-margin-top uk-margin-large-bottom">
		<div class="uk-grid" data-uk-grid-margin>
		<div class="uk-width-medium-1-1">

		<?php 
			/* Message Board */
			if(isset($message)) {
				foreach($message as $key => $item) {
					//echo '<div class="uk-alert uk-alert-' . ($item['type'] == '' ? 'primary' : $item['type']) . '">';
					echo '<script>';
					echo 'alert("' . $item['message'] . '");';
					echo '</script>';
					//echo '</div>';
				}
			}
		 ?>







		 <!-- bottom -->
		 </div>
		</div>
		</div>

		<!-- beans script -->
		<script>
		<?php
			$beansPrint = print_r($beans, true);
			echo "var beans = " . json_encode($beans) . ";";
		?>
		</script>
		<!-- beans script -->

		<!-- depedency script -->
        <script src="<?= base_url('js/jquery.min.js') ?>"></script>
        <script src="<?= base_url('js/uikit.min.js') ?>"></script>
        <script src="<?= base_url('js/uikit-icons.min.js') ?>"></script>
		<script src="<?= base_url('js/application.js') ?>"></script>
		<!-- depedency script -->

    </body>
</html>