<?php
namespace Blog\Form\Fieldset;

class MediaLink extends \Zend\Form\Fieldset 
    implements \Zend\InputFilter\InputFilterProviderInterface
{
    public function __construct(\Doctrine\Common\Persistence\ObjectManager $objectManager)
    {
        parent::__construct('media');

        $this->setHydrator(new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($objectManager))
             ->setObject(new \Blog\Entity\Media());
        
        $this->add(array(
            'name' => 'id',
            'type'  => 'Zend\Form\Element\Hidden',
        ));

        $this->add(array(
            'name' => 'posts',
            'type' => 'DoctrineModule\Form\Element\ObjectMultiCheckbox',
            'options' => array(
                'label' => 'Link to Posts',
                'property' => 'title',
                'target_class' => 'Blog\Entity\Post',
                'object_manager' => $objectManager,
            ),
            'attributes' => array(
                'class' => 'checkbox',
            )
        ));
        $this->get('posts')->setLabelAttributes(array('class' => 'checkbox'));
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

            'posts' => array(
                'required' => true,
            ),
        );
    }
}
