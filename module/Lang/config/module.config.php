<?php
namespace Lang;

return array(
    'controller_plugins' => include __DIR__ . '/controller_plugins.config.php',
    'lang_selector'      => include __DIR__ . '/lang_selector.config.php',
    'service_manager'    => include __DIR__ . '/service_manager.config.php',
    'view_helpers'       => include __DIR__ . '/view_helpers.config.php',
);
