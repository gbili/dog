<?php
namespace User\Form\Fieldset;

class UserUniquename extends \Zend\Form\Fieldset 
implements \Zend\InputFilter\InputFilterProviderInterface
{
    public function __construct($name, $options = array())
    {
        parent::__construct('user');

        $this->add(array(
            'name' => 'id',
            'type'  => 'Zend\Form\Element\Hidden',
        ));

        $this->add(array(
            'name' => 'uniquename',
            'type'  => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Username'
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Jonny.de-Vito_21',
            )
        ));

        $this->add(array(
            'name' => 'password',
            'type'  => 'Zend\Form\Element\Password',
            'options' => array(
                'label' => 'Password'
            ),
            'attributes' => array(
                'class' => 'form-control',
            )
        ));
    }

    public function getInputFilterSpecification()
    {
        return array(
            'id' => array(
                'required' => false,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ),

            'uniquename' => array(
                'required' => true,
                'validators' => array(
                    array(
                        'name'    => 'Regex',
                        'options' => array(
                            'pattern' => '/(?:\\A(?:[A-Za-z0-9]+(?:[-_.]?[A-Za-z0-9]+)*){4,}\\z)/',
                            'message' => 'The username can contain A-Z, a-z, 0-9 and these special chars: - _ . (dash, underscore, dot)',
                        ),
                    ),
                ),
            ),

            'password' => array(
                'required' => true,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'min' => 8,
                            'max' => 64,
                            'message' => 'Password must be from 8 to 64 characters long',
                        ),
                    ),
                ),
            ),
        );
    }
}
