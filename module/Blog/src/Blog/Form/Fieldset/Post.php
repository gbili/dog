<?php
namespace Blog\Form\Fieldset;

class Post extends \Zend\Form\Fieldset 
implements \Zend\InputFilter\InputFilterProviderInterface
{
    public function __construct($sm)
    {
        parent::__construct('post');

        $objectManager = $sm->get('Doctrine\ORM\EntityManager');

        $this->setHydrator(new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($objectManager))
             ->setObject(new \Blog\Entity\Post());
        
        $this->add(array(
            'name' => 'id',
            'type'  => 'Zend\Form\Element\Hidden',
        ));

        $this->add(array(
            'name' => 'parent',
            'type'  => 'Zend\Form\Element\Hidden',
        ));

        $this->add(array(
            'name' => 'slug',
            'type'  => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'class' => 'slugicize_put_slug_here',
            ),
        ));

        $this->add(array(
            'name' => 'categoryslug',
            'type' => 'Zend\Form\Element\Radio',
            'options' => array(
                'helper_method' => 'renderCustomizableOptionsRadio',
                'translate' => array(
                    'label' => false,
                ),
                'label' => 'Category',
                'value_option_label_attributes' => array(
                    'class' => 'radio-inline',
                ),
                'value_options' => $sm->get('scs')->getCategoriesToTranslated($translationsAsNames=true),
            ),
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
                'validators' => array(
                    array(
                        'name'    => 'Regex',
                        'options' => array(
                            'pattern'      => '/\\A[a-z0-9](:?[-]?[a-z0-9]+)*\\z/',
                        ),
                    ),
                ),
            ),

            'parent' => array(
                'required' => false,
            ),

            'categoryslug' => array(
                'required' => true,
            ),
        );
    }
}
