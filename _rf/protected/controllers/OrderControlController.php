<?php

class OrderControlController extends Controller
{

    public function init()
    {
        parent::init();
        if (!in_array('control',$this->user->rf_access)) {
            throw new CHttpException('403','Zabranjen pristup.');
        }


    }
    public function actionProduct()
    {
        $model = new ActivityOrder;
        if (isset($_POST['ActivityOrder'])) {
            $activity_order = ActivityOrder::model()->findByAttributes(array('order_number' => $_POST['ActivityOrder']['order_number']));
            if ($activity_order === null) {
                $model->addError('order_number','Nalog ne postoji');
            } else {
                $this->redirect(array('productControl','id'=>$activity_order->id));
            }
        }
        $this->render('order',array('model' => $model));
    }

    public function actionProductControl($id)
    {
        $activity_order_control = new ActivityOrderControl;

        $activity_order = ActivityOrder::model()->findByPk($id);
        if ($activity_order === null) {
            throw new CHttpException('404','Nalog ne postoji.');
        }

        if (isset($_POST['ActivityOrderControl'])) {


            $product = Product::model()->findByAttributes(array('product_barcode' => $_POST['ActivityOrderControl']['product_barcode']));
            if ($product == null) {
                $activity_order_control->addError('product_barcode','Proizvod ne postoji.');
            } else {

                $activity_order_product = ActivityOrderProduct::model()->findByAttributes(array('activity_order_id' => $id, 'product_id' => $product->id));

                if ($activity_order_product === null) {
                    $activity_order_control->addError('product_barcode','Proizvod nije u nalogu.');
                } else {
                    $activity_order_control->attributes = array(
                        'activity_order_id' => $id,
                        'product_id' => $product->id,
                        'product_barcode' => $product->product_barcode,
                        'control_type' => 'product',
                    );
                    if ($activity_order_control->save()) {
                        $this->redirect(array('/orderControl/productControl/'.$id));
                    }

                    Yii::app()->user->setFlash('success','OK');
                }




            }

        }
        $model = new ActivityOrderProduct('search');
        $model->unsetAttributes();
        $model->activity_order_id = $id;

        $this->render('products',array('model' => $model,'activity_order_control'=>$activity_order_control,'activity_order' => $activity_order));
    }


    public function actionProductControlView($id)

    {
        $activity_order = ActivityOrder::model()->findByPk($id);
        if ($activity_order === null) {
            throw new CHttpException('404','Nalog ne postoji.');
        }

        $model = new ActivityOrderControl('search');
        $model->unsetAttributes();
        $model->activity_id = $activity_order->activity_id;



        $this->render('product_view',array(
            'model'=>$model,
            'activity_order' => $activity_order
        ));
    }

    public function actionPalett()
    {
        $model = new ActivityOrder;
        if (isset($_POST['ActivityOrder'])) {
            $activity_order = ActivityOrder::model()->findByAttributes(array('order_number' => $_POST['ActivityOrder']['order_number']));
            if ($activity_order === null) {
                $model->addError('order_number','Nalog ne postoji');
            } else {
                $this->redirect(array('palettControl','id'=>$activity_order->id));
            }
        }
        $this->render('order',array('model' => $model));
    }

    public function actionPalettControl($id)
    {
        $activity_order_control = new ActivityOrderControl;
        $activity_order_control->unsetAttributes();

        $activity_order = ActivityOrder::model()->findByPk($id);
        if ($activity_order === null) {
            throw new CHttpException('404','Nalog ne postoji.');
        }

        if (isset($_POST['ActivityOrderControl'])) {


            $product = Product::model()->findByAttributes(array('product_barcode' => $_POST['ActivityOrderControl']['product_barcode']));
            if ($product == null) {
                $activity_order_control->addError('product_barcode','Proizvod ne postoji.');
            } else {

                $activity_order_product = ActivityOrderProduct::model()->findByAttributes(array('activity_order_id' => $id, 'product_id' => $product->id));

                if ($activity_order_product === null) {
                    $activity_order_control->addError('product_barcode','Proizvod nije u nalogu.');
                } else {
                    $activity_order_control->attributes = array(
                        'activity_order_id' => $id,
                        'product_id' => $product->id,
                        'product_barcode' => $product->product_barcode,
                        'control_type' => 'product',
                        'quantity' => $_POST['ActivityOrderControl']['quantity'],
                        'packages' => $_POST['ActivityOrderControl']['packages'],
                        'units' => $_POST['ActivityOrderControl']['units'],
                    );
                    if ($activity_order_control->save()) {
                        $this->redirect(array('/orderControl/palettControl/'.$id));
                    }

                    Yii::app()->user->setFlash('success','OK');
                }




            }

        }
        $model = new ActivityOrderProduct('search');
        $model->unsetAttributes();
        $model->activity_order_id = $id;

        $this->render('paletts',array('model' => $model,'activity_order_control'=>$activity_order_control,'activity_order' => $activity_order));
    }

    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest) {
// we only allow deletion via POST request
            ActivityOrderControl::model()->findByPk($id)->delete();

// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionAjaxGetProductQuantity()
    {
        $activity_order = ActivityOrder::model()->findByPk($_POST['activity_order_id']);
        if ($activity_order === null) {
            return false;
        }
        $product = Product::model()->findByAttributes(array('product_barcode'=> $_POST['product_barcode']));
        if ($product === null) {
            return false;
        }
        $quantity = 0;
        $packages = 0;
        $units = 0;

        foreach ($activity_order->activityPaletts as $activity_palett) {
            foreach($activity_palett->hasProducts as $activity_palett_has_product) {
                if ($activity_palett_has_product->product_barcode == $product->product_barcode) {
                    $quantity += $activity_palett_has_product->quantity;
                    $packages += $activity_palett_has_product->packages;
                    $units += $activity_palett_has_product->units;
                }
            }
        }
        echo json_encode(array(
            'quantity' => $quantity,
            'packages' => $packages,
            'units' => $units,
        ));
    }

    public function actionRemoveControl($id) {
        $sql = "DELETE FROM activity_order_control WHERE activity_order_id=" . $id;
        Yii::app()->db->createCommand($sql)->execute();
        $this->redirect(array('/orderControl/productControlView/'.$id));
    }
}