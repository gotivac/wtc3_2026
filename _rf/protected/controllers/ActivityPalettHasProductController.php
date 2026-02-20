<?php

class ActivityPalettHasProductController extends Controller
{

    public function init()
    {
        parent::init();
        if (!in_array('inbound',$this->user->rf_access)) {
            throw new CHttpException('403','Zabranjen pristup.');
        }


    }
    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($id)
    {
        $activity = Activity::model()->findByPk($id);
        if ($activity === null) {
            throw new CHttpException('404', 'Aktivnost ne postoji.');
        }
        $activity->cleanScanned();
        $model = new ActivityPalettHasProduct;


        if (isset($_POST['ActivityPalettHasProduct'])) {

            $activity_palett = ActivityPalett::model()->findByAttributes(array('activity_id' => $activity->id, 'sscc' => $_POST['ActivityPalettHasProduct']['sscc']));
            if ($activity_palett === null) {
                $model->addError('sscc', 'Paleta ne pripada ovoj aktivnosti.');
            } else {
                $model = ActivityPalettHasProduct::model()->findByAttributes(array('activity_palett_id' => $activity_palett->id, 'sscc' => $_POST['ActivityPalettHasProduct']['sscc']));
                if ($model === null) {
                    $model = new ActivityPalettHasProduct;
                    $model->attributes = $_POST['ActivityPalettHasProduct'];
                    $model->activity_palett_id = $activity_palett->id;
                    if ($model->save()) {
                        $this->redirect(array('create2', 'id' => $model->id));
                    }
                } else {
                    if ($model->product_id == NULL) {
                        $this->redirect(array('create2', 'id' => $model->id));
                    } else {
                        $this->redirect(array('update', 'id' => $model->id));
                    }
                }

            }

        }

        $this->render('create', array(
            'model' => $model,
            'activity' => $activity,
        ));
    }

    public function actionCreate2($id)
    {
        $model = $this->loadModel($id);

        if (isset($_POST['ActivityPalettHasProduct'])) {


            $model->attributes = $_POST['ActivityPalettHasProduct'];
            $product = Product::model()->findByAttributes(array('product_barcode' => $model->product_barcode));
            $model->product_id = $product->id;
            $model->volume = $product->length * $product->width * $product->height * $model->quantity;

            if (isset($_POST['ExpireDate'])) {
                $model->expire_date = $_POST['ExpireDate']['day'] . '.' . $_POST['ExpireDate']['month'] . '.' . $_POST['ExpireDate']['year'];
            }

            if ($model->save()) {

                $accepted = Accept::model()->findByAttributes(array('activity_palett_id' => $model->activity_palett_id));
                if (!$accepted) {
                    $accepted = new Accept;
                    $accepted->attributes = array(
                        'activity_palett_id' => $model->activity_palett_id,
                        'sscc' => $model->sscc,
                        'status' => 0,
                    );
                    $accepted->save();
                }

                $this->redirect(array('update', 'id' => $model->id));
            }
        }

        $this->render('create2', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = ActivityPalettHasProduct::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {


        $activity_palett_has_product = ActivityPalettHasProduct::model()->findByPk($id);
        if ($activity_palett_has_product === null) {
            $this->redirect(array('create'));
        }

        $model = new ActivityPalettHasProduct;
        $model->activity_palett_id = $activity_palett_has_product->activityPalett->id;
        $model->sscc = $activity_palett_has_product->sscc;


        if (isset($_POST['ActivityPalettHasProduct'])) {
            $model->attributes = $_POST['ActivityPalettHasProduct'];

            $model->product_id = Product::model()->findByAttributes(array('product_barcode' => $model->product_barcode))->id;
            $activity = Activity::model()->findByPk($activity_palett_has_product->activityPalett->activity->id);
            if ($activity === null) {
                throw new CHttpException('404', 'Aktivnost ne postoji');
            }

            $existing = ActivityPalettHasProduct::model()->findByAttributes(array('activity_palett_id' => $model->activity_palett_id, 'product_id' => $model->product_id, 'sscc' => $model->sscc));
            if ($existing) {
                $quantity = $model->quantity;
                $packages = $model->packages;
                $units = $model->units;
                $model = $existing;
                $model->quantity = $model->quantity + $quantity;
                $model->packages = $model->packages + $packages;
                $model->units = $model->units + $units;
            }
            if ($model->save()) {
                echo json_encode($model->attributes);
            } else {
                echo CActiveForm::validate($model);
            }
            Yii::app()->end();

        }

        $this->render('update', array(
            'model' => $model,

        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
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

    public function actionDeletePalett($id)
    {
        $model = ActivityPalettHasProduct::model()->findAllByAttributes(array('activity_palett_id' => $id));
        if (count($model) > 1 || $model[0]->product_id != null) {
            Yii::app()->user->setFlash('error', 'Paletu je nemoguće obrisati jer sadrži proizvode.');
            $this->redirect(array('update', 'id' => $model[0]->id));
        } else {
            $model[0]->delete();
            $activity_id = $model[0]->activityPalett->activity->id;
            $this->redirect(array('create', 'id' => $activity_id));
        }

    }

    public function actionView($id)
    {
        $activity_palett = ActivityPalett::model()->findByPk($id);
        if ($activity_palett == null) {
            throw new CHttpException('404', 'Paleta nije pronadjena.');
        }
        $activity_palett->activity->cleanScanned();
        $model = new ActivityPalettHasProduct('search');
        $model->unsetAttributes();
        $model->activity_palett_id = $id;
        $this->render('view', array('model' => $model, 'activity_palett' => $activity_palett));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model = new ActivityPalettHasProduct('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ActivityPalettHasProduct']))
            $model->attributes = $_GET['ActivityPalettHasProduct'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionAjaxGetProduct()
    {
        $product_barcode = $_POST['product_barcode'];
        $quantity = $_POST['quantity'];
        $product = Product::model()->findByAttributes(array('product_barcode' => $product_barcode));
        if ($product === null) {
            echo json_encode(array('ActivityPalettHasProduct_product_barcode' => 'Proizvod ne postoji.'));
            Yii::app()->end();
        }
        if ($product->defaultPackage && $product->defaultPackage->product_count != 0) {
            echo json_encode(array(
                'product_barcode' => $product_barcode,
                'packages' => floor($quantity / $product->defaultPackage->product_count),
                'units' => $quantity % $product->defaultPackage->product_count
            ));
            Yii::app()->end();

        }
        echo json_encode(array(
            'product_barcode' => $product_barcode,
            'packages' => '',
            'units' => ''
        ));

    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'activity-palett-has-product-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionViewActivity($id)
    {
        $activity = Activity::model()->findByPk($id);
        if ($activity === null) {
            throw new CHttpException('404','Aktivnost ne postoji.');
        }
        $model = new ActivityPalett('search');
        $model->unsetAttributes();
        $model->activity_id = $id;

        $this->render('view_activity',array('model' => $model, 'activity' => $activity));

    }
}
