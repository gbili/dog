<?php
namespace Blog\Form\Fieldset;

class Post extends \Zend\Form\Fieldset 
implements \Zend\InputFilter\InputFilterProviderInterface
{
    public function __construct(\Doctrine\Common\Persistence\ObjectManager $objectManager)
    {
        parent::__construct('post');

        $this->setHydrator(new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($objectManager))
             ->setObject(new \Blog\Entity\Post());
        
        $this->add(array(
            'name' => 'id',
            'type'  => 'Zend\Form\Element\Hidden',
        ));

        $this->add(array(
            'name' => 'slug',
            'type'  => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Slug'
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'the-slug-will-be-shown-in-url',
            )
        ));

        $this->add(array(
            'name' => 'parent',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Parent Post',
                'property' => 'slug',
                'target_class' => 'Blog\Entity\Post',
                'object_manager' => $objectManager,
                'display_empty_item' => true,
                'empty_item_label' => '---',
            ),
            'attributes' => array(
                'placeholder' => 'the-slug-will-be-shown-in-url',
                'class' => 'form-control',
            )
        ));

        $this->add(array(
            'name' => 'category',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Category',
                'property' => 'name',
                'target_class' => 'Blog\Entity\Category',
                'object_manager' => $objectManager,
            ),
            'attributes' => array(
                'class' => 'form-control'
            )
        ));
    }

    public function getInputFilterSpecification()
    {
        return array(
            'id' => array(
                'required' => false,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ),

            'slug' => array(
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'Regex',
                        'options' => array(
                            'pattern'      => '/[a-z]+[a-z-]+[a-z]+/',
                        ),
                    ),
                ),
            ),

            'parent' => array(
                'required' => false,
            ),

            'category' => array(
                'required' => false,
            ),
        );
    }
}
