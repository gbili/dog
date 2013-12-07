<?php
namespace Dogtore\Form;

class Search extends \Zend\Form\Form 
{
    public function __construct(\Doctrine\Common\Persistence\ObjectManager $objectManager)
    {
        parent::__construct('search');
        
        //Add the user fieldset, and set it as the base fieldset
        $searchFieldset = new Fieldset\Search($objectManager);
        $searchFieldset->setUseAsBaseFieldset(true);
        $this->add($searchFieldset);

        // ... add CSRF and submit elements
        // Optionally set your validation group here
    }
}
