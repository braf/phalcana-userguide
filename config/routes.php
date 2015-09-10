<?php
// Routes here


$group = new Phalcana\Mvc\Router\Group(array(
	'namespace' => 'Phalcana\Controllers\Guide',
	'controller' => 'guide',
	'action' => 'index',
));

$group->setPrefix('guide');

$group->add("");

$group->add("/<mod>(/<page>)", array(
	'action' => 'module',
    'mod' => '[a-z\-]+',
    'page' => '[a-z\-\/]+'
));

$group->add("/api(/<class>)", array(
	'action' => 'apiBrowser',
));

$router->mount($group);
