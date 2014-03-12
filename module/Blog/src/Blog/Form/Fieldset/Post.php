<?php
namespace Blog\Form\Fieldset;

class Post extends \Zend\Form\Fieldset 
implements \Zend\InputFilter\InputFilterProviderInterface
{
    public function __construct($sm)
    {
        parent::__construct('post');

        $objectManager = $sm->get('Doctrine\ORM\EntityManager');
        $lang = $sm->get('lang')->getLang();

        $authService   = $sm->get('Zend\Authentication\AuthenticationService');
        $user = $authService->getIdentity();

        $this->setHydrator(new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($objectManager))
             ->setObject(new \Blog\Entity\Post());
        
        $this->add(array(
            'name' => 'id',
            'type'  => 'Zend\Form\Element\Hidden',
        ));

        //TODO SLug should be sent by js
        $this->add(array(
            'name' => 'slug',
            'type'  => 'Zend\Form\Element\Hidden',
        ));


        $this->add(array(
            'name' => 'parent',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'translate' => array(
                    'label' => false,
                ),
                'label' => 'Parent Post',
                'property' => 'slug',
                'target_class' => 'Blog\Entity\Post',
                'object_manager' => $objectManager,
                'display_empty_item' => true,
                'empty_item_label' => '---',
                'is_method' => true,
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array('locale' => $lang),
                    ),
                ),
            ),
            'attributes' => array(
                'placeholder' => 'the-slug-will-be-shown-in-url',
                'class' => 'form-control',
            )
        ));

        $this->add(array(
            'name' => 'category',
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

        $this->add(new PostData($sm));
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

            'parent' => array(
                'required' => false,
            ),

            'category' => array(
                'required' => false,
            ),
        );
    }
}
