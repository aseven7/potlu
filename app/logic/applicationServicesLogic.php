<?php
	class applicationServicesLogic extends baseLogic {
		public function run() {
			// setup ORM
			ORM::configure('id_column', 'UID');

			// load setting
			parent::load();

			if(self::get('action')) {
				if(self::get('action') == 'product') {
					$fields = ['product.UID', 'product.SKU', 'product.CDPROD', 'product.NMPROD', 'product_pricing.SELLPRC'];
					$product = ORM::for_table('product')->join('product_pricing', array('product.UID', '=', 'product_pricing.PROD'))
					->select($fields)->find_many();

					$productResult = [];
					foreach($product as $key => $value) {
						$productResult[] = ['id' => $value->UID, 'name' => $value->NMPROD . ' (' . $value->CDPROD . ')', 'price' => $value->SELLPRC, 'UID' => $value->UID ];
					}

					header('content-type: text/json');
					echo json_encode($productResult);	
				}
			}
		}
	}