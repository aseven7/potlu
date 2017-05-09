<?php
	class baseLogic {
		public static $db;
		public static $view;
		public static $beans;
		public static $defaultBeans;
		public static $message = [];

		public static function load() {
			self::$db = ORM::get_db();
			self::$view = Flight::view();
			self::assign('db', self::$db);
			self::loadMessage();
		}

		public static function loadMessage() {
			// apply message from session store
			if(isset($_SESSION['message'])) {
				$sessionMessages = $_SESSION['message'];
				self::$message = $sessionMessages;
				self::assign('message', self::$message);

				unset($_SESSION['message']);
			}
		}

		public static function assign($key, $value) {
			self::$view->set($key, $value);
		}

		public static function post($key) {
			if(!array_key_exists($key, $_POST)) return null;
			return $_POST[$key];
		}

		public static function get($key) {
			if(!array_key_exists($key, $_GET)) return null;
			return $_GET[$key];
		}

		public static function input($key) {
			if(!array_key_exists($key, $_REQUEST)) return null;
			return $_REQUEST[$key];
		}

		/* message board handling */
		public static function message($message, $type = '') {
			self::$message[] = ['message' => $message, 'type' => $type];
		}

		public static function hasMessage() {
			return count(self::$message) > 0;
		}

		public static function broadcast() {
			self::assign('message', self::$message);
		}
		/* message board handling */

		public static function featureInlineEdit($tableName = "", $keyColumn = "") {
			$key = self::post('KEY');
			$column = self::post('COLUMN');
			$value = self::post('VALUE');

			$record = ORM::for_table($tableName)->where($keyColumn, $key)->find_one();
			$record->$column = $value;
			$record->save();
		}

		/* beans handling */
		public static function beans() {
			return self::$beans;
		}

		public static function clearBeans() {
			$defaultBeans = self::$defaultBeans;

			foreach(self::$beans as $key => $value) {
				if( array_key_exists($key, $defaultBeans) ) {
					self::$beans[$key] = $defaultBeans[$key];
				}else{
					self::$beans[$key] = null;
				}
			}
		}

		public static function applyBeans($beansArray) {
			// set default beans
			self::$defaultBeans = $beansArray;

			// apply default beans, also
			$posts = $_POST;
			foreach($posts as $key => $value) {
				if(array_key_exists($key, $beansArray)) {
					$beansArray[$key] = $value;
				}
			}

			// apply beans from session store
			if(isset($_SESSION['beans'])) {
				$sessionBeans = $_SESSION['beans'];
				foreach($sessionBeans as $key => $value) {
					if(array_key_exists($key, $beansArray)) {
						$beansArray[$key] = $value;
					}
				}

				unset($_SESSION['beans']);
			}

			// apply logic beans
			self::$beans = $beansArray;
		}

		public static function setBeans($key, $value) {
			self::$beans[$key] = $value;
		}
		public static function getBeans($key) {
			return array_key_exists($key, self::$beans) ? self::$beans[$key] : null;
		}
		public static function shareBeans() {
			self::assign('beans', self::$beans);
		}
		public static function storeBeans() {
			$_SESSION['beans'] = self::$beans;
		}
		public static function storeMessage() {
			$_SESSION['message'] = self::$message;
		}
		public static function presistance() {
			// store beans
			self::storeBeans();
			// store message
			self::storeMessage();
		}
		/* beans handling */
	}