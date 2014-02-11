<?php
namespace Upload\Service;

class Uploader 
{
    /**
     * Points to a php file containing
     * javascript code to override ajax.file_upload.js.phtml
     * TODO Weird
     *
     * @var string
     */
    protected $includeScriptFilePath;

    /**
     * @var array
     */
    protected $formActionRouteParams = array('route' => null, 'params' => array(), 'reuse_matched_params' => true);

    /**
     *
     * @var \Upload\FileHydratorInterface
     */
    protected $hydrator;

    /**
     *
     * @var \Zend\Http\Request
     */
    protected $request;

    /**
     * @var StdObject array
     */
    protected $postData;

    /**
     * @var string
     */
    protected $fileInputName;

    /**
     * @var string
     */
    protected $formName;

    /**
     * @var array
     */
    protected $messages;

    /**
     * @var array
     */
    protected $files = array();

    /**
     * @var \Upload\Form\Html5MultiUpload
     */
    protected $form = null;

    /**
     * @var \Upload\Form\Html5MultiUpload
     */
    protected $clonableForm = null;

    public function getFormCopy()
    {
        if (null === $this->clonableForm) {
            //Popopulate clonableForm
            $this->getForm();
        }
        return clone $this->clonableForm;
    }

    /**
     * This is very weird but... It allows overriding the
     * ajax.file_upload.js.phtml script vars: uploadSuccess
     * uploadFail. You can use this to set different  
     * behaviors on success or on fail
     * TODO Change this to javascript only solution? 
     */
    public function setIncludeScriptFilePath($filePath)
    {
        if (!is_file($filePath)) {
            throw new \Exception('File is not reachable: ' . print_r($filePath, true));
        }
        $this->includeScriptFilePath = $filePath;
        return $this;
    }

    public function hasIncludeScriptFilePath()
    {
        return null !== $this->includeScriptFilePath;
    }

    public function getIncludeScriptFilePath()
    {
        return $this->includeScriptFilePath;
    }

    public function setForm($form)
    {
        if (!($form instanceof \Upload\Form\Html5MultiUpload)) {
            throw new \Exception('Form type not supported, must extend Html5MultiUpload');
        }
        $this->form = $form;
        return $this;
    }

    public function getForm()
    {
        if (null !== $this->form) {
            return $this->form;
        }

        $options = array();
        if (!$this->hasFileInputName()) {
            $this->setFileInputName('file_input');
        }
        $options['file_input_name'] = $this->getFileInputName();

        if (!$this->hasFormName()) {
            $this->setFormName('file_form');
        }
        $name = $this->getFormName();

        $form = new \Upload\Form\Html5MultiUpload($name, $options);

        $this->clonableForm = clone $form;
        $this->setForm($form);

        return $form;
    }

    public function setFormActionRouteParams(array $routeParams)
    {
        if (isset($routeParams['route'])) {
            $this->formActionRouteParams['route'] = $routeParams['route'];
        }
        if (isset($routeParams['params'])) {
            $this->formActionRouteParams['params'] = $routeParams['params'];
        }
        if (isset($routeParams['reuse_matched_params'])) {
            $this->formActionRouteParams['reuse_matched_params'] = $routeParams['reuse_matched_params'];
        }
        return $this;
    }

    public function getFormActionRouteParams()
    {
        return $this->formActionRouteParams;
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

    public function hasFormName()
    {
        return null !== $this->formName;
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

    public function hasFileInputName()
    {
        return null !== $this->fileInputName;
    }

    public function getFileInputName()
    {
        if (!$this->hasFileInputName()) {
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

    /**
     * If request is ajax the Js Script will send a
     * request with a isAjax variable set to 1
     * You should find the Js Script in partial/file_upload.phtml
     */
    public function isAjax()
    {
        $postData = $this->getPostData();
        return !empty($postData['isAjax']);
    } 

    public function isFileUploaded($message)
    {
        return isset($message['class']) && $message['class'] === 'success';
    }

    public function uploadFiles()
    {
        $formName      = $this->getFormName();
        $fileInputName = $this->getFileInputName();
        $data          = $this->getPostData();

        $messages = array();
        foreach ($data[$fileInputName] as $fileData) {
            $message = array();
            $singleData = array($fileInputName => $fileData);
            $fileName = $fileData['name'];
            $singleFileFormData = $data;
            $singleFileFormData[$fileInputName] = $fileData;

            $message = $this->uploadOneFile($singleFileFormData);
            $message['fileName'] = $fileName;

            $messages[] = $message;
        }
        $this->setMessages($messages);
        return $this;
    }

    public function uploadOneFile(array $singleFileFormData)
    {
        $form = $this->getFormCopy();
        $form->setData($singleFileFormData);
        if (!$form->isValid()) {
            $messages = $form->getMessages();
            $message = implode('. ', $messages[$this->getFileInputName()]);
            return array(
                'class' => 'danger',
                'message' => $message,
            );
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
        return array(
            'class' => 'success', 
            'message' => 'File Uploaded',
        );
    }

    public function saveFile(array $fileData)
    {
        $file = $this->getFileHydrator()->getHydratedFile($fileData);

        $this->persistFile($file);

        $this->addFile($file);
    }

    /**
     * Form data is passed to $hydrater->getHydratedFile($formData)
     * the method needs to return a doctrine file entity
     */
    public function setFileHydrator(\Upload\FileHydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
        return $this;
    }

    public function getFileHydrator()
    {
        if (null === $this->hydrator) {
            throw new \Exception('Call setFileHydrator($hydrator), before starting to upload');
        }
        return $this->hydrator;
    }

    public function persistFile($file)
    {
        $this->getEntityManager()->persist($file);
        $this->getEntityManager()->flush();
    }
}
