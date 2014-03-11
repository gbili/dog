<?php
namespace Dogtore\Form;

class DogEditor extends \Zend\Form\Form 
{
    public function __construct($sm)
    {
        parent::__construct('form-dog-editor');

        //Add the user fieldset, and set it as the base fieldset
        $dogFieldset = new Fieldset\Dog($sm);
        $dogFieldset->setUseAsBaseFieldset(true);

        $this->add($dogFieldset);

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
                'class' => 'btn btn-primary', 
            ),
        ));
    }
}
