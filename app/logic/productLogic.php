<?php
	class productLogic extends baseLogic {
		public function run() {
			// setup ORM
			ORM::configure('id_column', 'UID');

			// load setting
			parent::load();

			// set title
			self::assign('title', 'Product');

			// inline edit
			if(self::post('inline_edit') && self::post('COLUMN') == 'SELLPRC') {
				self::featureInlineEdit('product_pricing', 'PROD');
			}else if(self::post('inline_edit') && in_array(self::post('COLUMN'), ['CDPROD', 'NMPROD', 'SKU'])) {
				self::featureInlineEdit('product', 'UID');
			}

			// datagrid
			$fields = ['product.UID', 'product.SKU', 'product.CDPROD', 'product.NMPROD', 'product_pricing.SELLPRC'];
			$datagrid = ORM::for_table('product')->join('product_pricing', array('product.UID', '=', 'product_pricing.PROD'))
			->select($fields)->find_many();

			self::assign('datagrid', $datagrid);

			// data category
			$dataCategory = ORM::for_table('category')->find_many();
			self::assign('dataCategory', $dataCategory);

			// data warehouse
			$dataWarehouse = ORM::for_table('warehouse')->find_many();
			self::assign('dataWarehouse', $dataWarehouse);

			// data brand
			$dataBrand = ORM::for_table('brand')->find_many();
			self::assign('dataBrand', $dataBrand);

			// conditional request
			if(self::post('delete_bulk')) {
				$delete_bulk = self::post('delete_bulk');
				$uidArray = explode(',', $delete_bulk);
				self::delete_bulk($uidArray);
			}else if(self::post('duplicate')) {
				$uid = self::post('duplicate');
				self::duplicate($uid);
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
			$warehouseRecord = ORM::for_table('warehouse')->where('ISDEFAULT', 1)->find_one();
			if($warehouseRecord) {
				self::setBeans('WAREHS', $warehouseRecord->UID);
			}
			
			if(self::post('update')) {
				$uid = self::post('update');
				$record = ORM::for_table('product')->find_one($uid);

				// set update value
				self::setBeans('UID', $record->UID);
				self::setBeans('SKU', $record->SKU);
				self::setBeans('CDPROD', $record->CDPROD);
				self::setBeans('NMPROD', $record->NMPROD);
				self::setBeans('CATGRY', $record->CATGRY);
				self::setBeans('WAREHS', $record->WAREHS);
				self::setBeans('BRAND', $record->BRAND);
				self::setBeans('DESCRIPTION', $record->DESCRIPTION);
				self::setBeans('REMARK', $record->REMARK);
				self::setBeans('STATUS', $record->STATUS);

				$recordPricing = ORM::for_table('product_pricing')->where('PROD', $record->UID)->find_one();
				self::setBeans('BASEPRC', $recordPricing->BASEPRC);
				self::setBeans('SELLPRC', $recordPricing->SELLPRC);

				self::setBeans('TYPEBEANS', 'RECOVERY');
			}

			$margin = self::getBeans('SELLPRC') - self::getBeans('BASEPRC');
			$marginPercentage = self::getBeans('BASEPRC') != 0 ? ( $margin / self::getBeans('BASEPRC') ) * 100 : 0;
			self::setBeans('MARGIN', $margin);
			self::setBeans('MARGINPRS', round($marginPercentage, 2) . '%');

		}

		public static function register() {

			// validation
			self::validation();

			// update or insert
			$record = ORM::for_table('product')->create();
			$recordPricing = ORM::for_table('product_pricing')->create();
			
			// check uid not null
			if(self::getBeans('UID') != null) {
				$uid = self::getBeans('UID');
				$record = ORM::for_table('product')->find_one($uid);
				$recordPricing = ORM::for_table('product_pricing')->where("PROD", $record->UID)->find_one();
			}
			
			// set record value
			$record->SKU = self::getBeans('SKU');
			$record->CDPROD = self::getBeans('CDPROD');
			$record->NMPROD = self::getBeans('NMPROD');
			$record->CATGRY = self::getBeans('CATGRY');
			$record->WAREHS = self::getBeans('WAREHS');
			$record->BRAND = self::getBeans('BRAND');
			$record->DESCRIPTION = self::getBeans('DESCRIPTION');
			$record->REMARK = self::getBeans('REMARK');
			$record->STATUS = self::getBeans('STATUS');

			// create product pricing
			$recordPricing->BASEPRC = self::getBeans('BASEPRC');
			$recordPricing->SELLPRC = self::getBeans('SELLPRC');

			// check error message
			if(!self::hasMessage()) {
				
				$record = recordStamp($record);
				$record->save();

				$recordPricing->PROD = $record->UID;
				$recordPricing->save();
				
				// redirect back
				if(self::getBeans('UID') == null) {
					self::clearBeans();
					self::setBeans('showOnBoard', true);
				}else{
					self::setBeans('TYPEBEANS', 'RECOVERY');
				}

				self::message("Register Product information done..");

				self::presistance();
				RedirectHelper::back();
			}else{
				self::setBeans('TYPEBEANS', 'RECOVERY');
			}
		}

		public static function delete($uid) {
			ORM::for_table('product')->find_one($uid)->delete();

			self::setBeans('showOnBoard', true);
			self::message("Record deleted..");
			self::presistance();
			RedirectHelper::back();
		}

		public static function duplicate($uid) {
			$record = ORM::for_table('product')->find_one($uid);
			$recordPricing = ORM::for_table('product_pricing')->where("PROD", $record->UID)->find_one();

			$newRecord = ORM::for_table('product')->create();
			$newRecord->SKU = $record->SKU;
			$newRecord->CDPROD = $record->CDPROD;
			$newRecord->NMPROD = $record->NMPROD;
			$newRecord->CATGRY = $record->CATGRY;
			$newRecord->WAREHS = $record->WAREHS;
			$newRecord->BRAND = $record->BRAND;
			$newRecord->DESCRIPTION = $record->DESCRIPTION;
			$newRecord->REMARK = $record->REMARK;
			$newRecord->STATUS = $record->STATUS;
			$newRecord = recordStamp($newRecord);
			$newRecord->save();

			// create product pricing
			$newRecordPricing = ORM::for_table('product_pricing')->create();
			$newRecordPricing->PROD = $newRecord->UID;
			$newRecordPricing->BASEPRC = $recordPricing->BASEPRC;
			$newRecordPricing->SELLPRC = $recordPricing->SELLPRC;
			$newRecordPricing->save();

			self::message("Record duplicated..");
			self::presistance();
			RedirectHelper::back();
		}

		public static function delete_bulk($uidArray) {
			ORM::for_table('product')->where_in('UID', $uidArray)->delete_many();
			
			self::setBeans('showOnBoard', true);
			self::message("Record deleted..");
			self::presistance();
			RedirectHelper::back();
		}

		public static function validation() {
			// validation
			if(self::getBeans('CDPROD') == "") {
				self::message("Error Code product blank !", "danger");
			}

			if(self::getBeans('NMPROD') == "") {
				self::message("Error Name product blank !", "danger");
			}
			
		}
	}