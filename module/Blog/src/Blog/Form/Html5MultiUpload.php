<?php
namespace Blog\Form;

use Zend\InputFilter;
use Zend\Form\Element;

class Html5MultiUpload extends \Zend\Form\Form
{
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->addElements();
        $this->setInputFilter($this->createInputFilter());
    }

    public function addElements()
    {
        //File Input
        $file = new Element\File('file');
        $file->setLabel('Select')
            ->setAttributes(array(
                'multiple' => true,
            )
        );
        $this->add($file);
        
        /* Enable this if you would like to have a Text Input sent along your files
        $text = new Element\Text('text');
        $text->setLabel('Text Entry');
        $this->add($text);*/
        
        // Submit
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Upload',
                'id' => 'submitbutton',
                'class' => 'btn btn-default', 
            ),
        ));
    }

    public function createInputFilter()
    {
        $inputFilter = new InputFilter\InputFilter();
        // File Input
        $file = new InputFilter\FileInput('file');
        $file->setRequired(true);
        $file->getFilterChain()->attachByName(
            'filerenameupload',
            array(
                'target'    => '/Users/g/Documents/workspace/dog/public/img/media.jpg',
                'randomize' => true,
            )
        );

        $file->getValidatorChain()->addByName(
            'fileextension', array('extension' => 'jpg')
        );

        /*$file->getValidatorChain()->addByName(
            'filesize', array('min' => 200, 'max' => 204800)
        );*/

        $file->getValidatorChain()->addByName(
            'filemimetype',
            array(
                'mimeType' => 'image/jpg,image/jpeg'
            )
        );

        $inputFilter->add($file);
        
        /* Text Input enable this if the field is enabled
        $text = new InputFilter\Input('text');
        $text->setRequired(true);
        $inputFilter->add($text);*/
        return $inputFilter;
    }
}
