<?php
    $files = array_merge(glob(__DIR__ . "/*.php"), glob(__DIR__ . "/Interfaces/*.php"), glob(__DIR__ . "/Sys/*.php"));

    foreach ($files as $file) {
        require_once $file;
    }