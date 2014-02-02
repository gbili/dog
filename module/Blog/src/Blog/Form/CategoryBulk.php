<?php
namespace Blog\Form;

class CategoryBulk extends Bulk
{
    public function __construct($name)
    {
        parent::__construct($name, array(
            'multicheck_element_name' => 'categories',
            'bulk_action_value_options' => array(
                'linkTranslations' => 'Link Translations',
                'deleteCategories' => 'Delete',
            )
        ));
    }
}
