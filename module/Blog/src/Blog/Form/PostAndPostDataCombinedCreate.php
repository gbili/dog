<?php
namespace Blog\Form;

class PostAndPostDataCombinedCreate extends \Zend\Form\Form 
{
    public function __construct($sm)
    {
        parent::__construct('form-post-create');

        $objectManager = $sm->get('Doctrine\ORM\EntityManager');
        // The form will hydrate an object of type Post
        $this->setHydrator(new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($objectManager));
        
        //Add the user fieldset, and set it as the base fieldset
        $postFieldset = new Fieldset\Post($sm);
        $this->add($postFieldset);
        
        $postDataFieldset = new Fieldset\PostData($sm);
        $this->add($postDataFieldset);

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
