<?php
namespace Dogtore\InputFilter;

/*
use Zend\InputFilter\Factory;
use Zend\InputFilter\Input;
use Zend\I18n\Validator\Alnum;
use Zend\I18n\Validator\Int;
 */

class PostCreate extends \Zend\InputFilter\InputFilter
{
    public function __construct()
    {
        $factory = $this->getFactory();

        $this->add(
            $factory->createInput(
                array(
                    'name' => 'type',
                    'required' => true,
                    'filters' => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                    ),
                )
            )
        );

        $this->add(
            $factory->createInput(
                array(
                    'name' => 'title',
                    'required' => true,
                    'filters' => array(
                        array('name' => 'StripTags'),
                    ),
                )
            )
        );

        $this->add(
            $factory->createInput(
                array(
                    'name' => 'content',
                    'required' => true,
                    'filters' => array(
                        array('name' => 'StripTags'),
                    ),
                )
            )
        );
    }
}
