# conf-storage-php

Saves configurations of your PHP objects.

# Install
```
composer require stolfam/conf-storage-php
```

# Using

```
$baseSaveDir = __DIR__ . "/your/path"; 
$namespace = "yourNamespace";
$confStorage = new ConfStorage($baseSaveDir, $namespace);

// your object implementing IConfigurable
$object = new TestObject();

// Save your object
$yourObjectId = "yourObjectId";
$confStorage->save($yourObjectId, $objcet);

// Load your object whenever you want
$yourObject = $confStorage->load($yourObjectId);
```

# Features

## Integrity check

Every object is checked when is loaded, if its hash is correct and nobody changed it. Otherwise `null` is returned and
you can check `$confStorage->errors` for error messages.

## Signing

You can sign every object with your signature/password:

```
$baseSaveDir = __DIR__ . "/your/path"; 
$namespace = "yourNamespace";
$confStorage = new ConfStorage($baseSaveDir, $namespace);

$signature = "yourSignatureOrSecretPassword";
$confStorage->setSignature($signature);
```

It works like password. If you have it, you can read objects. Otherwise you get `null`.

Passwords/signatures are NOT stored in plain text. They're always hashed.

## Overwriting

Be aware of that every file is overwritten when is saving.