<?php

namespace Phalcana\Controllers\Guide;

use Phalcana\Exceptions\HTTP404,
	Phalcana\Guide\Markdown,
	Phalcana\Guide\DocClass,
	Phalcana\Guide\Doc;

/**
 * Main controller for the user guide and API browser pages
 *
 * @package		Userguide
 * @category	Base
 * @author		Neil Brayfield
 */
class GuideController extends \Phalcon\Mvc\Controller
{
	

	public function initialize()
	{
    	$this->view->setMainView('guide');
        $this->view->setTemplateBefore('guide/index');
		$this->view->setVar('guide', true);

        // CSS in the header
        $this->assets
            ->collection('cssHeader')
            ->addCss('public/guide/css/guide.css');

        // Javascripts in the header
        $this->assets
            ->collection('jsHeader')
            ->addJs('public/guide/js/modernizr.js');

        // Javascripts in the footer
        $this->assets
            ->collection('jsFooter')
            ->addJs('public/guide/js/guide.js');

		// CSS in the footer
		$this->assets
			->collection('cssFooter')
            ->addCss('//fonts.googleapis.com/css?family=Open+Sans:300,400,700|Ubuntu+Mono', false);


        $this->conf = $this->config->load('userguide');

        Markdown::$baseUrl = $this->url->get('guide/');
        Markdown::$imageUrl =$this->url->get('public/guide/img/');

        $this->title->setReplacements(array(
        	'Guide' => 'User Guide',
        	'Api Browser' => 'API Browser',
        ));


	}


	/**
	 * Load the landing page for the userguide
	 **/
	public function indexAction()
	{
		$this->view->setVar('menu', $this->conf->modules);

		
	}
		
	/**
	 * Handle a userguide page based on the parameters passed to the router parsind the markdowns
	 **/
	public function moduleAction()
	{
		$this->view->setVar('guide', true);

		$module = $this->dispatcher->getParam('mod');
		$page = $this->dispatcher->getParam('page');
		
		if (!$page) $page = 'index';

		// load the routes so they are relative to the current user guide
		Markdown::$baseUrl = $this->url->get('guide/'.$module.'/');
        Markdown::$imageUrl = $this->url->get('public/guide/img/'.$module.'/');

        // find the menu for the module
		$menu = $this->fs->findFile('guide/'.$module, 'menu', 'md');
		if (!$menu) throw new HTTP404();

        // Parse the menu for the module
		$menuMd = $this->markdown->transform(file_get_contents($menu));
		
		// find the page file based on the passed variable
		$file = $this->fs->findFile('guide/'.$module, $page, 'md');
		if (!$file) throw new HTTP404();
		
		
        // Parse the file for the page
		$md = $this->markdown->transform(file_get_contents($file));

		$this->view->setVar('menu', $menuMd);
		$this->view->setVar('html', $md);

	}
		

	/**
	 * Load the API page for the given class
	 **/
	public function apiBrowserAction()
	{
		$this->view->setVar('guide', false);

		$this->title->setVar('controller', "");

		$menu = $this->userguide->menu();
		$this->view->setVar('menu', $menu);

		if ($this->dispatcher->getParam('class')) {
			$cl = str_replace('_', '\\', $this->dispatcher->getParam('class'));
			
			// Check class exists
			if (!class_exists($cl)) throw new HTTP404();

			$class = new DocClass($cl);
			$this->view->setVar('doc', $class);

			$this->title->setVar('page', $cl);

		} else {
			$classes = $this->userguide->classes();
			$this->view->setVar('classes', $classes);
			$this->view->pick('guide/guide/contents');
		}

	}
}