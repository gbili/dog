<?php
namespace Blog\Form;

class MediaLink extends \Zend\Form\Form 
{
    public function __construct(\Doctrine\Common\Persistence\ObjectManager $objectManager)
    {
        parent::__construct('form-media-link');

        // The form will hydrate an object of type Post
        $this->setHydrator(new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($objectManager));
        
        //Add the user fieldset, and set it as the base fieldset
        $fieldset = new Fieldset\MediaLink($objectManager);
        $fieldset->setUseAsBaseFieldset(true);
        $this->add($fieldset);

        // ... add CSRF and submit elements
        // Optionally set your validation group here

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Link',
                'id' => 'submitbutton',
                'class' => 'btn btn-default', 
            ),
        ));
    }
}
