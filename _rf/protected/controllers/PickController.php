<?php

class PickController extends Controller
{

    public function init()
    {
        parent::init();
        if (!in_array('outbound',$this->user->rf_access) && !in_array('manipulate',$this->user->rf_access)) {
            throw new CHttpException('403','Zabranjen pristup.');
        }


    }
    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = Pick::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function actionReset($id)
    {
        $model = $this->loadModel($id);
        $activity_palett = ActivityPalett::model()->findByAttributes(array('activity_order_id' => $model->activityOrder->id, 'sscc' => $model->sscc_destination));
        $quantity = $model->quantity;

        $model->sscc_destination = null;
        $model->quantity = 0;
        if ($model->pick_type == 'product') {
            $model->packages = 0;
            $model->units = 0;
        }
        if ($model->save() && $activity_palett)
            if ($model->pick_type == 'palett') {
                $activity_palett->delete();
            }
        if ($model->pick_type == 'product') {
            $activity_palett_has_product = ActivityPalettHasProduct::model()->findByAttributes(array(
                'activity_palett_id' => $activity_palett->id,
                'product_barcode' => $model->product_barcode,
                'quantity' => $quantity,
            ));
            if ($activity_palett_has_product) {
                $activity_palett_has_product->delete();
            }
        }
        $this->redirect(array('/outbound/pick' . ucfirst($model->pick_type) . '/' . $model->activityOrder->id));

    }

    public function actionResetSplit($id)
    {
        $model = $this->loadModel($id);
        $activity_palett = ActivityPalett::model()->findByAttributes(array('activity_order_id' => $model->activityOrder->id, 'sscc' => $model->sscc_destination));
        $model->product_id = null;
        $model->product_barcode = null;
        $model->quantity = 0;
        $model->packages = 0;
        $model->units = 0;

        if ($model->save() && $activity_palett && $model->pick_type == 'move') {
            foreach ($activity_palett->hasProducts as $has_product) {
                if (!$has_product->delete()) {
                    var_dump('1', $has_product, $has_product->getErrors());
                    die();
                }
            }

        } else {
            $model->addError('product_barcode', 'Greška. Pokušajte ponovo.');
        }

        $this->redirect(array('/split/index'));

    }

    public function actionDeleteSplit($id)
    {
        $model = $this->loadModel($id);
        $activity_palett = ActivityPalett::model()->findByAttributes(array('activity_order_id' => $model->activityOrder->id, 'sscc' => $model->sscc_destination));
        if ($activity_palett) {
            $activity_palett->delete();
        }
        $model->delete();
        $this->redirect(array('split/index'));
    }


    public function actionAlternate($id)
    {
        $model = $this->loadModel($id);


        if (isset($_POST['Alternate'])) {
            $alternate_id = $_POST['Alternate'][$id];
            $alternate_activity_palett = ActivityPalettHasProduct::model()->findByPk($alternate_id);
            if ($alternate_activity_palett == null) {
                throw new CHttpException('404', 'Paleta ne postoji.');
            }
            $model->sloc_id = $alternate_activity_palett->activityPalett->inSloc->sloc_id;
            $model->sloc_code = $alternate_activity_palett->activityPalett->inSloc->sloc_code;
            $model->activity_palett_id = $alternate_activity_palett->activity_palett_id;
            $model->sscc_source = $alternate_activity_palett->sscc;
            $model->pick_type = 'product';
            if ($model->save()) {
                $this->redirect(array('update', 'id' => $model->id));
            } else {
                Yii::app()->user->setFlash('error', 'Došlo je do greške!');
            }
        }

        if (isset($_POST['SetProduct']) && $_POST['SetProduct'] == 1) {
            $model->pick_type = 'product';
            if ($model->save()) {
                $this->redirect(array('update', 'id' => $model->id));
            } else {
                Yii::app()->user->setFlash('error', 'Došlo je do greške!');
            }
        }


        $sql = 'SELECT p.* FROM activity_palett_has_product p LEFT JOIN sloc_has_activity_palett sp ON p.activity_palett_id = sp.activity_palett_id WHERE p.product_id = ' . $model->product_id . ' ORDER BY RIGHT(sp.sloc_code,2) ASC, sp.sloc_code ASC';


        $activity_paletts = Yii::app()->db->createCommand($sql)->queryAll();

        $active_paletts = array();
        foreach ($activity_paletts as $activity_palett) {

            $activity_palett_model = ActivityPalett::model()->findByPk($activity_palett['activity_palett_id']);

            $activity_palett_has_product = ActivityPalettHasProduct::model()->findByPk($activity_palett['id']);

            if ($activity_palett_model->id == $model->activity_palett_id) {
                continue;
            }

            if (!$activity_palett_model->isLocated()) {
                continue;
            }

            if ($activity_palett_model->inSloc->storageType->pickup == 0) {
                continue;
            }

            if ($activity_palett_has_product->content['quantity'] < $model->target) {
                continue;
            }

            $active_paletts[] = $activity_palett_has_product;

        }

        $this->render('alternate', array(
            'model' => $model,
            'active_paletts' => $active_paletts
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        if ($model->quantity > 0) {
            $this->redirect(array('/outbound/pickProduct/' . $model->activity_order_id));
            Yii::app()->end();
        }
        $model->sscc_source = null;
        $model->sscc_destination = null;
        $model->product_barcode = null;
        $model->quantity = $model->target;

        if ($model->pick_type == 'product' && $model->sscc_source != null) {
            $arranged = $this->arrangeByPackages($model->activity_palett_id, $model->product_id, $model->target);
            $model->packages = $arranged['packages'];
            $model->units = $arranged['units'];
        }

        $success = isset($_GET['success']) ? $_GET['success'] : 2;
        if (isset($_POST['Pick'])) {

            if ($model->pick_type == 'palett') {
                $valid = $this->loadModel($id);
                $model->attributes = $_POST['Pick'];
                if ($valid->sscc_source != $model->sscc_source) {
                    $model->addError('sscc_source', 'Neispravan SSCC. Skeniraj ' . $valid->sscc_source);
                } else {
                    $model->sscc_destination = $model->sscc_source;
                }

                if ($valid->product_barcode != $model->product_barcode) {
                    $model->addError('product_barcode', 'Neispravan proizvod. Skeniraj ' . $valid->product_barcode);
                }

                $activity_palett_has_product = ActivityPalettHasProduct::model()->findByAttributes(array('activity_palett_id' => $model->activity_palett_id, 'product_id' => $model->product_id));

                if ($activity_palett_has_product) {
                    $content = $activity_palett_has_product->getContent();

                    // if ($model->quantity != $content['quantity']) {
                    if ($model->quantity != ($activity_palett_has_product->stockQuantity + $model->quantity)) {
                        $model->addError('quantity', 'KOL: ' . ($activity_palett_has_product->stockQuantity + $model->quantity));
                    }
                    /*
                    if ($model->packages != $content['packages']) {
                        $model->addError('packages', 'KOL: ' . $content['packages']);
                    }

                    if (($model->units != $content['units']) && ($content['packages'] - $model->packages <= 0)) {
                        $model->addError('units', 'KOL: ' . $content['units']);
                    }
                    */
                    if ($model->quantity > $model->target) {
                        $model->addError('quantity', 'Količina veća od tražene.');
                    }

                }


            } else if ($model->pick_type == 'product') {
                $valid = $this->loadModel($id);
                $model->attributes = $_POST['Pick'];
                if ($valid->sscc_source != $model->sscc_source) {
                    $model->addError('sscc_source', 'Neispravan SSCC. Skeniraj ' . $valid->sscc_source);
                }
                if (ActivityPalett::model()->findByAttributes(array('activity_order_id' => $model->activityOrder->id, 'sscc' => $model->sscc_destination)) === null) {
                    $model->addError('sscc_destination', 'Paleta ne postoji u nalogu');
                }
                if ($valid->product_barcode != $model->product_barcode) {
                    $model->addError('product_barcode', 'Neispravan proizvod. Skeniraj ' . $valid->product_barcode);
                }

                if ($valid->activity_palett_id != NULL) {
                    $activity_palett_has_product = ActivityPalettHasProduct::model()->findByAttributes(array('activity_palett_id' => $model->activity_palett_id, 'product_id' => $model->product_id));

                    if ($activity_palett_has_product) {
                        $content = $activity_palett_has_product->getContent();
                        // if ($model->quantity > $content['quantity']) {
                        if ($model->quantity > ($activity_palett_has_product->stockQuantity + $model->quantity)) {
                            $model->addError('quantity', 'MAX: ' . ($activity_palett_has_product->stockQuantity + $model->quantity));
                        }
                        if ($model->packages > $content['packages']) {
                            $model->addError('packages', 'MAX: ' . $content['packages']);
                        }

                        if (($model->units > $content['units']) && ($content['packages'] - $model->packages <= 0)) {
                            $model->addError('units', 'MAX: ' . $content['units']);
                        }
                    }

                } else {
                    $sloc_has_product = SlocHasProduct::model()->findByAttributes(array('sloc_id' => $model->sloc_id, 'product_id' => $model->product_id));


                    if ($sloc_has_product) {

                        if ($model->quantity > $sloc_has_product->realQuantity) {
                            $model->addError('quantity', 'MAX: ' . $sloc_has_product->quantity);
                        }
                    } else {
                        $model->addError('sloc_code', 'Sloc ne sadrži proizvod');
                    }
                }
                if ($model->quantity > $model->target) {
                    $model->addError('quantity', 'Količina veća od tražene.');
                }
            } else if ($model->pick_type == 'move') {

                $valid = $this->loadModel($id);
                $model->attributes = $_POST['Pick'];
                if ($valid->sscc_source != $model->sscc_source) {
                    $model->addError('sscc_source', 'Neispravan SSCC. Skeniraj ' . $valid->sscc_source);
                }
                if (ActivityPalett::model()->findByAttributes(array('activity_order_id' => $model->activityOrder->id, 'sscc' => $model->sscc_destination)) === null) {
                    $model->addError('sscc_destination', 'Neispravan SSCC. Skeniraj ' . $valid->sscc_destination);
                }


                $activity_paletts_has_products = ActivityPalettHasProduct::model()->findAllByAttributes(array('activity_palett_id' => $model->activity_palett_id));
                foreach ($activity_paletts_has_products as $has_product) {
                    if ($has_product->product_barcode == $model->product_barcode) {
                        $activity_palett_has_product = $has_product;
                        break;
                    }
                }
                if (!isset($activity_palett_has_product)) {
                    $model->addError('product_barcode', 'Proizvod ne postoji na paleti.');
                }


                if (isset($activity_palett_has_product) && $activity_palett_has_product) {


                    if ($model->quantity > $activity_palett_has_product->stockQuantity ) {
                        $model->addError('quantity', 'MAX: ' . $activity_palett_has_product->stockQuantity );
                    }
                    /*
                    if ($model->packages > $content['packages']) {
                        $model->addError('packages', 'MAX: ' . $content['packages']);
                    }

                    if (($model->units > $content['units']) && ($content['packages'] - $model->packages <= 0)) {
                        $model->addError('units', 'MAX: ' . $content['units']);
                    }
                    */
                }
            }

            if (!$model->hasErrors()) {

                if ($model->pick_type == 'move') {
                    $model->product_id = $activity_palett_has_product->product_id;
                    $model->created_user_id = $this->user->id;
                    $model->created_dt = date('Y-m-d H:i:s');

                    if ($model->save()) {
                        $this->redirect(array('/split/index/'));
                    } else {
                        $success = 0;
                        Yii::app()->user->setFlash('error', CHtml::errorSummary($model));
                    }
                } else {
                    if ($model->save()) {
                        $picks = Pick::model()->findAllByAttributes(array('pick_type' => $model->pick_type, 'activity_order_id' => $model->activityOrder->id));
                        foreach ($picks as $k => $pick) {
                            if ($pick->id == $model->id) {
                                $next = (isset($picks[$k + 1]) && $picks[$k + 1]->sscc_destination == null) ? $picks[$k + 1] : false;
                            }
                        }
                        if ($next) {
                            $this->redirect(array('update', 'id' => $next->id, 'success' => 1));
                        } else {
                            $this->redirect(array('/outbound/pick' . ucfirst($model->pick_type) . '/' . $model->activityOrder->id));
                        }

                    } else {
                        $success = 0;
                        Yii::app()->user->setFlash('error', CHtml::errorSummary($model));
                    }
                }
            }


        }


        $this->render('update', array(
            'model' => $model,
            'success' => $success,
            'order' => $model->activityOrder
        ));
    }

    private function arrangeByPackages($activity_palett_id, $product_id, $quantity)
    {
        $activity_palett_has_product = ActivityPalettHasProduct::model()->findByAttributes(array('activity_palett_id' => $activity_palett_id, 'product_id' => $product_id));

        if ($activity_palett_has_product->packages > 0) {
            $products_in_package = floor(($activity_palett_has_product->quantity - $activity_palett_has_product->units) / $activity_palett_has_product->packages);
        } else {
            $products_in_package = false;
        }
        if ($products_in_package === false || $products_in_package == 0) {
            return array(
                'packages' => 0,
                'units' => $quantity,
            );
        }

        $packages = floor($quantity / $products_in_package);

        $units = $quantity - ($packages * $products_in_package);


        return array(
            'packages' => $packages,
            'units' => $units,
        );

    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {

        $model = $this->loadModel($id);

        if ($model === null) {
            throw new CHttpException('404', 'Pikovanje nije pronađeno.');
        }

        $pick_type = $model->pick_type;
        $activity_order_id = $model->activity_order_id;

        $activity_palett = ActivityPalett::model()->findByAttributes(array('activity_order_id' => $activity_order_id, 'sscc' => $model->sscc_destination));

        if ($activity_palett && $pick_type == 'palett') {
            $activity_palett->delete();
        } else {
            foreach ($activity_palett->hasProducts as $activity_palett_has_product) {
                if ($activity_palett_has_product->product_id == $model->product_id) {
                    $activity_palett_has_product->delete();
                }
            }
        }


        $model->delete();

        $this->redirect(array('/outbound/pick' . ucfirst($pick_type) . '/' . $activity_order_id));


    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model = new Pick('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Pick']))
            $model->attributes = $_GET['Pick'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'pick-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
