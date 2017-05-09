<?php
	class TransactionHelper {
		public static function GetCashSalesNumber($sequence = 1) {
			$format = getParam('Cash Order');
			if($format == null) {
				$format = "CSH-YYYYMMDD/####";
			}
			
			$formatter = self::NumberFormatter($format);
			$formattedSequence = str_repeat("0", $formatter[1] - strlen($sequence)) . $sequence;
			$no = $formatter[0];
			$record = ORM::for_table('sales_transaction')->where_like('TRANSNO', $no."%")->order_by_desc('TRANSNO')->find_one();
			if($record != null) {
				$trans_no = $record->TRANSNO;
				$sequence = str_replace($no, "", $trans_no);
				$sequence = intval($sequence)+1;
				$formattedSequence = str_repeat("0", 4-strlen($sequence)) . $sequence;	
			}

			return $no . $formattedSequence;
		}

		public static function GetPurchaseOrderNumber($sequence = 1) {
			$format = getParam('Purchase Order');
			if($format == null) {
				$format = "PO-YYYYMMDD/####";
			}
			
			$formatter = self::NumberFormatter($format);
			$formattedSequence = str_repeat("0", $formatter[1] - strlen($sequence)) . $sequence;
			$no = $formatter[0];
			$record = ORM::for_table('purchase_transaction')->where_like('TRANSNO', $no."%")->order_by_desc('TRANSNO')->find_one();
			if($record != null) {
				$trans_no = $record->TRANSNO;
				$sequence = str_replace($no, "", $trans_no);
				$sequence = intval($sequence)+1;
				$formattedSequence = str_repeat("0", 4-strlen($sequence)) . $sequence;	
			}

			return $no . $formattedSequence;
		}
		
		public static function GetSKUNumber($sequence = 1) {
			$format = getParam('SKU Product');
			if($format == null) {
				$format = "SKU-YYYYMM-####";
			}
			$formatter = self::NumberFormatter($format);
			$formattedSequence = str_repeat("0", $formatter[1] - strlen($sequence)) . $sequence;
			$no = $formatter[0];
			$record = ORM::for_table('sales_transaction')->where_like('TRANSNO', $no."%")->order_by_desc('TRANSNO')->find_one();
			if($record != null) {
				$trans_no = $record->TRANSNO;
				$sequence = str_replace($no, "", $trans_no);
				$sequence = intval($sequence)+1;
				$formattedSequence = str_repeat("0", 4-strlen($sequence)) . $sequence;	
			}

			return $no . $formattedSequence;
		}

		public static function NumberFormatter($formatter = "") {
			/* available formatter 
			 *
			 * YYYY - year
			 * MM   - month
			 * DD   - day
			 * HH   - hour
			 * mm   - minute
			 * #### - digit
			 */

			$formatter = str_replace("YYYY", date('Y'), $formatter);
			$formatter = str_replace("MM", date('m'), $formatter);
			$formatter = str_replace("DD", date('d'), $formatter);
			$formatter = str_replace("HH", date('H'), $formatter);
			$formatter = str_replace("mm", date('i'), $formatter);

			if(strpos($formatter, "#") !== false) {
				$formatterResult = [];
				$formatterResult[1] = substr_count($formatter, "#");
				$formatter = str_replace("#", "", $formatter);
				$formatterResult[0] = $formatter;

				return $formatterResult;
			}
			
			return $formatter;
		}

		public static function GetStockProduct($UID) {
			$stock = 0;
			$product = ORM::for_table('product')->find_one($UID);
			if($product->UID != null) {
				$purchaseQuantity = ORM::for_table('purchase_transaction_detail')->where('PROD', $product->UID)->sum('QTY');
				$salesQuantity = ORM::for_table('sales_transaction_detail')->where('PROD', $product->UID)->sum('QTY');

				$stock = $purchaseQuantity - $salesQuantity;
			}
			return $stock;
		}
	}