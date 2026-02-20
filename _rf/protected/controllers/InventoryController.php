<?php

class InventoryController extends Controller
{
    public function init()
    {
        parent::init();
        if (!in_array('inventory', $this->user->rf_access)) {
            throw new CHttpException('403', 'Zabranjen pristup.');
        }


    }

    public function actionIndex()
    {
        $sloc = new Sloc;
        if (isset($_POST['Sloc'])) {
            $sloc_code = $_POST['Sloc']['sloc_code'];
            $sloc = Sloc::model()->findByAttributes(array('sloc_code' => $sloc_code));
            if ($sloc == null) {
                $sloc = new Sloc;
                $sloc->addError('sloc_code', 'SLOC ne postoji.');

            } else {
                $this->redirect(array('slocContent', 'id' => $sloc->id));
            }
        }
        $this->render('index', array('model' => $sloc));
    }

    public function actionSlocContent($id)
    {
        $sloc = Sloc::model()->findByPk($id);
        if ($sloc === null) {
            throw new CHttpException('404', 'SLOC  ne postoji.');
        }

        $sloc_has_activity_paletts = new SlocHasActivityPalett('search');
        $sloc_has_activity_paletts->unsetAttributes();
        $sloc_has_activity_paletts->sloc_id = $id;

        $sloc_has_products = new SlocHasProduct('search');
        $sloc_has_products->unsetAttributes();
        $sloc_has_products->sloc_id = $id;

        $this->render('sloc_content', array(
            'sloc' => $sloc,
            'sloc_has_activity_paletts' => $sloc_has_activity_paletts,
            'sloc_has_products' => $sloc_has_products,
        ));
    }

    public function actionViewPalett($id)

    {
        $activity_palett = ActivityPalett::model()->findByPk($id);
        if ($activity_palett == null) {
            throw new CHttpException('404', 'Paleta nije pronadjena.');
        }
        $activity_palett->activity->cleanScanned();
        $model = new ActivityPalettHasProduct('search');
        $model->unsetAttributes();
        $model->activity_palett_id = $id;
        $this->render('view_palett', array('model' => $model, 'activity_palett' => $activity_palett));
    }

    public function actionUpdateProduct($id)
    {
        $activity_palett_has_product = ActivityPalettHasProduct::model()->findByPk($id);
        if ($activity_palett_has_product === null) {
            throw new CHttpException('404', 'Proizvod nije pronadjen.');
        }
        $model = new ActivityPalettHasProductLog;

        $content = $activity_palett_has_product->getContent();

        $model->quantity = $content['quantity'];
        $model->packages = $content['packages'];
        $model->units = $content['units'];


        if (isset($_POST['ActivityPalettHasProductLog'])) {

            $attributes = $_POST['ActivityPalettHasProductLog'];

            $content = $activity_palett_has_product->getContent();
            $difference = array(
                'quantity' => $attributes['quantity'] - $content['quantity'],
                'packages' => $attributes['packages'] - $content['packages'],
                'units' => $attributes['units'] - $content['units'],
            );


            $model->attributes = array(
                'activity_palett_has_product_id' => $activity_palett_has_product->id,
                'activity_palett_id' => $activity_palett_has_product->activity_palett_id,
                'sscc' => $activity_palett_has_product->sscc,
                'product_id' => $activity_palett_has_product->product_id,
                'product_barcode' => $activity_palett_has_product->product_barcode,
                'quantity' => $difference['quantity'],
                'packages' => $difference['packages'],
                'units' => $difference['units'],
                'reason' => '*** POPIS *** ' . date('d.m.Y H:i'),
            );
            if ($model->save()) {
                $this->redirect(array('viewPalett','id'=>$model->activity_palett_id));
            }




        }


        $this->render('update_product',array('model'=>$model,'activity_palett_has_product' => $activity_palett_has_product));
    }

    public function actionUpdateSlocProduct($id)
    {
        $sloc_has_product = SlocHasProduct::model()->findByPk($id);
        if ($sloc_has_product === null) {
            throw new CHttpException('404', 'Proizvod nije pronadjen.');
        }
        $model = new SlocHasProductLog;

        $quantity = $sloc_has_product->getRealQuantity();

        $model->quantity = $quantity;


        if (isset($_POST['SlocHasProductLog'])) {

            $attributes = $_POST['SlocHasProductLog'];


            $difference = array(
                'quantity' => $attributes['quantity'] - $quantity,

            );


            $model->attributes = array(
                'sloc_has_product_id' => $sloc_has_product->id,
                'sloc_id' => $sloc_has_product->sloc_id,
                'sloc_code' => $sloc_has_product->sloc_code,
                'product_id' => $sloc_has_product->product_id,
                'product_barcode' => $sloc_has_product->product_barcode,
                'quantity' => $difference['quantity'],
                'reason' => '*** POPIS *** ' . date('d.m.Y H:i'),
            );
            if ($model->save()) {
                $this->redirect(array('slocContent','id'=>$model->sloc_id,'tab'=>1));
            }




        }


        $this->render('update_sloc_product',array('model'=>$model,'sloc_has_product' => $sloc_has_product));
    }


    public function actionAjaxDeleteProduct($id)
    {

        if (Yii::app()->request->isPostRequest) {

            $activity_palett_has_product = ActivityPalettHasProduct::model()->findByPk($id);
            if ($activity_palett_has_product) {
                $activity_palett_has_product->sscc = NULL;
                $activity_palett_has_product->delete();
            }


            if (!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }
    public function actionAjaxDeleteSlocProduct($id)
    {

        if (Yii::app()->request->isPostRequest) {

            $sloc_has_product = SlocHasProduct::model()->findByPk($id);
            if ($sloc_has_product) {
                $sloc_has_product->delete();
            }


            if (!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }
    public function actionAjaxDeletePalett($id)
    {

        if (Yii::app()->request->isPostRequest) {

            $activity_palett = ActivityPalett::model()->findByPk($id);
            if ($activity_palett && $activity_palett->getTotalRealQuantity() <= 0) {
                $activity_palett->delete();
            } else {
                throw new CHttpException(403,'Paleta sadrži proizvode!');
            }


            if (!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionCreateProductOnSSCC($id)
    {
        $activity_palett = ActivityPalett::model()->findByPk($id);
        if ($activity_palett == null) {
            throw new CHttpException(404,'Paleta ne postoji.');
        }
        $activity_palett_has_product = new ActivityPalettHasProduct;
        $activity_palett_has_product->activity_palett_id = $id;
        $activity_palett_has_product->sscc = $activity_palett->sscc;
        if (isset($_POST['ActivityPalettHasProduct'])) {
            $activity_palett_has_product->attributes = $_POST['ActivityPalettHasProduct'];
            $activity_palett_has_product->product_id = Product::model()->findByAttributes(array('product_barcode' => $activity_palett_has_product->product_barcode))->id;
            if ($activity_palett_has_product->save()) {

                $this->redirect(array('viewPalett','id'=>$id));
            }
        }
        $this->render('create_product_on_sscc',array(
            'activity_palett' => $activity_palett,
            'model' => $activity_palett_has_product
        ));
    }

    public function actionCreateProductOnSloc($id)
    {
        $sloc = Sloc::model()->findByPk($id);
        if ($sloc == null) {
            throw new CHttpException(404,'SLOC ne postoji.');
        }
        $sloc_has_product = new SlocHasProduct;
        $sloc_has_product->sloc_id = $id;
        $sloc_has_product->sloc_code = $sloc->sloc_code;
        if (isset($_POST['SlocHasProduct'])) {

            $sloc_has_product->attributes = $_POST['SlocHasProduct'];
            $product = Product::model()->findByAttributes(array('product_barcode' => $sloc_has_product->product_barcode));
            if ($product) {
                $sloc_has_product->product_id = $product->id;
                if ($sloc_has_product->save()) {
                    $this->redirect(array('slocContent','id'=>$id,'tab'=>1));
                }
            } else {
                $sloc_has_product->addErrors(array('product_barcode'=>'Proizvod ne postoji.'));
            }

        }

        $this->render('create_product_on_sloc',array(
            'sloc' => $sloc,
            'model' => $sloc_has_product
        ));
    }

    public function actionSlocChange()
    {
        $model = new SlocChange;
        if (isset($_POST['SlocChange'])) {
            $model->attributes = $_POST['SlocChange'];
            if ($model->validate()) {
                $sloc_source = Sloc::model()->findByAttributes(array('sloc_code' => $model->sloc_source));
                if ($sloc_source === null) {
                    $model->addError('sloc_source', 'SLOC ne postoji');
                }
                $sloc_destination = Sloc::model()->findByAttributes(array('sloc_code' => $model->sloc_destination));
                if ($sloc_destination === null) {
                    $model->addError('sloc_destination', 'SLOC ne postoji');
                }

                if (!$model->hasErrors()) {

                    $sloc_has_activity_paletts = SlocHasActivityPalett::model()->findAllByAttributes(array('sloc_id'=>$sloc_source->id));
                    foreach($sloc_has_activity_paletts as $sloc_has_activity_palett) {

                        $sql = 'UPDATE pick SET sloc_id = ' . $sloc_destination->id . ', sloc_code = "' . $sloc_destination->sloc_code . '" WHERE sloc_id=' . $sloc_has_activity_palett->sloc_id . ' AND sloc_code="' . $sloc_has_activity_palett->sloc_code . '" AND quantity=0';
                        Yii::app()->db->createCommand($sql)->execute();

                        $sql = 'UPDATE pick_web SET sloc_id = ' . $sloc_destination->id . ', sloc_code = "' . $sloc_destination->sloc_code . '" WHERE sloc_id=' . $sloc_has_activity_palett->sloc_id . ' AND quantity IS NULL';
                        Yii::app()->db->createCommand($sql)->execute();


                        $sloc_has_activity_palett->sloc_id = $sloc_destination->id;
                        $sloc_has_activity_palett->sloc_code = $sloc_destination->sloc_code;
                        if (!$sloc_has_activity_palett->save()) {
                            var_dump($sloc_has_activity_palett->getErrors());die();
                        }

                    }
                    $sloc_has_products = SlocHasProduct::model()->findAllByAttributes(array('sloc_id'=>$sloc_source->id));
                    foreach($sloc_has_products as $sloc_has_product) {

                        $sql = 'UPDATE pick SET sloc_id = ' . $sloc_destination->id . ', sloc_code = "' . $sloc_destination->sloc_code . '" WHERE sloc_id=' . $sloc_has_product->sloc_id . ' AND sloc_code="' . $sloc_has_product->sloc_code . '" AND quantity=0';
                        Yii::app()->db->createCommand($sql)->execute();

                        $sql = 'UPDATE pick_web SET sloc_id = ' . $sloc_destination->id . ', sloc_code = "' . $sloc_destination->sloc_code . '" WHERE sloc_id=' . $sloc_has_product->sloc_id . ' AND quantity IS NULL';
                        Yii::app()->db->createCommand($sql)->execute();


                        $sloc_has_product->sloc_id = $sloc_destination->id;
                        $sloc_has_product->sloc_code = $sloc_destination->sloc_code;

                        if (!$sloc_has_product->save()) {
                            var_dump($sloc_has_product->getErrors());die();
                        }
                    }


                        Yii::app()->user->setFlash('success','Sadržaj relociran sa ' . $model->sloc_source . ' na ' . $model->sloc_destination);
                        $model = new SlocChange;
                    }


                }
            }
        $this->render('sloc_change', array('model' => $model));

    }
}