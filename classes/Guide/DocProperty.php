<?php

namespace Phalcana\Guide;

use Phalcana\Debug;
use Phalcon\Di\Injectable;

/**
 * Class property documentation generator.
 *
 * @package    Userguide
 * @category   Reflection
 * @author     Kohana Team
 * @author     Neil Brayfield <neil@brayfield.uk>
 * @copyright  (c) 2008-2013 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class DocProperty extends Injectable
{

    /**
     * @var  object  ReflectionProperty
     */
    public $property;

    /**
     * @var  string   modifiers: public, private, static, etc
     */
    public $modifiers = 'public';

    /**
     * @var  string  variable type, retrieved from the comment
     */
    public $type;

    /**
     * @var  string  value of the property
     */
    public $value;

    /**
     * @var  string  default value of the property
     */
    public $default;

    public function __construct($class, $property, $default = null)
    {
        $property = new \ReflectionProperty($class, $property);

        list($description, $tags) = $this->userguide->parse($property->getDocComment());

        $this->description = $description;

        if ($modifiers = $property->getModifiers()) {
            $this->modifiers = '<small>'.implode(' ', \Reflection::getModifierNames($modifiers)).'</small> ';
        }

        if (isset($tags['var'])) {
            if (preg_match('/^(\S*)(?:\s*(.+?))?$/s', $tags['var'][0], $matches)) {
                $this->type = $matches[1];

                if (isset($matches[2])) {
                    $this->description = $this->markdown->transform($matches[2]);
                }
            }
        }

        $this->property = $property;

        // Show the value of static properties, but only if they are public or we are php 5.3 or
        // higher and can force them to be accessible
        if ($property->isStatic()) {
            $property->setAccessible(true);

            // Don't debug the entire object, just say what kind of object it is
            if (is_object($property->getValue($class))) {
                $this->value = '<pre>object '.get_class($property->getValue($class)).'()</pre>';
            } else {
                $this->value = Debug::vars($property->getValue($class));
            }

        }

        // Store the defult property
        $this->default = Debug::vars($default);
    }
}
