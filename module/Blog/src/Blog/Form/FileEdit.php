<?php
namespace Blog\Form;

class FileEdit extends \Zend\Form\Form 
{
    public function __construct(\Doctrine\Common\Persistence\ObjectManager $objectManager)
    {
        parent::__construct('form-file-edit');

        // The form will hydrate an object of type Post
        $this->setHydrator(new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($objectManager));
        
        //Add the user fieldset, and set it as the base fieldset
        $fileFieldset = new Fieldset\File($objectManager);
        $fileFieldset->setUseAsBaseFieldset(true);
        $this->add($fileFieldset);

        // ... add CSRF and submit elements
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Save',
                'id' => 'submitbutton',
                'class' => 'btn btn-default', 
            ),
        ));
        // Optionally set your validation group here
    }
}
