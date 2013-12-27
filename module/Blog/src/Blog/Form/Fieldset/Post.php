<?php
namespace Blog\Form\Fieldset;

class Post extends \Zend\Form\Fieldset 
implements \Zend\InputFilter\InputFilterProviderInterface
{
    public function __construct($sm)
    {
        parent::__construct('post');

        $objectManager = $sm->get('Doctrine\ORM\EntityManager');
        $lang = $sm->get('lang')->getLang();

        $this->setHydrator(new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($objectManager))
             ->setObject(new \Blog\Entity\Post());
        
        $this->add(array(
            'name' => 'id',
            'type'  => 'Zend\Form\Element\Hidden',
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
                'is_method' => true,
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array('locale' => $lang),
                    ),
                ),
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
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array('locale' => $lang),
                    ),
                ),
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

            'parent' => array(
                'required' => false,
            ),

            'category' => array(
                'required' => false,
            ),
        );
    }
}
