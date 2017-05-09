<?php
	class purchaseOrderLogic extends baseLogic {
		public function run() {
			// setup ORM
			ORM::configure('id_column', 'UID');

			// load setting
			parent::load();

			// set title
			self::assign('title', 'Purchase Order');
			self::assign('dataProduct', []);
			
			// datagrid
			$datagrid = ORM::for_table('purchase_transaction')->find_many();
			self::assign('datagrid', $datagrid);

			$prod = self::getBeans('PROD');
			$prodnm = self::getBeans('PRODNM');
			$price = self::getBeans('PRICE');
			$qty = self::getBeans('QTY');
			$discount = self::getBeans('DISCOUNT');
			$total = self::getBeans('TOTAL');

			// dataproduct
			$dataProduct = [];
			for($i = 0; $i < count($prod); $i++) {
				if($prod[$i] != '' && $price != '') {
					$dataProduct[] = [
						'PROD' => $prod[$i],
						'PRODNM' => $prodnm[$i],
						'PRICE' => $price[$i],
						'QTY' => $qty[$i],
						'DISCOUNT' => $discount[$i],
						'TOTAL' => $total[$i],
					];
				}
			}

			$deleteRowItem = is_null(self::post('deleteRowItem')) ? null : self::post('deleteRowItem');

			if($deleteRowItem >= 0) {
				unset($dataProduct[$deleteRowItem]);
			}

			self::assign('dataProduct', $dataProduct);

			// conditional request
			if(self::post('addMoreItem') || self::post('deleteRowItem') !== null) {
				self::setBeans('TYPEBEANS', 'RECOVERY');
			}else if(self::post('delete_bulk')) {
				$delete_bulk = self::post('delete_bulk');
				$uidArray = explode(',', $delete_bulk);
				self::delete_bulk($uidArray);
			}else if(self::post('delete')) {
				$uid = self::post('delete');
				self::delete($uid);
			}else if(self::post('register')) {
				self::register($dataProduct);
			}else{
				self::initial();
			}

		}

		public static function initial() {
			if(self::post('update')) {
				$uid = self::post('update');
				$record = ORM::for_table('purchase_transaction')->find_one($uid);

				// set update value
				self::setBeans('UID', $record->UID);
				self::setBeans('TRANSNO', $record->TRANSNO);
				self::setBeans('TRANSDATE', $record->TRANSDATE);
				self::setBeans('MEMO', $record->MEMO);
				self::setBeans('TYPEBEANS', 'RECOVERY');
				self::setBeans('DATEADDED', $record->DATEADDED);
				self::setBeans('DATEMODIFIED', $record->DATEMODIFIED);

				$dataProduct = [];
				$fields = ['product.NMPROD', 'PROD', 'QTY', 'PRICE', 'DISCOUNT'];
				$recordDetail = ORM::for_table('purchase_transaction_detail')->join('product', array('product.UID', '=', 'purchase_transaction_detail.PROD'))->select($fields)->where("PUID", $uid)->find_many();

				foreach($recordDetail as $record) {
					$dataProduct[] = [
						'PROD' => $record->PROD,
						'PRODNM' => $record->NMPROD,
						'PRICE' => $record->PRICE,
						'QTY' => $record->QTY,
						'DISCOUNT' => $record->DISCOUNT,
						'TOTAL' => 0,
					];
				}
				self::assign('dataProduct', $dataProduct);
			}
		}

		public static function register($dataProduct = []) {

			// validation
			self::validation();

			// update or insert
			$record = ORM::for_table('purchase_transaction')->create();
			
			// check uid not null
			if(self::getBeans('UID') != null) {
				$uid = self::getBeans('UID');
				$record = ORM::for_table('purchase_transaction')->find_one($uid);
				// clear detail in case update
				ORM::for_table('purchase_transaction_detail')->where('PUID', $uid)->delete_many();
			}
			
			// set record value 
			$record->TRANSNO = self::getBeans('TRANSNO');
			$record->TRANSDATE = self::getBeans('TRANSDATE');
			$record->TRANSTYPE = self::getBeans('TRANSTYPE');
			$record->MEMO = self::getBeans('MEMO');

			// check error message
			if(!self::hasMessage()) {
				$record = recordStamp($record);
				$record->save();

				foreach($dataProduct as $key => $value) {
					$recordDetail = ORM::for_table('purchase_transaction_detail')->create();
					$recordDetail->PUID = $record->UID;
					$recordDetail->PROD = $value['PROD'];
					$recordDetail->PRICE = $value['PRICE'];
					$recordDetail->DISCOUNT = $value['DISCOUNT'];
					$recordDetail->QTY = $value['QTY'];
					$recordDetail->save();
				}
				
				// redirect back
				if(self::getBeans('UID') == null) {
					self::clearBeans();
					self::setBeans('showOnBoard', true);
				}else{
					self::setBeans('TYPEBEANS', 'RECOVERY');
				}

				self::message("Register Purchase Order information done..");
				self::presistance();
				RedirectHelper::back();
			}else{
				self::setBeans('TYPEBEANS', 'RECOVERY');
			}
		}

		public static function delete($uid) {
			ORM::for_table('purchase_transaction')->find_one($uid)->delete();
			ORM::for_table('purchase_transaction_detail')->where('PUID', $uid)->delete_many();

			self::setBeans('showOnBoard', true);
			self::message("Record deleted..");
			self::presistance();
			RedirectHelper::back();
		}

		public static function delete_bulk($uidArray) {
			ORM::for_table('purchase_transaction')->where_in('UID', $uidArray)->delete_many();
			ORM::for_table('purchase_transaction_detail')->where_in('PUID', $uidArray)->delete_many();
			
			self::setBeans('showOnBoard', true);
			self::message("Record deleted..");
			self::presistance();
			RedirectHelper::back();
		}

		public static function validation() {
			// validation
			if(self::getBeans('MEMO') == "") {
				self::message("Error Memo blank !", "danger");
			}

			if(count(self::getBeans('PROD')) == 0) {
				self::message("Error No Item selected !", "danger");
			}
		}
	}