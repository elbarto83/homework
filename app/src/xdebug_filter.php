<?php

xdebug_set_filter(
    XDEBUG_FILTER_CODE_COVERAGE,
    XDEBUG_PATH_INCLUDE,
    [ __DIR__ . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR ]
);