<?php
namespace Blog\Form\Fieldset;

class MediaMetadata extends \Zend\Form\Fieldset 
    implements \Zend\InputFilter\InputFilterProviderInterface
{
    public function __construct($sm)
    {
        parent::__construct('metadata');

        $objectManager = $sm->get('Doctrine\ORM\EntityManager');
        $authService   = $sm->get('Zend\Authentication\AuthenticationService');
        $user = $authService->getIdentity();

        $this->setHydrator(new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($objectManager))
             ->setObject(new \Blog\Entity\MediaMetadata());
        
        $this->add(array(
            'name' => 'id',
            'type'  => 'Zend\Form\Element\Hidden',
        ));

        $this->add(array(
            'name' => 'media',
            'type' => 'Blog\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Picture',
                'property' => 'slug',
                'attributes' => array(
                    'data-img-src' => 'src',
                ),
                'form_group_class' => 'well',
                'is_method' => true,
                'target_class' => 'Blog\Entity\Media',
                'object_manager' => $objectManager,
                'is_method' => true,
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array('user' => $user),
                    ),
                ),
                'display_empty_item' => true,
                'empty_item_label' => '---',
            ),
            'attributes' => array(
                'class' => 'image-picker masonry',
            )
        ));

        $this->add(array(
            'name' => 'alt',
            'type'  => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Alt'
            ),
            'attributes' => array(
                'placeholder' => 'Alternate text',
                'class' => 'form-control',
            )
        ));

        $this->add(array(
            'name' => 'description',
            'type'  => 'Zend\Form\Element\Textarea',
            'options' => array(
                'label' => 'Description',
            ),
            'attributes' => array(
                'placeholder' => 'Descriptions are usefull for search engines...',
                'class' => 'form-control',
                'rows' => '3',
            )
        ));
    }

    /** 
     * This relies on the fact that the file id is passed
     * in the form action as a uri parameter
     * Then that parameter has to be merged in the post data
     * as the post id in the 'file'
     * 
     * Note if the hidden element value could be preset, it
     * would be much simpler.
     */
    public function turnFileSelectorIntoHidden()
    {
        $this->remove('file');
        $this->add(array(
            'name' => 'file',
            'type'  => 'Zend\Form\Element\Hidden',
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

            'media' => array(
                'required' => true,
            ),

            'alt' => array(
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

            'description' => array(
                'required' => false,
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
        );
    }
}
