<?php
return array(
        // The DefaultNavigationFactory we configured in (1) uses 'default' as the sitemap key
       'side_1' => array(
            // And finally, here is where we define our page hierarchy
            'dog_list_my_dogs' => array(
                'label' => 'My Pack',
                'route' => 'dog_list_my_dogs',
                'order' => 100,
            ),
        ),
       'side_2' => array(
            // And finally, here is where we define our page hierarchy
            'dog_add_my_dog' => array(
                'label' => 'Add Dog',
                'route' => 'dog_add_my_dog',
            ),
        ),
);
