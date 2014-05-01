<?php
return array(
    // The DefaultNavigationFactory we configured in (1) uses 'side_1' as the sitemap key
   'side_1' => array(
        // And finally, here is where we define our page hierarchy
        'dog_list_my_dogs' => array(
            'label' => 'My Pack',
            'route' => 'dog_list_my_dogs',
            'order' => 100,
            'pages' => array(
                'dogtore_dog_add_route' => array(
                    'label' => 'Add Dog',
                    'iconClass' => 'glyphicon glyphicon-plus',
                    'route' => 'dogtore_dog_add_route',
                ),
            ),
        ),
    ),
);
