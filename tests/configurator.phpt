<?php
    require_once __DIR__ . "/../src/bootstrap.php";
    require_once __DIR__ . "/impl/TestObject.php";

    $tempDir = __DIR__ . "/temp";
    $namespace = "test";
    $signature = "mySignature";
    $fakeSignature = "fakeSignature";

    $configurator = new \Stolfam\ConfStorage\ConfStorage($tempDir, $namespace);
    $configurator->setSignature($signature);


    $testObject = new \Stolfam\ConfStorage\Test\TestObject(123, "xyz");
    $objectId = "abc";

    echo "\nTEST #0 - Save/Load an unsigned object\n\n";

    if ($configurator->save($objectId, $testObject)) {
        $loadedTestObject = $configurator->load($objectId);

        if ($loadedTestObject instanceof Stolfam\ConfStorage\Test\TestObject) {
            if ($loadedTestObject->id == $testObject->id && $loadedTestObject->name == $testObject->name) {
                echo "Test passed.\n";
            } else {
                echo "Test failed.\n";
            }
        }
    }

    foreach ($configurator->errors as $error) {
        echo $error . "\n";
    }
    $configurator->errors = [];

    echo "\nTEST #1 - Save/Load an signed object\n\n";

    if ($configurator->save($objectId, $testObject)) {
        $loadedTestObject = $configurator->load($objectId);

        if ($loadedTestObject instanceof Stolfam\ConfStorage\Test\TestObject) {
            if ($loadedTestObject->id == $testObject->id && $loadedTestObject->name == $testObject->name) {
                echo "Test passed.\n";
            } else {
                echo "Test failed.\n";
            }
        }
    }

    foreach ($configurator->errors as $error) {
        echo $error . "\n";
    }
    $configurator->errors = [];

    echo "\nTEST #2 - Load an signed object with fake signature\n\n";

    echo "Set fake signature.\n";
    $configurator->setSignature($fakeSignature);

    $loadedTestObject = $configurator->load($objectId);

    if ($loadedTestObject instanceof Stolfam\ConfStorage\Test\TestObject) {
        if ($loadedTestObject->id == $testObject->id && $loadedTestObject->name == $testObject->name) {
            echo "Test passed.\n";
        } else {
            echo "Test failed.\n";
        }
    }

    foreach ($configurator->errors as $error) {
        echo $error . "\n";
    }
    $configurator->errors = [];

    echo "\nTEST #3 - Load a signed object with correct signature\n\n";

    echo "Set correct signature.\n";
    $configurator->setSignature($signature);

    $loadedTestObject = $configurator->load($objectId);

    if ($loadedTestObject instanceof Stolfam\ConfStorage\Test\TestObject) {
        if ($loadedTestObject->id == $testObject->id && $loadedTestObject->name == $testObject->name) {
            echo "Test passed.\n";
        } else {
            echo "Test failed.\n";
        }
    }


    foreach ($configurator->errors as $error) {
        echo $error . "\n";
    }
    $configurator->errors = [];