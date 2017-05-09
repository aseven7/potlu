<?php
	// addons route here
	Flight::route('/m/backup', function() {
		RecoveryHelper::EXPORT_TABLES("localhost", "root", "", "dist");
	});