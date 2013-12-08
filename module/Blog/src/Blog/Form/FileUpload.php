<?php
namespace Blog\Form;

class FileUpload extends \Zend\Form\Form 
{
    protected $fileFieldset;

    public function __construct(\Doctrine\Common\Persistence\ObjectManager $objectManager, $name = null, $options = array())
    {
        $name = ((null === $name)? 'multiple-files' : $name);
        parent::__construct($name, $options);
        
        // The form will hydrate an object of type Post
        $this->setHydrator(new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($objectManager));
        
        //Add the user fieldset, and set it as the base fieldset
        $this->fileFieldset = new Fieldset\FileUpload($objectManager, $name);
        $this->fileFieldset->setUseAsBaseFieldset(true);
        $this->add($this->fileFieldset);

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

    public function getFileFieldset()
    {
        return $this->fileFieldset;
    }

    public function getRemappedFilesDataIfMultiple(array $filesData)
    {
        if (!$this->getFileFieldset()->isMultiple()) {
            return $filesData;
        }

        $fileInputName = $this->getFileFieldset()->getFileInputName();
        $fieldsetName = $this->getFileFieldset()->getName();
        $rawFilesData = $filesData[$fieldsetName][$fileInputName];
        $remappedFilesData = array();
        foreach ($rawFilesData as $fileData) {
            $remappedFilesData[] = array($fieldsetName => array($fileInputName => $fileData));
        }
        return $remappedFilesData;
    }
}
