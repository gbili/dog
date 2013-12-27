<?php
namespace Dogtore\Form;

class PostEdit extends \Zend\Form\Form
{
    public function __construct($name = null)
    {
        parent::__construct('post');

        //$this->setAttribute('method', 'POST');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

       $this->add( array(
            'name' => 'type',
            'type' => 'Select',
            'options' => array(
                'label' => 'Type',
                'value_options' => array(
                    'Symptom'  => 'Symptom',
                    'Cause'    => 'Cause',
                    'Solution' => 'Solution',
                    'Wiki'     => 'Wiki',
                ),
            ),
        ));

        $this->add(array(
            'name' => 'title',
            'type' => 'Text',
            'options' => array(
                'label' => 'Title'
            ),
        ));

        $this->add(array(
            'name' => 'content',
            'type' => 'Textarea',
            'options' => array(
                'label' => 'Content'
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Edit',
                'id' => 'submitbutton',
            ),
        ));
    }
}
