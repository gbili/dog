<?php
namespace Blog\Form\Fieldset;

class File extends \Zend\Form\Fieldset 
    implements \Zend\InputFilter\InputFilterProviderInterface
{
    public function __construct(\Doctrine\Common\Persistence\ObjectManager $objectManager)
    {
        parent::__construct('file');

        $this->setHydrator(new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($objectManager))
             ->setObject(new \Blog\Entity\File());
        
        $this->add(array(
            'name' => 'id',
            'type'  => 'Zend\Form\Element\Hidden',
        ));

        $this->add(array(
            'name' => 'name',
            'type'  => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'File Name'
            ),
            'attributes' => array(
                'class' => 'form-control'
            )
        ));

/*        $this->add(array(
            'name' => 'uri',
            'type'  => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Uri'
            ),
            'attributes' => array(
                'class' => 'form-control'
            )
        )); */
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

            'name' => array(
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 255,
                        ),
                    ),
                ),
            ),

/*            'uri' => array(
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 255,
                        ),
                    ),
                ),
            ), */
        );
    }
}
