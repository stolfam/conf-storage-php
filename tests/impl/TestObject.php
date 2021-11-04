<?php
    declare(strict_types=1);

    namespace Stolfam\ConfStorage\Test;

    use Stolfam\ConfStorage\IConfigurable;


    /**
     * Class TestObject
     * @package Stolfam\Configurator\Test
     */
    final class TestObject implements IConfigurable
    {
        public int $id;
        public string $name;

        /**
         * TestObject constructor.
         * @param int    $id
         * @param string $name
         */
        public function __construct(int $id, string $name)
        {
            $this->id = $id;
            $this->name = $name;
        }

        public function toJson(): string
        {
            return json_encode([
                "id"   => $this->id,
                "name" => $this->name
            ]);
        }

        public static function createFromJson(string $json): IConfigurable
        {
            $o = json_decode($json);

            return new TestObject($o->id, $o->name);
        }
    }