<?php
namespace Blog\Form\Fieldset;

class PostLinkable extends Post 
implements \Zend\InputFilter\InputFilterProviderInterface
{
    public function __construct($sm)
    {
        parent::__construct($sm);

        $objectManager = $sm->get('Doctrine\ORM\EntityManager');
        $lang = $sm->get('lang')->getLang();

        $this->add(array(
            'name' => 'data',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Linked data ( this post is a representation of this data)',
                'property' => 'title',
                'target_class' => 'Blog\Entity\PostData',
                'object_manager' => $objectManager,
                'is_method' => true,
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array('locale' => $lang),
                    ),
                ),
            ),
            'attributes' => array(
                'class' => 'form-control',
            )
        ));
    }

    public function getInputFilterSpecification()
    {
        $parentSpec = parent::getInputFilterSpecification();
        $parentSpec['data'] = array(
            'required' => false,
            'filters'  => array(
                array('name' => 'Int'),
            ),
        );

        return $parentSpec;
    }
}
