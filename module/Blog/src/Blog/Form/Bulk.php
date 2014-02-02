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

        $bulkActionValueOptions = $this->getOption('bulk_action_value_options');
        if (null === $bulkActionValueOptions || !is_array($bulkActionValueOptions)) {
            throw new \Exception('In order to extend Blog\Form\Bulk, you must pass an bulk_action_value_options in the options constructor param');
        }
        
        $this->add(array(
            'name' => 'action-top',
            'type'  => 'Blog\Form\Element\Select',
            'options' => array(
                'empty_option' => 'Bulk Actions',
                'value_options' => $bulkActionValueOptions,
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
                'value_options' => $bulkActionValueOptions,
            ),
            'attributes' => array(
                'class' => 'form-control',
            ),
        ));

        //This element represents the posts or categories or files etc. 
        //being marked to apply the bulk action on
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

    public function hydrateValueOptions(array $entities)
    {
        $valueOptions = array();
        foreach ($entities as $entity) {
            $id = ((!is_array($entity))? $entity->getId() : $entity['id']);
            $valueOptions[] = array('label' => '', 'value' => $id);
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
                    array(
                        'type' => 'Regex',
                        'options' => array(
                            'pattern' => '/[a-z][a-zA-Z]+/'
                        ),
                    )
                ),
            ),

            'action-bottom' => array(
                'required' => false,
                'validators' => array(
                    array(
                        'type' => 'Regex',
                        'options' => array(
                            'pattern' => '/[a-z][a-zA-Z]+/'
                        ),
                    )
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
