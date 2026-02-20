<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {

    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/column1';

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();
    
    public $device = 'pc';

    public $user;
    

    public function init() {
        parent::init();
        if (isset(Yii::app()->session['layout'])) {
            $this->layout = Yii::app()->session['layout'];
        }
        if (isset(Yii::app()->session['device'])) {
            $this->device = Yii::app()->session['device'];
        }

        $this->user = User::model()->findByPk(Yii::app()->user->id);

       if ($this->user)
       {
           if ($this->user->rf_access) {
               $this->user->rf_access = json_decode($this->user->rf_access, true);
           } else {
               $this->user->rf_access = array();
           }
       }

    
    }

    protected function download($file) {
        $file = Yii::app()->basePath . '/..' . $file;
        if (file_exists($file)) {

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);

            exit;
        } else {

            echo "Error exporting document: " . $file;
        }
    }

    public function downloadClientFile($file) {
        $file = Yii::app()->params['client_application_path'].$file;
        if (file_exists($file)) {

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);

            exit;
        } else {

            echo "Error exporting document: " . $file;
        }
    }

}
