<?php
namespace Blog\Form\Fieldset;

class PostData extends \Zend\Form\Fieldset 
implements \Zend\InputFilter\InputFilterProviderInterface
{
    public function __construct(\Doctrine\Common\Persistence\ObjectManager $objectManager)
    {
        parent::__construct('post-data');

        $this->setHydrator(new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($objectManager))
             ->setObject(new \Blog\Entity\PostData());
        
        $this->add(array(
            'name' => 'id',
            'type'  => 'Zend\Form\Element\Hidden',
        ));

        $this->add(array(
            'name' => 'title',
            'type'  => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Title'
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'The post title',
            )
        ));

        $this->add(array(
            'name' => 'content',
            'type'  => 'Zend\Form\Element\Textarea',
            'options' => array(
                'label' => 'Content',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'rows' => '8',
                'placeholder' => 'Write cool content..',
            )
        ));

        $this->add(array(
            'name' => 'media',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Featured Image',
                'property' => 'slug',
                'target_class' => 'Blog\Entity\Media',
                'object_manager' => $objectManager,
                'display_empty_item' => true,
                'empty_item_label' => '---',
            ),
            'attributes' => array(
                'class' => 'form-control',
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

            'title' => array(
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

            'content' => array(
                'required' => true,
                'filters'  => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                        ),
                    ),
                ),
            ),

            'media' => array(
                'required' => false,
            ),
        );
    }
}
