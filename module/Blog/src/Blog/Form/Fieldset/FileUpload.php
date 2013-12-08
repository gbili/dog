<?php
namespace Blog\Form\Fieldset;

class FileUpload extends \Zend\Form\Fieldset 
    implements \Zend\InputFilter\InputFilterProviderInterface
{

    protected $isMultiple;
    protected $fileInputName = 'file';
    protected $name;

    public function __construct($name = null)
    {
        $this->name = $name;
        parent::__construct($name);

        $this->isMultiple = (0 < preg_match('/multiple/', $name));
        
        //File Input
        $fileInput = array(
            'name' => $this->getFileInputName(), 
            'type' => 'Zend\Form\Element\File',
            'attributes' => array(
                'id' => 'file',
            ),
            'options' => array(
                'label' => 'File Upload'
            ),
        );

        if ($this->isMultiple()) {
            $fileInput['attributes']['multiple'] = true;
        }

        $this->add($fileInput);
    }

    public function getFileInputName()
    {
        return $this->fileInputName;
    }

    public function getName()
    {
        return $this->name;
    }

    public function isMultiple()
    {
        return $this->isMultiple;
    }

    public function getInputFilterSpecification()
    {
        /* You only need to define validators and filters
         * as if only one file was being uploaded. All files
         * will be run through the same validators and filters
         * automatically.
         */
        //$fileInput->getValidatorChain()
            //->attachByName('filesize',      array('max' => 204800))
            //->attachByName('filemimetype',  array('mimeType' => 'image/png,image/x-png,image/jpeg'))
            //->attachByName('fileimagesize', array('maxWidth' => 100, 'maxHeight' => 100));

        return array(
            $this->getFileInputName() => array(
                'required' => true,
                'input_filters'  => array(
                    array(
                        'name' => '\Zend\InputFilter\FileInput'
                    ),
                ),
                'filters'  => array(
                    /* All files will be renamed, i.e.:
                    .* /data/tmpuploads/media_4b3403665fea6.png,
                    .* /data/tmpuploads/media_5c45147660fb7.png
                     */
                    array(
                        'name' => 'filerenameupload',
                        'options' => array(
                            'target'    => '/Users/g/Documents/workspace/dog/module/Blog/tmp_uploads/media',
                            'randomize' => true,
                        ),
                    ),
                ),
                'validators' => array(
                    array(
                        'name' => 'filesize',
                        'options' => array(
                            'max' => 204800
                        ),
                    ),
                ),
            ),
        );
    }
}
