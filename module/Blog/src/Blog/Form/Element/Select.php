<?php
namespace Blog\Form\Element;

class Select extends \Zend\Form\Element\Select
{
    public function getInputSpecification()
    {
        $spec = array(
            'name' => $this->getName(),
            'required' => false,
        );
        
        if ($validator = $this->getValidator()) {
           $spec['validators'] = array(
               $validator,
           );
        }

       return $spec;
    }
}
