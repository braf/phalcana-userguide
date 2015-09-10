<?php 
// Routes here


$group = new Phalcon\Mvc\Router\Group(array(
	'namespace' => 'Phalcana\Controllers\Guide',
	'controller' => 'guide',
	'action' => 'index',
));

$group->setPrefix('/guide');

$group->add("");

$group->add("/{mod:[a-z\-]+}", array(
	'action' => 'module',
));

$group->add("/{mod:[a-z\-]+}/{page:[a-z\-\/]+}", array(
	'action' => 'module',
));

$group->add("/api", array(
	'action' => 'apiBrowser',
));

$group->add("/api/{class}", array(
	'action' => 'apiBrowser',
));

$router->mount($group);