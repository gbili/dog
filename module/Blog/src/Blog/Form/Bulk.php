<?php
namespace Blog\Form;

class Bulk extends \Zend\Form\Form 
{
    public function __construct($name, array $options)
    {
        parent::__construct('bulk-action', $options);

        $multicheckElementName = $this->getOption('multicheck_element_name');
        if (null === $multicheckElementName || !is_string($multicheckElementName)) {
            throw new \Exception('In order to extend Blog\Form\Bulk, you must pass an multicheck_element_name in the options constructor param');
        }
        
        $this->add(array(
            'name' => 'action-top',
            'type'  => 'Blog\Form\Element\Select',
            'options' => array(
                'empty_option' => 'Bulk Actions',
                'value_options' => array(
                    'linkTranslations' => 'Link Translations',
                    'deletePosts' => 'Delete',
                ),
            ),
            'attributes' => array(
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'name' => 'action-bottom',
            'type'  => 'Blog\Form\Element\Select',
            'options' => array(
                'empty_option' => 'Bulk Actions',
                'value_options' => array(
                    'linkTranslations' => 'Link Translations',
                    'deletePosts' => 'Delete',
                ),
            ),
            'attributes' => array(
                'class' => 'form-control',
            ),
        ));

        //Hydrated in self::hydrateValueOptions($entitiesAsArray)
        $this->add(array(
            'name' => $multicheckElementName,
            'type'  => 'Zend\Form\Element\MultiCheckbox',
            'options' => array(
                'label' => 'Mark',
                'value_options' => array(
                ),
            ),
            'attributes' => array(
                'class' => 'form-control input-sm',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Apply',
                'id' => 'submitbutton',
                'class' => 'btn btn-default', 
            ),
        ));
    }

    public function getSelectedAction()
    {
        if (!$this->isValid()) {
            throw new \Exception('Form is not valid');
        }
        $data = $this->getData();
        if ('' !== $data['action-top']) {
            return $data['action-top'];
        }
        if ('' !== $data['action-bottom']) {
            return $data['action-bottom'];
        }
        throw new \Exception('No Action was selected');
    }

    public function hydrateValueOptions(array $entitiesAsArray)
    {
        $valueOptions = array();
        foreach ($entitiesAsArray as $entitiyAsArray) {
            $valueOptions[] = array('label' => '', 'value' => $entitiyAsArray['id']);
        }
        $this->get($this->getOption('multicheck_element_name'))->setValueOptions($valueOptions);
        return $this;
    }


    public function getInputSpecification()
    {
        return array(
            'action-top' => array(
                'required' => false,
                'validators' => array(
                    array('type' => 'Alnum'),
                ),
            ),

            'action-bottom' => array(
                'required' => false,
                'validators' => array(
                    array('type' => 'Alnum'),
                ),
            ),

            $this->getOption('multicheck_element_name') => array(
                'required' => true,
                'validators' => array(
                    array('type' => 'Int'),
                ),
            ),
        );
    }
}
