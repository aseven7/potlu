<?php
	/* view helper */
	class ViewHelper {
		public static function render($content = null) {
			include 'views/_header.php';
			$content();
			include 'views/_footer.php';
		}
	}