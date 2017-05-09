<?php
	class supplierLogic extends baseLogic {
		public function run() {
			// setup ORM
			ORM::configure('id_column', 'UID');

			// load setting
			parent::load();

			// set title
			self::assign('title', 'Supplier');

			// datagrid
			$datagrid = ORM::for_table('supplier')->find_many();
			self::assign('datagrid', $datagrid);

			// conditional request
			if(self::post('delete_bulk')) {
				$delete_bulk = self::post('delete_bulk');
				$uidArray = explode(',', $delete_bulk);
				self::delete_bulk($uidArray);
			}else if(self::post('delete')) {
				$uid = self::post('delete');
				self::delete($uid);
			}else if(self::post('register')) {
				self::register();
			}else{
				self::initial();
			}
		}

		public static function initial() {
			if(self::post('update')) {
				$uid = self::post('update');
				$record = ORM::for_table('supplier')->find_one($uid);

				// set update value
				self::setBeans('UID', $record->UID);
				self::setBeans('NM', $record->NM);
				self::setBeans('NMPERSON', $record->NMPERSON);
				self::setBeans('PHONE1', $record->PHONE1);
				self::setBeans('PHONE2', $record->PHONE2);
				self::setBeans('LOCATE', $record->LOCATE);

				self::setBeans('TYPEBEANS', 'RECOVERY');
			}
		}

		public static function register() {

			// validation
			self::validation();

			// update or insert
			$record = ORM::for_table('supplier')->create();
			
			// check uid not null
			if(self::getBeans('UID') != null) {
				$uid = self::getBeans('UID');
				$record = ORM::for_table('supplier')->find_one($uid);
			}
			
			// set record value 
			$record->NM = self::getBeans('NM');
			$record->NMPERSON = self::getBeans('NMPERSON');
			$record->PHONE1 = self::getBeans('PHONE1');
			$record->PHONE2 = self::getBeans('PHONE2');
			$record->LOCATE = self::getBeans('LOCATE');

			// check error message
			if(!self::hasMessage()) {
				$record = recordStamp($record);
				$record->save();
				
				// redirect back
				if(self::getBeans('UID') == null) {
					self::clearBeans();
					self::setBeans('showOnBoard', true);
				}else{
					self::setBeans('TYPEBEANS', 'RECOVERY');
				}

				self::message("Register Supplier information done..");
				self::presistance();
				RedirectHelper::back();
			}else{
				self::setBeans('TYPEBEANS', 'RECOVERY');
			}
		}

		public static function delete($uid) {
			ORM::for_table('supplier')->find_one($uid)->delete();

			self::setBeans('showOnBoard', true);
			self::message("Record deleted..");
			self::presistance();
			RedirectHelper::back();
		}

		public static function delete_bulk($uidArray) {
			ORM::for_table('supplier')->where_in('UID', $uidArray)->delete_many();
			
			self::setBeans('showOnBoard', true);
			self::message("Record deleted..");
			self::presistance();
			RedirectHelper::back();
		}

		public static function validation() {
			// validation
			if(self::getBeans('NM') == "") {
				self::message("Error Name supplier blank !", "danger");
			}
			
		}
	}