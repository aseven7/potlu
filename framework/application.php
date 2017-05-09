<?php /* apps.php */

	function base_url($addon = null) {
		return Flight::get("flight.base_url") . (strstr($addon, "/") !== 0 ? "/" : "") . $addon;
	}

	function request() {
		return Flight::request();
	}

	function slug($text) {
		// replace non letter or digits by -
		$text = preg_replace('~[^\pL\d]+~u', '-', $text);
		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);
		// trim
		$text = trim($text, '-');
		// remove duplicate -
		$text = preg_replace('~-+~', '-', $text);
		// lowercase
		$text = strtolower($text);
		// return
		return $text;
	}

	function camelCase($str) {
		// non-alpha and non-numeric characters become spaces
        $str = preg_replace('/[^a-z0-9' . ']+/i', ' ', $str);
        $str = trim($str);
        // uppercase the first character of each word
        $str = ucwords($str);
        $str = str_replace(" ", "", $str);
        $str = lcfirst($str);
        return $str;
	}

	function create_router($name, $router) {
		$appConfig = include APP_PATH . '\\config\\app.php';

		Flight::route(($router == "." ? "" : "/") . ($router == '.' ? '' : $router), function() use($name) {

			$logicName = camelCase($name);
			$viewName = slug($name);
			$viewpath = APP_PATH . '\\views\\' . $viewName . '.php';
			$beansPath = APP_PATH . '\\beans\\' . $logicName . 'Beans.php';
			
			require_once APP_PATH . '\\logic\\baseLogic.php';
			include APP_PATH . '\\logic\\' . $logicName . 'Logic.php';

			$beans = file_exists($beansPath) ? include($beansPath) : [];

			$classname = $logicName.'Logic';
			$classname::applyBeans($beans);
			$classname::run();
			$classname::shareBeans();
			$classname::broadcast();
			$classname::assign('title', $name);
			$classname::assign('module', $name);

			if(file_exists($viewpath)) {
				Flight::render($viewName);
			}

		});
	}

	function loadStarter() {
		Flight::set('flight.views.path', APP_PATH . '\\views\\');
	}

	function loadLibrary() {
		$library = include(APP_PATH . '\\config\\library.php');
		foreach($library as $value) {
			include APP_PATH . '..\\lib\\' . $value;
		}
	}

	function loadHelper() {
		$library = include(APP_PATH . '/config/helper.php');
		foreach($library as $value) {
			include APP_PATH . '/' . $value;
		}
	}

	function loadMenu() {
		$menu = include(APP_PATH . '/config/menu.php');

		// loading menu list
		Flight::view()->set('menu', $menu);

		// loading flight routing
		foreach($menu as $name => $router) {
			if( is_array($router) ) {
				foreach($router as $name1 => $router1) {
					create_router($name1, $router1);
				}
			}else{
				create_router($name, $router);
			}
		}
	}

	function recordStamp($record, $exclude = []) {
		$listStamp = [
			'USR' => 'IT',
			'DATEADDED' => date('Y-m-d'),
			'DATEMODIFIED' => date('Y-m-d'),
		];

		foreach($listStamp as $key => $value) {
			if(in_array($key, $exclude)) {
				unset($listStamp[$key]);
			}else{
				$record->$key = $value;
			}
		}

		return $record;
	}

	function messageBoard($message) {
		foreach($message as $key => $item): ?>
	    <div class="alert alert-<?= isset($item['type']) && $item['type'] != '' ? $item['type'] : 'info' ?> alert-dismissible fade in" role="alert">
	      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
	      </button>
	      <p><?= $item['message'] ?></p>
	    </div>
	  <?php endforeach;
	}

	function getParam($name) {
		$value = null;
		$record = ORM::for_table('parameter')->where('NM', $name)->find_one();
		if($record) {
			$value = $record->VALUE;
		}
		return $value;
	}
	
	function appHeader() {
		include APP_PATH .'\\views\\_header.php';
	}

	function appFooter() {
		include APP_PATH .'\\views\\_footer.php';
	}