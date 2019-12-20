<?php

ini_set('xdebug.profiler_enable', 1);
ini_set('xdebug.default_enable', 1);
ini_set('xdebug.remote_autostart', 1);
ini_set('xdebug.auto_trace', 1);


xdebug_set_filter(
    XDEBUG_FILTER_TRACING,
	XDEBUG_PATH_WHITELIST,
	[ realpath(__DIR__ . '/../src') ]
);

