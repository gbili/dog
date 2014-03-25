<?php
namespace Blog\Form\Fieldset;

/**
 * Used to fetch the post_parent id with ajax 
 */
class PostParentSlug extends \Zend\Form\Fieldset 
implements \Zend\InputFilter\InputFilterProviderInterface
{
    public function __construct($name, $options=array())
    {
        parent::__construct($name);

        $this->add(array(
            'name' => 'slug',
            'type'  => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Answer for:',
                'form_group_class' => 'hide',
                'label_attributes' => array(
                    'class' => 'control-label',
                ),
            ),
            'attributes' => array(
                'id' => 'autocomplete_parent_slug',
                'class' => 'form-control',
            ),
        ));
    }

    public function getInputFilterSpecification()
    {
        return array(
            'slug' => array(
                'required' => false,
                'validators' => array(
                    array(
                        'name'    => 'Regex',
                        'options' => array(
                            'pattern'      => '/\\A[a-z0-9](:?[-]?[a-z0-9]+)*\\z/',
                        ),
                    ),
                ),
            ),
        );
    }
}
