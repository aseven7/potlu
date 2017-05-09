<?php /* menu */
	return [
		'Dashboard' => "/",
		'Sales' => [
			'Cash Sales' => 'sales/cash',
		],
		'Stock Management' => [
			'Purchase Order' => 'stock/purchase',
			'Stock Adjustment' => 'stock/adjustment',
		],
		'Inventory' => [
			'Product' => 'management/product',
			'Category' => 'management/category',
			'Brand' => 'management/brand',
			'Warehouse' => 'management/warehouse',
		],
		'System' => [
			'Supplier' => 'system/supplier',
			'Parameter' => 'system/parameter'
		],

		'' => [
			'ApplicationServices' => 'services'
		]

	];