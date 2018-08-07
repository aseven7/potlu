<?php
	class signOutLogic extends baseLogic {
		public static function run() {
			parent::load();
			RedirectHelper::back();
		}
	}