<?php
namespace Blog\Service;

class FileEntityUploader
{

    private $postData;
    private $fileInputName;
    private $formName = 'file-form';
    private $messages;
    private $files = array();
    private $form = null;

    public function getFormCopy()
    {
        if (null !== $this->form) {
            return clone $this->form;
        }
        return new \Blog\Form\Html5MultiUpload($this->getFormName());
    }

    public function setForm($form)
    {
        if (!($from instanceof \Blog\Form\Html5MultiUpload)) {
            throw new \Exception('Form type not supported, must extend Html5MultiUpload');
        }
        $this->form = $form;
        return $this;
    }

    public function getFiles()
    {
        if (empty($this->files)) {
            throw new \Exception('No file was successfully uploaded, check what went wrong in getMessages()');
        }
        return $this->files;
    }

    public function hasFiles()
    {   
        if (!$this->hasMessages()) {
            throw new \Exception('Make sure tu call uploadFiles() before getFiles()');
        }
        return !empty($this->files);
    }

    protected function addFile($file)
    {
        $this->files[] = $file;
        return $this;
    }

    public function setFormName($name)
    {
        $this->formName = $name;
        return $this;
    }

    public function getFormName()
    {
        return $this->formName;
    }

    public function setEntityManager(\Doctrine\ORM\EntityManager $em)
    {
        $this->objectManager = $em;
        return $this;
    }

    public function getEntityManager()
    {
        if (null === $this->objectManager) {
            throw new \Exception('Doctrine Object Manager not set');
        }
        return $this->objectManager;
    }

    public function setMessages(array $messages)
    {
        $this->messages = $messages;
        return $this;
    }

    public function hasMessages()
    {
        return null !== $this->messages;
    }

    public function getMessages()
    {
        if (!$this->hasMessages()) {
            throw new \Exception('It seems that you have not called uploadFiles');
        }
        return $this->messages;
    }

    public function setFileInputName($name)
    {
        $this->fileInputName = $name;
        return $this;
    }

    public function getFileInputName()
    {
        if (null === $this->fileInputName) {
            throw new \Exception('File input name not set');
        }
        return $this->fileInputName;
    }

    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getPostData()
    {
        if (null === $this->postData) {
            $this->postData = array_merge_recursive(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );
        }
        return $this->postData;
    }

    public function areAllFilesUploaded()
    {
        foreach ($this->getMessages() as $message) {
            if (!$this->isFileUploaded($message)) {
                return false; 
            }
        }
        return true;
    }

    public function isFileUploaded($message)
    {
        return isset($message['success']);
    }

    public function uploadFiles()
    {
        $formName = $this->getFormName();
        $fileInputName = $this->getFileInputName();
        $data = $this->getPostData();

        $messages = array();
        foreach ($data[$fileInputName] as $fileData) {
            $singleData = array($fileInputName => $fileData);
            $fileName = $fileData['name'];
            $singleFileFormData = $data;
            $singleFileFormData[$fileInputName] = $fileData;
            $messages[$fileName] = $this->uploadOneFile($singleFileFormData);
        }
        $this->setMessages($messages);
        return $this->areAllFilesUploaded();
    }

    public function uploadOneFile(array $singleFileFormData)
    {
        $form = $this->getFormCopy();
        $form->setData($singleFileFormData);
        if (!$form->isValid()) {
            return $form->getMessages();
        }
        //array('otherinput'=>'inputval', 
        //      'file' => array(
        //           name=>filename.jpg, 
        //           tmp_upload=>'/something/asdfasdf/LKGO'
        //           ...
        //       )
        //       otherinput ...
        //);
        $formData = $form->getData();
        
        //array(
        //    name=>filename.jpg,
        //    tmp_upload=>'/something/asdfasdf/LKGO'
        //    ...
        // );
        $fileData = $formData[$this->getFileInputName()];
        $this->saveFile($fileData);
        return array('success' => 'File Uploaded');
    }

    public function saveFile(array $fileData)
    {
        $file = $this->getHydratedFile($fileData);

        $this->persistFile($file);

        $this->addFile($file);
    }

    public function getHydratedFile($fileData)
    {
        $persistableData = array_intersect_key($fileData, array_flip(array('name', 'type', 'date', 'tmp_name', 'size')));
        $persistableData['date'] = new \DateTime();

        $file = new \Blog\Entity\File();
        $file->hydrateWithFormData($persistableData);
        if ($file->getName() !== $file->getBasename()) {
            $file->move($file->getName());
        }
        return $file;
    }

    public function persistFile(\Blog\Entity\File $file)
    {
        $this->getEntityManager()->persist($file);
        $this->getEntityManager()->flush();
    }
}
