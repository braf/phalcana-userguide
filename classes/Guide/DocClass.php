<?php

namespace Phalcana\Guide;

use Phalcana\Debug;
use Phalcon\Di\Injectable;

/**
 * Documentation helper
 *
 * @package     Userguide
 * @category    Reflection
 * @author      Neil Brayfield
 */
class DocClass extends Injectable
{

    /**
     * @var  ReflectionClass The ReflectionClass for this class
     */
    public $class;

    /**
     * @var  string  Class name without namespace
     */
    public $name;

    /**
     * @var  string  modifiers like abstract, final
     */
    public $modifiers;

    /**
     * @var  string  description of the class from the comment
     */
    public $description;

    /**
     * @var  array  array of tags, retrieved from the comment
     */
    public $tags = array();

    /**
     * @var  array  array of this classes constants
     */
    public $constants = array();

    /**
     * @var array Parent classes/interfaces of this class/interface
     */
    public $parents = array();

    /**
     * undocumented function
     *
     * @return  void
     * @author  Neil Brayfield
     **/
    public function __construct($class)
    {
        $this->class = new \ReflectionClass($class);

        $this->name = str_replace($this->class->getNamespaceName().'\\', '', $this->class->name);

        if ($modifiers = $this->class->getModifiers()) {
            $this->modifiers = implode(' ', \Reflection::getModifierNames($modifiers)).' ';
        }

        $this->constants = $this->class->getConstants();

        // If ReflectionClass::getParentClass() won't work if the class in
        // question is an interface
        if ($this->class->isInterface()) {
            $this->parents = $this->class->getInterfaces();
        } else {
            $parent = $this->class;

            while ($parent = $parent->getParentClass()) {
                $this->parents[] = $parent;
            }
        }

        if (!$comment = $this->class->getDocComment()) {
            foreach ($this->parents as $parent) {
                if ($comment = $parent->getDocComment()) {
                    // Found a description for this class
                    break;
                }
            }
        }

        list($this->description, $this->tags) = $this->userguide->parse($comment);

    }


    /**
     * undocumented function
     *
     * @return  void
     * @author  Neil Brayfield
     **/
    public function parentLinks()
    {
        $return = array();

        foreach ($this->parents as $parent) {
            if (strpos($parent->name, 'Phalcana') === 0) {
                $link = $this->url->get('guide/api/').str_replace('\\', '_', $parent->name);
            } elseif (strpos($parent->name, 'Phalcon') === 0) {
                $link = 'http://docs.phalconphp.com/en/latest/api/'.str_replace('\\', '_', $parent->name).'.html';
            } else {
                $link = false;
            }
            $return[$parent->name] = $link;
        }

        return $return;
    }

    /**
     * undocumented function
     *
     * @return  void
     * @author  Neil Brayfield
     **/
    public function interfaceLinks()
    {
        $return = array();

        foreach ($this->class->getInterfaceNames() as $interface) {
            if (strpos($interface, 'Phalcana') === 0) {
                $link = $this->url->get('url')->get('guide/api/').str_replace('\\', '_', $interface);
            } elseif (strpos($interface, 'Phalcon') === 0) {
                $link = 'http://docs.phalconphp.com/en/latest/api/'.str_replace('\\', '_', $interface).'.html';
            } else {
                $link = false;
            }
            $return[$interface] = $link;
        }

        return $return;
    }


    /**
     * Get the description of this class as HTML.
     *
     * @return  string  HTML
     */
    public function description()
    {
        $result = $this->description;

        return $this->markdown->transform($result);
    }


    /**
     * Gets a sorted list of constants
     *
     * @return  array   Constants
     **/
    public function constants()
    {
        $constants = $this->class->getConstants();

        foreach ($constants as &$const) {
            $const = Debug::vars($const);
        }

        ksort($constants);

        return $constants;

    }

    /**
     * Gets a sorted list of properties
     *
     * @return  array   properties
     **/
    public function properties()
    {
        $props = $this->class->getProperties();

        $defaults = $this->class->getDefaultProperties();

        usort($props, array($this,'_prop_sort'));

        foreach ($props as $key => $property) {
            // Create Kodoc Properties for each property
            $props[$key] = new DocProperty($this->class->name, $property->name, @$defaults[$property->name]);
        }

        return $props;
    }

    protected function _prop_sort($a, $b)
    {
        // If one property is public, and the other is not, it goes on top
        if ($a->isPublic() && ( ! $b->isPublic())) {
            return -1;
        }
        if ($b->isPublic() && ( ! $a->isPublic())) {
            return 1;
        }

        // If one property is protected and the other is private, it goes on top
        if ($a->isProtected() && $b->isPrivate()) {
            return -1;
        }
        if ($b->isProtected() && $a->isPrivate()) {
            return 1;
        }

        // Otherwise just do alphabetical
        return strcmp($a->name, $b->name);
    }


    /**
     * Gets a list of the class properties as [Kodoc_Method] objects.
     *
     * @return  array
     */
    public function methods()
    {
        $methods = $this->class->getMethods();

        usort($methods, array($this,'_method_sort'));

        foreach ($methods as $key => $method) {
            $methods[$key] = new DocMethod($this->class->name, $method->name);
        }

        return $methods;
    }

    /**
     * Sort methods based on their visibility and declaring class based on:
     *
     *  * methods will be sorted public, protected, then private.
     *  * methods that are declared by an ancestor will be after classes
     *    declared by the current class
     *  * lastly, they will be sorted alphabetically
     *
     */
    protected function _method_sort($a, $b)
    {
        // If one method is public, and the other is not, it goes on top
        if ($a->isPublic() && ( ! $b->isPublic())) {
            return -1;
        }
        if ($b->isPublic() && ( ! $a->isPublic())) {
            return 1;
        }

        // If one method is protected and the other is private, it goes on top
        if ($a->isProtected() && $b->isPrivate()) {
            return -1;
        }
        if ($b->isProtected() && $a->isPrivate()) {
            return 1;
        }

        // The methods have the same visibility, so check the declaring class depth:


        /*
        echo Debug::vars('a is '.$a->class.'::'.$a->name,'b is '.$b->class.'::'.$b->name,
                           'are the classes the same?', $a->class == $b->class,'if they are, the result is:',strcmp($a->name, $b->name),
                           'is a this class?', $a->name == $this->class->name,-1,
                           'is b this class?', $b->name == $this->class->name,1,
                           'otherwise, the result is:',strcmp($a->class, $b->class)
                           );
        */

        // If both methods are defined in the same class, just compare the method names
        if ($a->class == $b->class) {
            return strcmp($a->name, $b->name);
        }

        // If one of them was declared by this class, it needs to be on top
        if ($a->name == $this->class->name) {
            return -1;
        }
        if ($b->name == $this->class->name) {
            return 1;
        }

        // Otherwise, get the parents of each methods declaring class, then compare which function has more "ancestors"
        $adepth = 0;
        $bdepth = 0;

        $parent = $a->getDeclaringClass();
        do {
            $adepth++;
        } while ($parent = $parent->getParentClass());

        $parent = $b->getDeclaringClass();
        do {
            $bdepth++;
        } while ($parent = $parent->getParentClass());

        return $bdepth - $adepth;
    }



    /**
     * undocumented function
     *
     * @return  void
     * @author  Neil Brayfield
     **/
    public function filename()
    {
        if (!$this->class->getFilename()) {
            return false;
        } else {
            return Debug::path($this->class->getFilename());
        }
    }
}
