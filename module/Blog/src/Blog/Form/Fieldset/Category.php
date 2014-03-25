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

        $authService   = $sm->get('Zend\Authentication\AuthenticationService');
        $user = $authService->getIdentity();

        $this->setHydrator(new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($objectManager))
             ->setObject(new \Blog\Entity\Category());

        $this->add(array(
            'name' => 'parent',
            'type' => 'Blog\Form\Element\ObjectSelectNested',
            'options' => array(
                'translate' => array(
                    'label' => false,
                ),
                'label' => 'Category',
                'property' => 'name',
                'target_class' => 'Blog\Entity\Category',
                'object_manager' => $objectManager,
                'query_param' => array('locale' => $lang, 'lvl'),
                'query_builder_callback' => function ($queryBuilder, $paramNum) use ($user) {
                    if ($user->isAdmin()) {
                        return $paramNum;
                    }
                    $queryBuilder->andWhere('node.lvl > ?' . $paramNum)
                        ->setParameter($paramNum, 0);
                    return ++$paramNum;
                },
                'indent_chars' => '-',
                'indent_multiplyer' => 3,
                // Because we are skipping the lvl 0, it makes sense to start indenting from lvl 2 instead of 1
                'indent_multiplyer_callback' => function ($multiplyBy, $node, $indentMultiplyer, $elementObjectSelectNested) use ($user) {
                    if ($user->isAdmin()) {
                        return $multiplyBy;
                    }
                    //Because lvl 0 is skipped, we make as if lvl 1 where 0, by resting 1
                    $multiplyBy = ($node['lvl']-1) * $indentMultiplyer;
                    return $multiplyBy;
                },
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
                'class' => 'form-control slugicize',
            )
        ));

        $this->add(array(
            'name' => 'slug',
            'type'  => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'class' => 'slugicize_put_slug_here',
            ),
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
                'validators' => array(
                    array(
                        'name'    => 'Regex',
                        'options' => array(
                            'pattern'      => '/\\A[a-z0-9](:?[-]?[a-z0-9]+)*\\z/',
                        ),
                    ),
                ),
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
