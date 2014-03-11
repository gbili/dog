<?php
namespace Blog;

return array(
   'eventmanager' => array(
       'orm_default' => array(
           'subscribers' => array(
               'Gedmo\Tree\TreeListener',
               'Gedmo\Sluggable\SluggableListener',
           ),
       ),
   ),
   'driver' => array(
        __NAMESPACE__ . '_driver' => array(
            'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
            'cache' => 'array',
            'paths' => array(
                __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
            ),
        ),
        'orm_default' => array(
            'drivers' => array(
                __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
            ),
        ),
    ),
);
