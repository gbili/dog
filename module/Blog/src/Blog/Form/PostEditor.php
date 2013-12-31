<?php
namespace Blog\Form;

class PostEditor extends \Zend\Form\Form 
{
    public function __construct($sm)
    {
        parent::__construct('form-post-editor');

        //Add the user fieldset, and set it as the base fieldset
        $postFieldset = new Fieldset\Post($sm);
        $postFieldset->setUseAsBaseFieldset(true);

        $this->add($postFieldset);

        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'security',
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Save',
                'id' => 'submitbutton',
                'class' => 'btn btn-default', 
            ),
        ));
    }
}
