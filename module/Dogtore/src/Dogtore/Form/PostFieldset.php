<?php
namespace Dogtore\Form;

class PostFieldset extends \Zend\Form\Fieldset implements \Zend\InputFilter\InputFilterProviderInterface
{
    public function __construct($name = null)
    {
       parent::__construct('Post');// not post like in POST, but Post like in blogpost

       $this->setHydrator(new \Zend\Stdlib\Hydrator\ObjectProperty())
            ->setObject(new \Dogtore\Entity\Post());

       $this->setLabel('Post');

       $this->add( array(
            'name' => 'category',
            'type' => 'Select',
            'options' => array(
                'label' => 'Type',
                'value_options' => array(
                    'Symptom'  => 'Symptom',
                    'Cause'    => 'Cause',
                    'Solution' => 'Solution',
                    'Wiki'     => 'Wiki',
                ),
            ),
            'attributes' => array(
                'required' => 'required'
            ),
        ));

        $this->add(array(
            'name' => 'title',
            'type' => 'Text',
            'options' => array(
                'label' => 'Title'
            ),
            'attributes' => array(
                'required' => 'required',
            ),
        ));

        $this->add(array(
            'name' => 'content',
            'type' => 'Textarea',
            'options' => array(
                'label' => 'Content'
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Create',
                'id' => 'submitbutton',
            ),
        ));
    }

    public function getInputFilterSpecification()
    {
        return array(
            'category' => array(
                'required' => true,
            ),
            'title' => array(
                'required' => true,
            ),
            'content' => array(
                'required' => true,
            ),
        );
    }
}
