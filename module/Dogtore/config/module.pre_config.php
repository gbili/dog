<?php
namespace Dogtore;
return array(
    'regex_patterns' => array(
        'lang'       => '(?:en)|(?:es)|(?:fr)|(?:de)|(?:it)',
        'id'         => '[0-9]+',
        'uniquename' => '[A-Za-z0-9](?:[-_.]?[A-Za-z0-9]){3,}',
        'dogname' => '[A-Za-z0-9](?: ?[A-Za-z0-9.-]){3,}',
        'category' => '(?:(?:symptom)|(?:cause)|(?:solution))s?',
    ),
);
