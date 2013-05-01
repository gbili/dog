<?php
return array(
	'errors' => array(
		'post_processor' => 'json-pp',
		'show_exceptions' => array(
			'message' => true,
			'trace'   => true
		)
	),
	'di' => array(
		'instance' => array(
			'alias' => array(
				'json-pp'  => 'Api\PostProcessor\Json',
				'image-pp' => 'Api\PostProcessor\Image',
				'xml-pp'   => 'Api\PostProcessor\Xml',
				'phps-pp'  => 'Api\PostProcessor\Phps',
			)
		)
	),
	'controllers' => array(
		'invokables' => array(
			'Api\Controller\Info'/*'info'*/ => 'Api\Controller\InfoController',
		)
	),
	'router' => array(
		'routes' => array(
			'info' => array(
				'type'    => 'Zend\Mvc\Router\Http\Segment',
				'options' => array(
					'route'       => '/api/info[.:formatter][/:id]',/*'/:controller[.:formatter][/:id]'*/ //make sure to rename the conroller to 'info'
					'constraints' => array(
						'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'formatter'  => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'         => '[a-zA-Z0-9_-]*'
					),
			        'defaults' => array(
			                'controller' => 'Api\Controller\Info', //this has to be the same as the invokable key/*'info'*/
			                'formatter'  => 'json',
			        ),
				),
			),
		),
	),
);