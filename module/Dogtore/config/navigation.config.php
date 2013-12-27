<?php
return array(
        // The DefaultNavigationFactory we configured in (1) uses 'default' as the sitemap key
        'default' => array(
            // And finally, here is where we define our page hierarchy
            'dogtore_symptom' => array(
                'label' => 'Symptoms',
                'route' => 'dogtore_index',
                'params' => array('category' => 'symptoms'),
            ),
            'dogtore_cause' => array(
                'label' => 'Causes',
                'route' => 'dogtore_index',
                'params' => array('category' => 'causes'),
            ),
            'dogtore_solution' => array(
                'label' => 'Solutions',
                'route' => 'dogtore_index',
                'params' => array('category' => 'solutions'),
            ),
        ),
);
