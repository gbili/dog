<?php
namespace Blog\Form;

class PostCreate extends \Zend\Form\Form 
{
    public function __construct(\Doctrine\Common\Persistence\ObjectManager $objectManager)
    {
        parent::__construct('form-post-create');

        // The form will hydrate an object of type Post
        $this->setHydrator(new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($objectManager));
        
        //Add the user fieldset, and set it as the base fieldset
        $postFieldset = new Fieldset\Post($objectManager);
        $postFieldset->setUseAsBaseFieldset(true);
        $this->add($postFieldset);

        // ... add CSRF and submit elements
        // Optionally set your validation group here

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