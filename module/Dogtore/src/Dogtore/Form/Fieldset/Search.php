<?php
namespace Dogtore\Form\Fieldset;

class Search extends \Zend\Form\Fieldset 
    implements \Zend\InputFilter\InputFilterProviderInterface
{

    /**
     * Used for regex validation
     */
    protected $allCategoriesSlugs = array();

    public function __construct(\Doctrine\Common\Persistence\ObjectManager $objectManager)
    {
        parent::__construct('search');

        $categories = $objectManager->getRepository('Blog\Entity\Category')->findAll();
        foreach ($categories as $category) {
            $this->allCategoriesSlugs[] = $category->getSlug();
        }

        $this->add(array(
            'name' => 'term',
            'type'  => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'search'
            ),
            'attributes' => array(
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'name' => 'category',
            'type'  => 'Zend\Form\Element\Button',
            'options' => array(
                'label' => 'Content',
            ),
            'attributes' => array(
                'value' => $category->getSlug(), //from foreach above
                'class' => 'btn btn-primary',
                'type' => 'submit',
            )
        ));
    }

    public function getInputFilterSpecification()
    {
        return array(
            'term' => array(
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'Alnum',
                        'options' => array(
                            'allowWhiteSpace' => true,
                        ),
                    ),
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 255,
                        ),
                    ),
                ),
            ),
            'category' => array(
                'required' => true,
                'validators' => array(
                    array(
                        'name'    => 'Regex',
                        'options' => array(
                            'pattern' => '/symptom/',//'/^(?:' . implode(')|(?:', $this->allCategoriesSlugs) . ')$/',
                        ),
                    ),
                ),
            ),
        );
    }
}
