<?php
namespace Blog\Form\Fieldset;

class Category extends \Zend\Form\Fieldset 
    implements \Zend\InputFilter\InputFilterProviderInterface
{
    public function __construct($sm)
    {
        parent::__construct('category');

        $objectManager = $sm->get('Doctrine\ORM\EntityManager');
        $lang = $sm->get('lang')->getLang();

        $this->setHydrator(new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($objectManager))
             ->setObject(new \Blog\Entity\Category());

        $this->add(array(
            'name' => 'parent',
            'type' => 'Blog\Form\Element\ObjectSelectNested',
            'options' => array(
                'label' => 'Parent',
                'property' => 'name',
                'target_class' => 'Blog\Entity\Category',
                'object_manager' => $objectManager,
                'query_param' => array('locale' => $lang),
                'indent_chars' => '-',
                'indent_multiplyer' => 3,
            ),
            'attributes' => array(
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id',
        ));

        $this->add(array(
            'name' => 'name',
            'type'  => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Name'
            ),
            'attributes' => array(
                'placeholder' => 'Category Name',
                'class' => 'form-control',
            )
        ));

        $this->add(array(
            'name' => 'slug',
            'type'  => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Slug'
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'the-slug-is-a-lowercase-representation-of-the-name-with-dashes',
            )
        ));

        $this->add(array(
            'name' => 'description',
            'type'  => 'Zend\Form\Element\Textarea',
            'options' => array(
                'label' => 'Description',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Descriptions may not be shown, but they are used as meta for search engines',
            )
        ));
    }

    public function getInputFilterSpecification()
    {
        return array(
            'id' => array(
                'required' => false,
            ),
            'name' => array(
                'required' => true,
            ),
            'slug' => array(
                'required' => true,
            ),
            'description' => array(
                'required' => false,
            ),
            'parent' => array(
                'required' => false,
            ),
        );
    }
}
