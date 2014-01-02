<?php
namespace Blog\Form;

class Post extends \Zend\Form\Form 
{
    public function __construct($name)
    {
        parent::__construct('bulk-action');
        
        $this->add(array(
            'name' => 'action',
            'type'  => 'Zend\Form\Element\Select',
            'options' => array(
                'empty_option' => 'Bulk Actions',
                'value_options' => array(
                    'linkTranslations' => 'Link Translations',
                    'deletePosts' => 'Delete',
                ),
            ),
            'attributes' => array(
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'name' => 'posts',
            'type'  => 'Zend\Form\Element\MultiCheckbox',
            'options' => array(
                'label' => 'Mark',
                'value_options' => array(
                ),
            ),
            'attributes' => array(
                'class' => 'form-control input-sm',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Apply',
                'id' => 'submitbutton',
                'class' => 'btn btn-default', 
            ),
        ));
    }

    public function getInputSpecification()
    {
        return array(
            'action-select' => array(
                'required' => true,
                'validators' => array(
                    'type' => 'Int',
                ),
            ),
            'multicheck' => array(
                'required' => true,
                'validators' => array(
                    'type' => 'Int',
                ),
            ),
        );
    }
}
