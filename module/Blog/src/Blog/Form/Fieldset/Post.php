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
            'name' => 'slug',
            'type'  => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Slug'
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'the-post-title-without-special-chars',
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

        $this->add(new PostData($sm));
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
                            'pattern'      => '/[a-z0-9]+[a-z0-9-]+[a-z0-9]+/',
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
