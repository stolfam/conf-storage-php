<?php
    declare(strict_types=1);

    namespace Stolfam\Configurator;

    /**
     * Interface IConfigurable
     * @package Stolfam\Configurator
     */
    interface IConfigurable
    {
        public function toJson(): string;

        public static function createFromJson(string $json): IConfigurable;
    }