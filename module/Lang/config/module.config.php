<?php
namespace Lang;

return array(
    'lang' => array(
        'date_time_formats' =>array(
            'es' => 'dd/mm/yy',
            'fr' => 'dd-mm-yy',
            'en' => 'yy-mm-dd',
        ), 
    ),

    'controller_plugins' => include __DIR__ . '/controller_plugins.config.php',
    'lang_selector'      => include __DIR__ . '/lang_selector.config.php',
    'service_manager'    => include __DIR__ . '/service_manager.config.php',
    'view_helpers'       => include __DIR__ . '/view_helpers.config.php',
);
