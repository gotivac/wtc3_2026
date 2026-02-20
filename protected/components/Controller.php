<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{

    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/column2';

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

    public $user;


    public function init()
    {
        parent::init();
        Yii::app()->session['authenticated'] = true;
        if (!Yii::app()->user->isGuest) {
            $this->user = User::model()->findByPk(Yii::app()->user->id);
            if ($this->user->session_start != Yii::app()->session['session_start']) {
                Yii::app()->user->logout();
                $this->redirect('/site/login');
            }
        }

    }

    public function controllerSettings()
    {
        $settings = Settings::model()->findByAttributes(array('controller'=>$this->id));
        if ($settings) {
            return json_decode($settings->content);
        }
        return array();
    }

    public function downloadClientFile($file)
    {
        $file = Yii::app()->params['client_application_path'] . $file;
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

    public function getAllowances()
    {
        $user = User::model()->findByPk(Yii::app()->user->id);

        if ($user->roles == 'superadministrator' || substr($this->action->id, 0, 4) == 'ajax' || substr($this->action->id, 0, 3) == 'res') {
            return array(
                array('allow')
            );
        }

        $auth_role = $user->authRole;
        $allowances = array();

        foreach ($auth_role->authRoleCan as $can) {

            if ($can->controller->title == Yii::app()->controller->id)
                $allowances[] = array('allow', 'actions' => array($can->action->title,$can->action->title.'2'), 'roles' => array($can->role->lower_case));
        }
        $allowances[] = array('deny', 'users' => array('*'));

        return $allowances;

    }
    public function userAccess($controller)
    {
        Yii::app()->getModule('rbac');
        return Yii::app()->user->roles == 'superadministrator' ? true : AuthRoleCan::model()->findByAttributes(array('url_phrase'=>$controller.'/index','auth_role_id'=>$this->user->authRole->id));
    }
    protected function download($file)
    {
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

}
