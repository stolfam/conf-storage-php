<?php
    declare(strict_types=1);

    namespace Stolfam\Configurator;

    /**
     * Class Sign
     * @package Stolfam\Configurator
     * @property-read int    $updated
     * @property-read string $salt
     */
    class Sign implements IConfigurable
    {
        protected string $salt;
        protected int $updated;

        /**
         * Sign constructor.
         * @param string   $salt
         * @param int|null $updated
         */
        public function __construct(string $salt, int $updated = null)
        {
            $this->salt = $salt;
            $this->updated = $updated ?? time();
        }

        public function toJson(): string
        {
            return json_encode([
                "salt"    => $this->salt,
                "updated" => $this->updated
            ]);
        }

        public static function createFromJson(string $json): IConfigurable
        {
            $o = json_decode($json);

            return new Sign($o->salt, $o->updated);
        }

        public function __get($name)
        {
            if ($name == "salt") {
                return $this->salt;
            }
            if ($name == "updated") {
                return $this->updated;
            }

            return null;
        }
    }