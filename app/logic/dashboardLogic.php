<?php
	class dashboardLogic extends baseLogic {
		public static function run() {
			parent::load();

			self::assign('title', 'Dashboard');
			self::assign('showSubTitle', true);

			$customSubtitleDashboard = 'dashboard-sub-title.php';
			self::assign('customSubTitle', $customSubtitleDashboard);
		}
	}