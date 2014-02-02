<?php
namespace Blog\Form;

class FileBulk extends Bulk
{
    public function __construct($name)
    {
        parent::__construct($name, array(
            'multicheck_element_name' => 'files',
            'bulk_action_value_options' => array(
                'deleteFiles' => 'Delete',
            )
        ));
    }
}
