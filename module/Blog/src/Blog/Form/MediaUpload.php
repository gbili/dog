<?php
namespace Blog\Form;

class MediaUpload extends \Zend\Form\Form 
{
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->addElements();
        $this->addInputFilter();
    }
    
    public function addElements()
    {
        //File Input
        $file = new \Zend\Form\Element\File('image-file');
        $file->setLabel('Image Upload')
            ->setAttribute('id', 'image-file')
            ->setAttribute('multiple', true);    // HTML 5, that's it
        $this->add($file);
    }

    public function addInputFilter()
    {
        $inputFilter = new \Zend\InputFilter\InputFilter();
        
        //File Input
        $fileInput = new \Zend\InputFilter\FileInput('image-file');
        $fileInput->setRequired(true);

        /* You only need to define validators and filters
         * as if only one file was being uploaded. All files
         * will be run through the same validators and filters
         * automatically.
         */
        $fileInput->getValidatorChain()
            ->attachByName('filesize',      array('max' => 204800));
            //->attachByName('filemimetype',  array('mimeType' => 'image/png,image/x-png,image/jpeg'))
            //->attachByName('fileimagesize', array('maxWidth' => 100, 'maxHeight' => 100));

        /* All files will be renamed, i.e.:
        .* /data/tmpuploads/media_4b3403665fea6.png,
        .* /data/tmpuploads/media_5c45147660fb7.png
         */
        $fileInput->getFilterChain()->attachByName(
            'filerenameupload',
            array(
                'target'    => './data/tmpuploads/media.png',
                'randomize' => true,
            )
        );
        $inputFilter->add($fileInput);
        $this->setInputFilter($inputFilter);
    }
}
