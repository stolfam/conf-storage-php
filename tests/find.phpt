<?php
    require_once __DIR__ . "/../src/bootstrap.php";
    require_once __DIR__ . "/impl/TestObject.php";

    $tempDir = __DIR__ . "/temp";
    $namespace = "test";

    $configurator = new \Stolfam\ConfStorage\ConfStorage($tempDir, $namespace);

    $prefix = "myPrefix";
    $ids = [
        $prefix . "_one",
        $prefix . "_two",
        $prefix . "_three"
    ];

    $testObjects = [];

    foreach ($ids as $objectId) {
        $testObject = new \Stolfam\ConfStorage\Test\TestObject(rand(100, 999), "name" . rand(1, 9));
        $configurator->save($objectId, $testObject);
        $testObjects[$objectId] = $testObject;
    }

    $ids = $configurator->findIdsContain("myPrefix");

    foreach ($ids as $objectId) {
        $loadedTestObject = $configurator->load($objectId);
        if ($loadedTestObject instanceof \Stolfam\ConfStorage\Test\TestObject) {
            if ($testObjects[$objectId]->id == $loadedTestObject->id &&
                $testObjects[$objectId]->name == $loadedTestObject->name) {
                echo "$objectId: Test passed.\n";
            } else {
                echo "$objectId: Test failed.\n";
            }
        }
    }