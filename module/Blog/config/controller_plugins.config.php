<?php
namespace Blog;
return array(
    'invokables' => array(
        'em'         => 'Blog\Controller\Plugin\EntityManager',
        'paginator'  => 'Blog\Controller\Plugin\Paginator',
        'repository' => 'Blog\Controller\Plugin\Repository',
    ),
);
