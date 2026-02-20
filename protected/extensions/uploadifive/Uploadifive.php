<?php
/* Uploadifive Widget v1.0.0 / Compatible with UploadiFive 1.1.1
 * This widget is for uploadifive. But it doesn't provide jquery.uploadifive.min.js
 * You need to buy your own copy from http://www.uploadify.com/ and copy js in assets/js folder
 *
 * USAGE:
 *
 * Copy uploadifive folder into your extensions dir
 *
 * VIEW FILE:
 * $this->widget('ext.uploadifive.Uploadifive', [
         'name' => 'image_uploader',
         'auto' => true,
         'buttonText' => Yii::t('application', 'Upload Images'),
         'uploadButton' => false,
         //'uploadButtonText' => 'start upload',
         'formData' => [
             'YII_CSRF_TOKEN' => Yii::app()->request->cookies['YII_CSRF_TOKEN']->value,
             'product_id' => $product_id
         ],
         'queueID' => 'queue',
         'uploadScript' => '/your_controller/upload',
         'onSelect' => 'js:function (file) {
                                               $("div#queue") . show();
                                           }',
         'onUploadComplete' => 'js:function (file, data) {
                                               if(data != 1) alert(data);
                                               setTimeout(function () {
                                                   $("div#queue") . fadeOut("slow");
                                               }, 2000);
                                           }',
         'onUploadError' => 'js:function(file, errorCode, errorMsg, errorString) {
                                               alert("The file " + file.name + " could not be uploaded: " + errorString);
                                           }'
     ]
 );
*
*
* CONTROLLER:
    public function actionUpload()
    {
        if (isset($_POST['image_uploader'])) {

            $image = CUploadedFile::getInstanceByName('image_uploader');

            if (!in_array($image->extensionName, ['gif', 'jpg', 'jpeg', 'png'])) {
                echo "wrong file type";
                Yii::app()->end();
            }
            $image->saveAs('/tmp/' . $image->name);
            echo 1;
            Yii::app()->end();
        }
    }
 * */

class Uploadifive extends CInputWidget
{
    private $_parameters = array();
    private $_base_js_path;
    private $_base_css_path;
    private $_id;
    private $field_name;
    public $uploadButton;
    public $uploadButtonOptions = array();
    public $uploadButtonTagname = 'a';
    public $uploadButtonText = 'Upload Files';

    function init()
    {
		$this->_base_js_path = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets/js');
        $this->_base_css_path = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets/css');
		
        Yii::app()->getClientScript()
            ->registerCoreScript('jquery')
            ->registerScriptFile($this->_base_js_path . '/jquery.uploadifive.min.js', CClientScript::POS_END)
            ->registerCssFile($this->_base_css_path . '/uploadifive.css');
		
        return parent::init();
		
    }

    function run()
    {
        $this->setParameters();

        echo CHtml::fileField($this->name, $this->value, $this->htmlOptions);
        echo CHtml::tag('div', array('id' => $this->queueID), ' ');

        if ($this->uploadButton === true || ($this->uploadButton === null && (!isset($this->_parameters['auto']) || $this->_parameters['auto'] == false)))
            echo $this->addUploadButton();

        Yii::app()->getClientScript()->registerScript(get_class($this) . '-' . $this->getInputId(),
            "$('#{$this->inputId}').uploadifive(" . CJavaScript::encode($this->_parameters) . ");"
            , CClientScript::POS_END);
    }

    protected function getInputId()
    {
        if ($this->_id === null)
            $this->defineNameId();
        return $this->_id;
    }

    protected function defineNameId()
    {
        list($name, $id) = $this->resolveNameID();
        $this->_id = $this->htmlOptions['id'] = $id;
        $this->_name = $this->htmlOptions['name'] = $name;
    }

    protected function setParameters()
    {
        $this->_parameters = array_merge(array(
            'buttonText' => Yii::t('application', 'Select a file'),
        ), $this->_parameters);

        $this->_parameters['formData'][$this->name] = true;
        $this->_parameters['fileObjName'] = $this->name;

    }

    function __get($parameter)
    {
        try {
            return parent::__get($parameter);
        } catch (exception $exception) {
            if (isset($this->_parameters[$parameter]))
                return $this->_parameters[$parameter];
            throw $exception;
        }
    }

    function __set($parameter, $value)
    {
        try {
            return parent::__set($parameter, $value);
        } catch (exception $exception) {
            return $this->_parameters[$parameter] = $value;
        }
    }

    private function addUploadButton()
    {
		
        if (!isset($this->uploadButtonOptions['onclick']))
            $this->uploadButtonOptions['onclick'] = "javascript:$('#{$this->inputId}').uploadifive('upload')";
        if (!isset($this->uploadButtonOptions['href']))
            $this->uploadButtonOptions['href'] = '#';
        return CHtml::tag(
            $this->uploadButtonTagname,
            $this->uploadButtonOptions,
            $this->uploadButtonText
        );
		
    }

}