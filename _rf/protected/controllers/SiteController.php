<?php

class SiteController extends Controller {

    public $layout;
    public $menu = array();
    public $breadcrumbs = array();
    
    

    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    
    public function actionIndex() {


        $this->render('index', array(


        ));
    }
    
    public function actionSetScanner()
    {
        Yii::app()->session['layout'] = '//layouts/column2';
        Yii::app()->session['device'] = 'scanner';
       $this->redirect(Yii::app()->request->urlReferrer);
    }
    
    public  function actionSetPC()
    {
        Yii::app()->session['layout'] = '//layouts/column1';
        Yii::app()->session['device'] = 'pc';
        $this->redirect(Yii::app()->request->urlReferrer);
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest) {
                echo $error['message'];
            } else {
                $this->render('error', $error);
            }
        }
    }
    
    public function actionBarcode()
    {
        $ini_file = Yii::getPathOfAlias('webroot').'/settings/barcode.ini';
        $model = parse_ini_file($ini_file);
        if (isset($_POST['Barcode'])) {
            $model = $_POST['Barcode'];
            
            $file = fopen($ini_file,'w');
            $content = '';
            foreach ($model as $key=>$value) {
                $content .= $key.'='.$value."\n";
            }
            fwrite($file, $content);
            Yii::app()->user->setFlash('success',Yii::t('app','Saved'));
        }
        $this->render('barcode',array('model' => $model));
    }


    /**
     * Displays the login page
     */
    public function actionLogin() {
        $model = new LoginForm;
        $user = new User;

        $visible = 'login';

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()) {
                Yii::app()->session['layout'] = '//layouts/column2';
                Yii::app()->session['device'] = 'scanner';
                $this->redirect(Yii::app()->controller->createUrl('index')); // ova linija sredjuje problem sa Chromeom nakon logina u nekim konfiguracijama servera
                
            }
        }



        // display the login form
        $this->render('login', array('model' => $model, 'user' => $user, 'visible' => $visible));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();

        $this->redirect(Yii::app()->homeUrl);
    }

}
