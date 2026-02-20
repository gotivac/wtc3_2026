<?php

class InboundController extends Controller
{
    public $user;

    public function init()
    {
        parent::init();
       // $this->user = User::model()->findByPk(Yii::app()->user->id);
        if ($this->user === null) {
            $this->redirect('site/logout');
        }
        if ($this->user->location == null) {
            throw new CHttpException('500', 'Korisnik nema lokaciju.');
        }


        if (!in_array('inbound',$this->user->rf_access)) {
            throw new CHttpException('403','Zabranjen pristup.');
        }



    }

    public function actionIndex()
    {
        $model = new Activity('inbound');
        $model->location_id = $this->user->location->id;
        $model->direction = 'in';
        $this->render('index', array(
            'model' => $model
        ));
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = Activity::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function actionCloseActivity($id)
    {
        $model = Activity::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException('404', 'Aktivnost ne postoji');
        }


        foreach ($model->activityOrders as $activity_order) {
            foreach ($activity_order->activityOrderProducts as $activity_order_product) {
                $sql = 'SELECT id FROM activity_palett WHERE activity_order_id = ' . $activity_order->id;
                $activity_palett_ids = Yii::app()->db->createCommand($sql)->queryColumn();

                if (!empty($activity_palett_ids)) {
                    $sql = 'SELECT SUM(quantity) FROM activity_palett_has_product WHERE activity_palett_id IN (' . implode(',', $activity_palett_ids) . ') AND product_id = ' . $activity_order_product->product_id;
                    $quantity = Yii::app()->db->createCommand($sql)->queryScalar();

                    if ($quantity != $activity_order_product->quantity) {
                        if ($quantity == NULL) {
                            $quantity = 0;
                        }
                        Yii::app()->user->setFlash('error', 'Nalog: <br>' . $activity_order->order_number . "<br> Proizvod: <br> " . $activity_order_product->product->title . ' &bull; ' . $activity_order_product->product->product_barcode . '<br> ' . 'Najava: ' . $activity_order_product->quantity . '<br> Prijem: ' . $quantity);
                        $this->render('closed', array('model' => $model));
                        Yii::app()->end();
                    }

                } else {
                    Yii::app()->user->setFlash('error', 'Nalog: <br>' . $activity_order->order_number . "<br> Proizvod: <br> " . $activity_order_product->product->title . ' &bull; ' . $activity_order_product->product->product_barcode . '<br> ' . 'Najava: ' . $activity_order_product->quantity . '<br> Prijem: 0');
                        $this->render('closed', array('model' => $model));
                        Yii::app()->end();
                }
            }
            $activity_order->status = 1;
            $activity_order->save();
        }

        $model->system_acceptance_datetime = date('Y-m-d H:i:s');
        if ($model->save()) {

            foreach ($model->activityPaletts as $activity_palett) {
                if (!in_array($activity_palett->sscc, $model->scannedSSCCs)) {
                    $activity_palett->delete();
                }
            }

            $model->cleanScanned(true);


            Yii::app()->user->setFlash('info', 'Prijem završen');
            $model = Activity::model()->findByPk($id);
            $this->render('closed', array('model' => $model));

        } else {
            $errors = $model->getErrors();

            Yii::app()->user->setFlash('error', $errors['system_acceptance_datetime'][0]);
            $model = Activity::model()->findByPk($id);
            $this->render('closed', array('model' => $model));
        }
    }

    public function actionDeleteActivity($id)
    {
        $model = Activity::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException('404', 'Aktivnost ne postoji');
        }
        foreach ($model->activityPaletts as $activity_palett) {
            foreach ($activity_palett->hasProducts as $scanned_sscc) {
                $scanned_sscc->delete();
            }
        }

        Yii::app()->user->setFlash('info', 'Prijem poništen');
        $model = Activity::model()->findByPk($id);
        $this->render('deleted', array('model' => $model));

    }

    public function actionLocate($id)
    {
        $activity = Activity::model()->findByPk($id);
        if ($activity === null) {
            throw new CHttpException('404', 'Aktivnost ne postoji');
        }
        $model = new SlocHasActivityPalett;

        if (isset($_POST['SlocHasActivityPalett'])) {
            $model->attributes = $_POST['SlocHasActivityPalett'];

            $activity_palett = ActivityPalett::model()->findByAttributes(array('activity_id' => $activity->id, 'sscc' => $model->sscc));

            if ($activity_palett === null) {
                $model->addError('sscc', 'Paleta ne pripada ovoj aktivnosti.');
            } else {
                if ($activity_palett->isLocated()) {
                    $model->addError('sscc', 'Paleta je već locirana na ' . $activity_palett->inSloc->sloc->sloc_code);
                } else {
                    if (count($activity_palett->hasProducts) == 0) {
                        $model->addError('sscc', 'Paleta je prazna.');
                    } else {
                        $sloc = Sloc::model()->findByAttributes(array('sloc_code' => $model->sloc_code));
                        if ($sloc === null) {
                            $model->addError('sloc_code', 'SLOC ne postoji.');
                        } else {

                            $model->activity_palett_id = $activity_palett->id;
                            $model->sloc_id = $sloc->id;
                            if ($model->save()) {
                                $accepted = Accept::model()->findByAttributes(array('activity_palett_id' => $activity_palett->id));
                                $accepted->status = 1;
                                $accepted->save();

                                Yii::app()->user->setFlash('success', '<b>LOCIRANO</b><br>SSCC: ' . $model->sscc . '<br>' . 'SLOC: ' . $model->sloc_code);

                                if (count($activity->unlocated) == 0) {
                                    $this->redirect(array('located', 'id' => $activity->id));
                                    Yii::app()->end();
                                }
                                $this->redirect(array('locate', 'id' => $activity->id));

                            } else {
                                Yii::app()->user->setFlash('error',CHtml::errorSummary($model));
                            }

                        }
                    }
                }

            }
        }

        $this->render('locate', array(
            'activity' => $activity,
            'model' => $model,
        ));

    }

    public function actionLocated($id)
    {
        $model = Activity::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException('404', 'Aktivnost ne postoji.');
        }
        $this->render('located', array('model' => $model));
    }

    public function actionViewActivity($id)
    {
        $activity = Activity::model()->findByPk($id);
        if ($activity === null) {
            throw new CHttpException('404', 'Aktivnost ne postoji.');
        }
        $model = new ActivityPalett('search');
        $model->unsetAttributes();
        $model->activity_id = $id;

        $this->render('view_activity', array('model' => $model, 'activity' => $activity));
    }
}