<?php
namespace Blog;
return array(
    'preparation_callbacks' => array(
        'Blog\Entity\Repository\NestedTreeFlat' => function ($repository, $controllerPlugin) {
            $controller = $controllerPlugin->getController();
            if (!$controller->identity()->isAdmin()) {
                $repository->setLocale($controller->locale());
            }
            $paginator = $controller->paginator();
            $repository->setFirstResult($paginator->getPageFirstItem());
            $repository->setMaxResults($paginator->getItemsPerPage());
        },
    ),
);
