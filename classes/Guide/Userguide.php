<?php

namespace Phalcana\Guide;

use Phalcon\DI;
 
/**
 * Documentation helper
 *
 * @package		Userguide
 * @category	Services
 * @author		Neil Brayfield
 */
class Userguide extends \Phalcon\Di\Injectable {
	
	/**
	 * Loads the menu
	 * 
	 * @return	string
	 * @author	Neil Brayfield
	 **/
	public function menu()
	{
		$classes = $this->classes();

		$menu = array();

		foreach ($classes as $className) {
			$class = new DocClass($className);

			$link = $this->menuLink($className);

			if (isset($class->tags['package']))
			{
				foreach ($class->tags['package'] as $package)
				{
					if (isset($class->tags['category']))
					{
						foreach ($class->tags['category'] as $category)
						{
							$menu[$package][$category][] = $link;
						}
					}
					else
					{
						$menu[$package]['Base'][] = $link;
					}
				}
			}
			else
			{
				$menu['[Unknown]']['Base'][] = $link;
			}
		}

		ksort($menu);

		return $menu;

	}

	/**
	 * Gets a list of classes
	 * 
	 * @uses 	Filesystem::listFiles()
	 * @return	string
	 * @author	Neil Brayfield
	 **/
	public function classes(array $list = NULL)
	{
		if($list == NULL) {
			$list = $this->fs->listFiles('classes');
		}


		$classes = array();

		foreach ($list as $name => $path) {

			// If sub directory recurse
			if (is_array($path)) {

				$classes += self::classes($path);

			} else {

				if($name == 'classes'.DIRECTORY_SEPARATOR.'Core'.DIRECTORY_SEPARATOR.'Phalcana.php') {

					$name = 'Phalcana';
				
				} else {

					// Convert class name to namespace
					$name = substr($name, 8, -4);
					$name = 'Phalcana\\'.str_replace(DIRECTORY_SEPARATOR, '\\', $name);

				}
				
				$classes[$name] = $name;

			}

		}

		return $classes;

	}


	/**
	 * Comment parser that extracts tags, Code thanks to the Kohana Kodoc class
	 * 
	 * @return	void
	 * @author	Neil Brayfield
	 **/
	public function parse($comment, $html = false)
	{
		// Normalize all new lines to \n
		$comment = str_replace(array("\r\n", "\n"), "\n", $comment);

		// Split into lines while capturing without leading whitespace
		preg_match_all('/^\s*\* ?(.*)\n/m', $comment, $lines);

		// Tag content
		$tags = array();

		/**
		 * Process a tag and add it to $tags
		 *
		 * @param   string  $tag    Name of the tag without @
		 * @param   string  $text   Content of the tag
		 * @return  void
		 */
		$add_tag = function ($tag, $text) use ($html, & $tags)
		{
			// Don't show @access lines, they are shown elsewhere
			if ($tag !== 'access')
			{
				if ($html)
				{
					//$text = Kodoc::format_tag($tag, $text);
				}

				// Add the tag
				$tags[$tag][] = $text;
			}
		};

		$comment = $tag = NULL;
		$end = count($lines[1]) - 1;

		foreach ($lines[1] as $i => $line)
		{
			// Search this line for a tag
			if (preg_match('/^@(\S+)\s*(.+)?$/', $line, $matches))
			{
				if ($tag)
				{
					// Previous tag is finished
					$add_tag($tag, $text);
				}

				$tag = $matches[1];
				$text = isset($matches[2]) ? $matches[2] : '';

				if ($i === $end)
				{
					// No more lines
					$add_tag($tag, $text);
				}
			}
			elseif ($tag)
			{
				// This is the continuation of the previous tag
				$text .= "\n".$line;

				if ($i === $end)
				{
					// No more lines
					$add_tag($tag, $text);
				}
			}
			else
			{
				$comment .= "\n".$line;
			}
		}

		$comment = trim($comment, "\n");

		if ($comment AND $html)
		{
			// Parse the comment with Markdown
			//$comment = Kodoc_Markdown::markdown($comment);
		}

		return array($comment, $tags);	
	}

	/**
	 * undocumented function
	 * 
	 * @return	void
	 * @author	Neil Brayfield
	 **/
	public function menuLink($className)
	{
		$link = $this->url->get('guide/api/');
		$link .= urlencode(str_replace('\\', '_', $className));
		$text = explode('\\', $className);

		return '<a href="'.$link.'">'.$text[count($text)-1].'</a>';
	}


	/**
	 * Get the source of a function
	 *
	 * @param  string   the filename
	 * @param  int      start line?
	 * @param  int      end line?
	 */
	public function source($file, $start, $end)
	{
		if ( ! $file) return FALSE;

		$file = file($file, FILE_IGNORE_NEW_LINES);

		$file = array_slice($file, $start - 1, $end - $start + 1);

		if (preg_match('/^(\s+)/', $file[0], $matches))
		{
			$padding = strlen($matches[1]);

			foreach ($file as & $line)
			{
				$line = substr($line, $padding);
			}
		}

		return str_replace('<', '&alt;', implode("\n", $file));
	}

	/**
	 * Finds links in an array and return the a tags
	 * 
	 * @uses	Phalcana\Guide\Userguide::classLink
	 * @param 	array Classes array
	 * @return 	array Array of tranformed links
	 **/
	public function classLinks($classes)
	{
		foreach ($classes as &$class) {
			$class = $this->classLink($class);
		}
		return $classes;
	}


	/**
	 * Class link
	 * 
	 * @param 	string Class to try to link
	 * @param 	string Method to append
	 * @return	string Either the transformed link the original
	 **/
	public function classLink($class, $method = false)
	{
		if( strpos($class, 'Phalcana') === 0) {
			
			$link = $this->url->get('guide/api/').str_replace('\\', '_', str_replace('::', '#', $class));
			if ($method) $link .= '#'.$method;

		} else if (strpos($class, 'Phalcon') === 0){
			$link = 'http://docs.phalconphp.com/en/latest/api/'.str_replace('\\', '_', $class).'.html';
		} else {
			$link = false;
		}

		if ($link) {
			$link = '<a href="'.$link.'">'.$class.'</a>';
		} else {
			return $class;
		}

		return $link;
	}

} 