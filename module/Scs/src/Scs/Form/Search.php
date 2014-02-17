<?php
namespace Scs\Form;

class Search extends \Zend\Form\Form 
{
    protected $validData;
    protected $categoryName;

    public function __construct($name, array $options=array())
    {
        parent::__construct($name, $options);

        // ... add CSRF and submit elements
        // Optionally set your validation group here

        $this->add(array(
            'name' => 't', //terms
            'type'  => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'search'
            ),
            'attributes' => array(
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'name' => 'c', //category
            'type'  => 'Zend\Form\Element\Button',
            'attributes' => array(
                'class' => 'btn btn-success',
                'type' => 'submit',
            )
        ));
    }

    public function getInputFilterSpecification()
    {
        return array(
            't' => array(//terms
                'required' => true,
                'filters'  => array(
                    /*array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),*/
                ),
                'validators' => array(
                   array(
                        'name'    => 'Regex',
                        'options' => array(
                            'pattern' => '/([A-Za-z0-9?!- ]{3,})/',
                        ),
                    ),
                ),
            ),
            'c' => array(//category
                'required' => false,
                'validators' => array(
                   array(
                        'name'    => 'Regex',
                        'options' => array(
                            'pattern' => '/([a-z-]+)/',
                        ),
                    ),
                ),
            ),
        );
    }

    public function isValid()
    {
        if ($this->hasValidated) {
            return $this->isValid;
        }
        return $this->isValid = (parent::isValid() && ($this->hasTerms() || $this->hasCategory()));
    }

    public function getElementData($elementName)
    {
        if (null === $this->validData) {
            $this->validData = $this->getData();
        }
        if (!array_key_exists($elementName, $this->validData)) {
            throw new \Exception('Element with name ' . $elementName . ' is not set in ' . __CLASS__ . ' : ' . $this->validData . ' data');
        }
        return $this->validData[$elementName];
    }

    /*
     * TODO beware of the strlen check
     */
    public function hasTerms()
    {
        $terms = $this->getElementData('t');
        return (null !== $terms && '' !== $terms);
    }

    public function hasCategory()
    {
        return null !== $this->getElementData('c');
    }
}
