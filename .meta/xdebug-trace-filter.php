<?php

ini_set('xdebug.profiler_enable', 1);
ini_set('xdebug.default_enable', 1);
ini_set('xdebug.remote_autostart', 1);
ini_set('xdebug.auto_trace', 1);

ini_set('xdebug.trace_output_dir', '/tmp/xdebug');
ini_set('xdebug.trace_format ', 0);
ini_set('xdebug.idekey', 'PHPSTORM');

if (!is_dir('/tmp/xdebug')) {
    mkdir('/tmp/xdebug', 755, true);
}


xdebug_set_filter(
    XDEBUG_FILTER_TRACING,
	XDEBUG_PATH_WHITELIST,
	[ realpath(__DIR__ . '/../src') ]
);

