<?php
namespace Blog\Form;

class MediaCreate extends \Zend\Form\Form 
{
    public function __construct(\Doctrine\Common\Persistence\ObjectManager $objectManager)
    {
        parent::__construct('form-media-link');

        // The form will hydrate an object of type Post
        $this->setHydrator(new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($objectManager));
        
        //Add the user fieldset, and set it as the base fieldset
        $postFieldset = new Fieldset\Media($objectManager);
        $postFieldset->setUseAsBaseFieldset(true);
        $this->add($postFieldset);

        // ... add CSRF and submit elements
        // Optionally set your validation group here

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Create',
                'id' => 'submitbutton',
                'class' => 'btn btn-default', 
            ),
        ));
    }
}
