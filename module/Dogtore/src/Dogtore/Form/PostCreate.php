<?php
namespace Dogtore\Form;

class PostCreate extends \Zend\Form\Form
{
    public function __construct($name = null)
    {
        parent::__construct('post');// not post like in POST, but post like in blogpost

        $this->setAttribute('method', 'POST');

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
            'name' => 'quantity',
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
                'value' => 'Create',
                'id' => 'submitbutton',
            ),
        ));
    }
}
