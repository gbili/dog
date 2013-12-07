<?php
namespace Blog\Form;

class FileUpload extends \Zend\Form\Form 
{
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->addElements();
    }

    public function addElements()
    {
        $file = new \Zend\Form\Element\File('image-file');
        $file->setLabel('Thumbnail')
            ->setAttribute('id', 'image-file');
        $this->add($file);
    }
}
