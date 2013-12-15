<?php
namespace Blog\Form\Fieldset;

class PostLinkable extends Post 
implements \Zend\InputFilter\InputFilterProviderInterface
{
    public function __construct(\Doctrine\Common\Persistence\ObjectManager $objectManager)
    {
        parent::__construct($objectManager);

        $this->add(array(
            'name' => 'data',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Linked data ( this post is a representation of this data)',
                'property' => 'title',
                'target_class' => 'Blog\Entity\PostData',
                'object_manager' => $objectManager,
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
