<?php
namespace Dogtore\Form;

class DogMedias extends \Zend\Form\Form 
{
    public function __construct($sm)
    {
        parent::__construct('form-dog-medias');

        //Add the user fieldset, and set it as the base fieldset
        $dogFieldset = new Fieldset\DogMedias($sm);
        $dogFieldset->setUseAsBaseFieldset(true);

        $this->add($dogFieldset);

        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'security',
        ));
    }
}
