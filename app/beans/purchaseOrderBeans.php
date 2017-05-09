<?php
	return [
		'UID' => null,
		
		'TRANSNO' => TransactionHelper::GetPurchaseOrderNumber(1),
		'TRANSDATE' => null,
		'TRANSTYPE' => 8,
		'MEMO' => null,
		'DISCOUNT' => 0,

		'ITEM' => [],

		'PRODNM' => [],
		'PROD' => [],
		'PRICE' => [],
		'DISCOUNT' => [],
		'QTY' => [],
		'TOTAL' => [],

		'DATEMODIFIED' => null,
		'DATEADDED' => null,

		'TYPEBEANS' => null,
		'showOnBoard' => false,
	];