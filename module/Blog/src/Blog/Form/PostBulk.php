<?php
namespace Blog\Form;

class PostBulk extends Bulk
{
    public function __construct($name)
    {
        parent::__construct($name, array('multicheck_element_name' => 'posts'));
    }
}
