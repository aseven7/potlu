<?php
	class warehouseLogic extends baseLogic {
		public static function run() {
			// setup ORM
			ORM::configure('id_column', 'UID');

			// load setting
			parent::load();

			// set title
			self::assign('title', 'Warehouse');

			// datagrid
			$datagrid = ORM::for_table('warehouse')->find_many();
			self::assign('datagrid', $datagrid);

			// conditional request
			if(self::post('delete_bulk')) {
				$delete_bulk = self::post('delete_bulk');
				$uidArray = explode(',', $delete_bulk);
				self::delete_bulk($uidArray);
			}else if(self::post('delete')) {
				$uid = self::post('delete');
				self::delete($uid);
			}else if(self::post('set_default')) {
				self::setDefault();

				self::setBeans('showOnBoard', 1);
				self::message("Set default warehouse done..");
				self::presistance();
				RedirectHelper::back();
			}else if(self::post('register')) {
				self::register();
			}else{
				self::initial();
			}
		}

		public static function initial() {
			if(self::post('update')) {
				$uid = self::post('update');
				$record = ORM::for_table('warehouse')->find_one($uid);

				// set update value
				self::setBeans('UID', $record->UID);
				self::setBeans('NM', $record->NM);
				self::setBeans('LOCATE', $record->LOCATE);
				self::setBeans('CITY', $record->CITY);
				self::setBeans('DESCRIPTION', $record->DESCRIPTION);

				self::setBeans('TYPEBEANS', 'RECOVERY');
			}
		}

		public static function setDefault($uid = null) {
			if($uid == null) {
				$uid = self::post('set_default');
			}

			$records = ORM::for_table('warehouse')->find_many();
			foreach($records as $record) {
				$record->ISDEFAULT = $record->UID == $uid;
				$record->save();
			}
		}

		public static function register() {

			// validation
			self::validation();

			// update or insert
			$record = ORM::for_table('warehouse')->create();
			
			// check uid not null
			if(self::getBeans('UID') != null) {
				$uid = self::getBeans('UID');
				$record = ORM::for_table('warehouse')->find_one($uid);
			}
			
			// set record value
			$record->NM = self::getBeans('NM');
			$record->CITY = self::getBeans('CITY');
			$record->LOCATE = self::getBeans('LOCATE');
			$record->DESCRIPTION = self::getBeans('DESCRIPTION');
			$record->ISDEFAULT = self::getBeans('ISDEFAULT');

			// check error message
			if(!self::hasMessage()) {
				$record = recordStamp($record);
				$record->save();

				self::setDefault($record->UID);
				
				// redirect back
				if(self::getBeans('UID') == null) {
					self::clearBeans();
					self::setBeans('showOnBoard', true);
				}else{
					self::setBeans('TYPEBEANS', 'RECOVERY');
				}

				self::message("Register Warehouse information done..");

				self::presistance();
				RedirectHelper::back();
			}else{
				self::setBeans('TYPEBEANS', 'RECOVERY');
			}
		}

		public static function delete($uid) {
			ORM::for_table('warehouse')->find_one($uid)->delete();

			self::setBeans('showOnBoard', true);
			self::message("Record deleted..");
			self::presistance();
			RedirectHelper::back();
		}

		public static function delete_bulk($uidArray) {
			ORM::for_table('warehouse')->where_in('UID', $uidArray)->delete_many();
			
			self::setBeans('showOnBoard', true);
			self::message("Record deleted..");
			self::presistance();
			RedirectHelper::back();
		}

		public static function validation() {

			// validation
			if(self::getBeans('NM') == "") {
				self::message("Error Name warehouse blank !", "danger");
			}

			// validation
			if(self::getBeans('CITY') == "") {
				self::message("Error City warehouse blank !", "danger");
			}
		}
	}