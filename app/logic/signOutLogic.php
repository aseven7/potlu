<?php
	class signOutLogic extends baseLogic {
		public function run() {
			parent::load();
			RedirectHelper::back();
		}
	}