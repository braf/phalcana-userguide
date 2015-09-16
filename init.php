<?php

// Add the markdown lib to the autoloader
$this->loader->registerNamespaces(array('Michelf' => __DIR__.'/vendor/markdown/Michelf'), true);

$di->setShared('markdown', '\Phalcana\Guide\Markdown');
$di->setShared('userguide', '\Phalcana\Guide\Userguide');
