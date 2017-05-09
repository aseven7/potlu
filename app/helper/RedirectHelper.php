<?php
	class RedirectHelper {
		public static function back() {
			$backRoute = request()->referrer;
			Flight::redirect($backRoute);
		}
		
		public static function to($route) {
			Flight::redirect($route);
		}
	}