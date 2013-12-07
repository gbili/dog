<?php
namespace Blog\Form\Fieldset;

class Category extends \Zend\Form\Fieldset 
    implements \Zend\InputFilter\InputFilterProviderInterface
{
    public function __construct(\Doctrine\Common\Persistence\ObjectManager $objectManager)
    {
        parent::__construct('category');

        $this->setHydrator(new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($objectManager))
             ->setObject(new \Blog\Entity\Category());
        

        $this->add(array(
            'name' => 'parent',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Parent',
                'property' => 'name',
                'target_class' => 'Blog\Entity\Category',
                'object_manager' => $objectManager,
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id',
        ));

        $this->add(array(
            'name' => 'name',
            'type'  => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Name'
            ),
        ));

        $this->add(array(
            'name' => 'slug',
            'type'  => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Slug'
            ),
        ));

        $this->add(array(
            'name' => 'description',
            'type'  => 'Zend\Form\Element\Textarea',
            'options' => array(
                'label' => 'Description',
            ),
        ));
    }

    public function getInputFilterSpecification()
    {
        return array(
            'id' => array(
                'required' => false,
            ),
            'name' => array(
                'required' => true,
            ),
            'slug' => array(
                'required' => true,
            ),
            'description' => array(
                'required' => false,
            ),
            'parent' => array(
                'required' => false,
            ),
        );
    }
}
