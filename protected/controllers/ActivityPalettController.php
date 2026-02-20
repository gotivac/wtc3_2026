<?php

class ActivityPalettController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column1';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return $this->allowances;
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $products = new  ActivityPalettHasProduct('search');
        $products->unsetAttributes();
        $products->activity_palett_id = $id;
        $this->render('view', array(
            'model' => $this->loadModel($id),
            'products' => $products,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = ActivityPalett::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function actionResSplit($id)
    {


        $model = $this->loadModel($id);
        $activity_palett = new ActivityPalett;
        $activity_palett->attributes = array(
            'activity_id' => $model->activity_id,
            'activity_order_id' => $model->activity_order_id,
            'sscc' => ActivityPalett::newSSCC(),
        );

        if ($activity_palett->save()) {

            $pick = new Pick;
            $pick->attributes = array(
                'activity_order_id' => $model->activity_order_id,
                'sloc_id' => $model->inSloc->sloc_id,
                'sloc_code' => $model->inSloc->sloc_code,
                'activity_palett_id' => $model->id,
                'sscc_source' => $model->sscc,
                'sscc_destination' => $activity_palett->sscc,
                'product_id' => null,
                'product_barcode' => null,
                'target' => 0,
                'quantity' => 0,
                'packages' => 0,
                'units' => 0,
                'pick_type' => 'move',
            );

            if (!$pick->save()) {
                $activity_palett->delete();
                Yii::app()->user->setFlash('error', Yii::t('app', 'Error creating pick task.'));
                $pick = false;

            } else {
                Yii::app()->user->setFlash('success', 'Uspešno kreirana paleta za preraspodelu. SSCC kod: ' . $pick->sscc_destination);
                $this->redirect(array('resSplitOK','id'=>$activity_palett->id));
                Yii::app()->end();
            }

        } else {
            Yii::app()->user->setFlash('error', Yii::t('app', 'Error creating SSCC.'));
            $pick = false;
            $activity_palett = false;
        }
        $this->render('split_error', array('model' => $pick, 'activity_palett' => $activity_palett));
    }

    public function actionResSplitOK($id)
    {
        $model = $this->loadModel($id);
        $this->render('split',array('model'=>$model));
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'activity-palett-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest) {
// we only allow deletion via POST request
            $this->loadModel($id)->delete();

// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

}
