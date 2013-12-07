<?php
namespace Blog\Form;

class CategoryCreate extends \Zend\Form\Form 
{
    public function __construct(\Doctrine\Common\Persistence\ObjectManager $objectManager)
    {
        parent::__construct('form-category-create');

        // The form will hydrate an object of type Post
        $this->setHydrator(new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($objectManager));
        
        //Add the user fieldset, and set it as the base fieldset
        $categoryFieldset = new Fieldset\Category($objectManager);
        $categoryFieldset->setUseAsBaseFieldset(true);
        $this->add($categoryFieldset);

        // ... add CSRF and submit elements
        $this->add(array(
            'name' => 'send',
            'attributes' => array(
                'type'  => 'Submit',
                'value' => 'Save',
                'id' => 'submitbutton',
            ),
        ));
        // Optionally set your validation group here
    }
}
