<?php
    declare(strict_types=1);

    namespace Stolfam\ConfStorage;

    /**
     * Class Configurator
     * @package Stolfam\Configurator
     */
    class ConfStorage
    {
        const KEY_CLASS = "class";
        const KEY_TIMESTAMP = "ts";
        const KEY_HASH = "hash";

        private string $path;
        private string $namespace;
        private int $version;

        public array $errors = [];

        private ?Sign $sign = null;

        /**
         * Configurator constructor.
         * @param string $path
         * @param string $namespace
         * @param int    $version
         */
        public function __construct(string $path, string $namespace, int $version = 1)
        {
            $this->path = $path;
            $this->namespace = $namespace;
            $this->version = $version;

            if (!file_exists($this->path)) {
                if (!mkdir($this->path)) {
                    throw new \Error("Failed to create a dir: $path");
                }
            }

            if (!file_exists($this->getNamespaceDir())) {
                if (!mkdir($this->getNamespaceDir())) {
                    throw new \Error("Failed to create a dir: " . $this->getNamespaceDir());
                }
            }

            if (!file_exists($this->getCurrentDir())) {
                if (!mkdir($this->getCurrentDir())) {
                    throw new \Error("Failed to create a dir: " . $this->getCurrentDir());
                }
            }
        }

        public function setSignature(string $sign): void
        {
            $this->sign = null;

            $salt = sha1(md5($sign));
            $hashedSign = sha1($sign);

            $sign = $this->load($hashedSign);
            if ($sign === null) {
                $sign = new Sign($salt);

                $this->save($hashedSign, $sign);
            }

            $this->sign = $sign;
        }

        /**
         * @param string $id
         * @return IConfigurable|null
         */
        public function load(string $id): ?IConfigurable
        {
            $file = $this->getCurrentDir() . "/" . $id;
            if (file_exists($file)) {
                $json = file_get_contents($file);
                $object = json_decode($json);
                if (isset($object->{$this->getHashedKey(self::KEY_CLASS)})) {
                    $class = (string) $object->{$this->getHashedKey(self::KEY_CLASS)};
                    $hash = $object->{$this->getHashedKey(self::KEY_HASH)};

                    // check integrity
                    $array = (array) $object;
                    unset($array[$this->getHashedKey(self::KEY_HASH)]);
                    if (sha1(json_encode($array)) != $hash) {
                        $this->errors[] = "Integrity check failed.";

                        return null;
                    }

                    try {
                        return $class::createFromJson($json);
                    } catch (\Throwable $t) {
                        $this->errors[] = $t->getMessage();

                        return null;
                    }
                }
                $this->errors[] = "Bad signature.";

                return null;
            }
            $this->errors[] = "Object $id not found.";

            return null;
        }

        /**
         * @param string        $id
         * @param IConfigurable $object
         * @return bool
         */
        public function save(string $id, IConfigurable $object): bool
        {
            $array = (array) json_decode($object->toJson());
            $array[$this->getHashedKey(self::KEY_CLASS)] = get_class($object);
            $array[$this->getHashedKey(self::KEY_TIMESTAMP)] = time();
            $array[$this->getHashedKey(self::KEY_HASH)] = sha1(json_encode($array));
            $json = json_encode($array);

            return (bool) file_put_contents($this->getCurrentDir() . "/" . $id, $json);
        }

        /**
         * @return string
         */
        private function getCurrentDir(): string
        {
            return $this->getNamespaceDir() . "/" . $this->version;
        }

        /**
         * @return string
         */
        private function getNamespaceDir(): string
        {
            return $this->path . "/" . $this->namespace;
        }

        public function getHashedKey(string $name): string
        {
            return sha1($this->namespace . $this->version . $name . @$this->sign->salt ?? null);
        }

        /**
         * Returns IDs of object contain string $part
         *
         * @param string $part
         * @return array
         */
        public function findIdsContain(string $part): array
        {
            $files = glob($this->getCurrentDir() . "/*$part*");

            $ids = [];
            foreach ($files as $file) {
                $ids[] = basename($file);
            }

            return $ids;
        }
    }