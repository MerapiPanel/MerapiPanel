<?php

return (array_map(function ($file) {
	return require_once $file;}, glob(__DIR__ . '/**/index.php', GLOB_BRACE)));