<?php
namespace Dogtore\Form\Fieldset;

class Dog extends \Zend\Form\Fieldset 
implements \Zend\InputFilter\InputFilterProviderInterface
{
    public function __construct($sm)
    {
        parent::__construct('name');

        $objectManager = $sm->get('Doctrine\ORM\EntityManager');
        $lang = $sm->get('lang');

        $this->setHydrator(new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($objectManager))
             ->setObject(new \Blog\Entity\PostData());
        
        $this->add(array(
            'name' => 'id',
            'type'  => 'Zend\Form\Element\Hidden',
        ));

        $this->add(array(
            'name' => 'name',
            'type'  => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Name'
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Your dog\'s name',
            )
        ));

        $this->add(array(
            'name' => 'birthdate',
            'type'  => 'Zend\Form\Element\Date',
            'options' => array(
                'label' => 'Birth date'
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'datepicker',
            )
        ));
        
        $this->add(array(
            'name' => 'gender',
            'type'  => 'Blog\Form\Element\Select',
            'options' => array(
                'label' => 'Gender',
                'empty_option' => '---',
                'value_options' => array(
                    'm' => 'Male',
                    'f' => 'Female',
                ),
            ),
            'attributes' => array(
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'name' => 'breed',
            'type'  => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Breed'
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Labrador, Chihuahua, Bull Terrier etc.',
            )
        ));

        $this->add(array(
            'name' => 'color',
            'type'  => 'Zend\Form\Element\Color',
            'options' => array(
                'label' => 'Color',
                'helper_method' => 'formColor',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'colpick'
            )
        ));

        $this->add(array(
            'name' => 'weightkg',
            'type'  => 'Zend\Form\Element\Number',
            'options' => array(
                'label' => 'Weight (Kg)'
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => '10 or 25 etc.',
                'min' => '1',
                'max' => '150',
                'step' => '1',
            )
        ));

        $this->add(array(
            'name' => 'whythisdog',
            'type'  => 'Zend\Form\Element\Textarea',
            'options' => array(
                'label' => 'Why this dog?',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'rows' => '8',
                'placeholder' => 'Tell people why you chose your dog. Was it the breed? Was it because of a great story?',
            )
        ));

        $this->add(array(
            'name' => 'media',
            'type' => 'Blog\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Picture',
                'property' => 'slug',
                'attributes' => array(
                    'data-img-src' => 'src'
                ),
                'is_method' => true,
                'target_class' => 'Blog\Entity\Media',
                'object_manager' => $objectManager,
                'display_empty_item' => true,
                'empty_item_label' => '---',
            ),
            'attributes' => array(
                'class' => 'image-picker masonry',
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

            'birthdate' => array(
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'Date',
                    ),
                ),
            ),

            'breed' => array(
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

            'color' => array(
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array(
                        'name' => 'Callback',
                        'options' => array(
                            'callback' => function ($value) {
                                return preg_replace('/#/', '', $value);
                            },
                        ),
                    ),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 6,
                            'max'      => 7,
                        ),
                    ),
                    array(
                        'name'    => 'Regex',
                        'options' => array(
                            'pattern' => '/[0-9ABCDEF]{6}/i'
                        ),
                    ),
                ),
            ),

            'gender' => array(
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
                            'max'      => 1,
                        ),
                    ),
                ),
            ),

            'whythisdog' => array(
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
                            'max'      => 700,
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