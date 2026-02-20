<?php

class WebController extends Controller
{
    public function init()
    {
        parent::init();
        if (!in_array('web',$this->user->rf_access)) {
            throw new CHttpException('403','Zabranjen pristup.');
        }


    }
    public function ajaxGetStorageType()
    {
        if (isset($_POST['sscc']) && $_POST['sscc'] != '' && isset($_POST['sloc_code']) && $POST['sloc_code']) {
            $sloc_has_activity_palett = SlocHasActivityPalett::model()->findByAttributes(array('sscc' => $_POST['sscc'], 'sloc_oode' => $_POST['sloc_code']));
            if ($sloc_has_activity_palett !== null) {
                echo json_encode(array('success' => 1, 'storage_type_id' => $sloc_has_activity_palett->storage_type_id));
                Yii::app()->end();
            }
        }
        echo json_encode(array('success' => 0));

    }

    public function actionEmpty()
    {
        $model = new WebEmpty;

        if (isset($_POST['WebEmpty'])) {
            $model->attributes = $_POST['WebEmpty'];

            $model->packages = 0;
            $model->units = $model->quantity;

            if ($model->validate()) {
                $sloc_source = Sloc::model()->findByAttributes(array('sloc_code' => $model->sloc_source));

                if ($sloc_source === null) {
                    $model->addError('sloc_source', 'SLOC ne postoji');
                }

                $activity_palett = ActivityPalett::model()->findByAttributes(array('sscc' => $model->sscc_destination));
                if ($activity_palett === null) {
                    $model->addError('sscc_destination', 'Paleta ne postoji.');

                }


                $sloc_has_product = SlocHasProduct::model()->findByAttributes(array('sloc_id' => $sloc_source->id, 'product_barcode' => $model->product_barcode));
                if ($sloc_has_product === null) {
                    $model->addError('product_barcode', 'Proizvod se ne nalazi u SLOC-u');
                } else {
                    if ($sloc_has_product->realQuantity < $model->quantity) {
                        $model->addError('quantity', 'Max: ' . $sloc_has_product->realQuantity);
                    }
                }

                if (!$model->hasErrors()) {

                    $pick_web = new PickWeb;
                    $pick_web->attributes = array(
                        'sloc_id' => $sloc_has_product->sloc_id,
                        'sloc_code' => $sloc_has_product->sloc_code,

                        'product_id' => $sloc_has_product->product_id,
                        'product_barcode' => $sloc_has_product->product_barcode,
                        'target' => 0,
                        'quantity' => $model->quantity,

                        'status' => 1,
                    );
                    if ($pick_web->save()) {
                        $initial = false;
                        $activity_palett_has_product = ActivityPalettHasProduct::model()->findByAttributes(array('activity_palett_id' => $activity_palett->id, 'product_id' => $sloc_has_product->product_id));
                        if ($activity_palett_has_product === null) {

                            $activity_palett_has_product = new ActivityPalettHasProduct;
                            $activity_palett_has_product->attributes = array(
                                'activity_palett_id' => $activity_palett->id,
                                'sscc' => $activity_palett->sscc,
                                'product_id' => $sloc_has_product->product_id,
                                'product_barcode' => $sloc_has_product->product_barcode,
                                'quantity' => $model->quantity,
                                'packages' => 0,
                                'units' => $model->quantity,
                            );

                            if (!$activity_palett_has_product->save()) {
                                $pick_web->delete();
                                $model->addError('sloc_source', 'Greška. Pokušajte ponovo.');
                            } else {
                                Yii::app()->user->setFlash('success', 'Proizvod ' . $model->product_barcode . ' količina ' . $model->quantity . ' na ' . $model->sscc_destination);
                                $model = new WebEmpty;
                            }
                        } else {
                            $activity_palett_has_product_log = new ActivityPalettHasProductLog;
                            $activity_palett_has_product_log->attributes = array(
                                'activity_palett_has_product_id' => $activity_palett_has_product->id,
                                'activity_palett_id' => $activity_palett->id,
                                'sscc' => $activity_palett->sscc,
                                'product_id' => $sloc_has_product->product_id,
                                'product_barcode' => $sloc_has_product->product_barcode,
                                'quantity' => $model->quantity,
                                'packages' => 0,
                                'units' => $model->quantity,
                                'reason' => 'Povrat sa ' . $sloc_has_product->sloc_code,
                            );
                            if (!$activity_palett_has_product_log->save()) {
                                $pick_web->delete();
                                $model->addError('sloc_source', 'Greška. Pokušajte ponovo.');
                            } else {
                                Yii::app()->user->setFlash('success', 'Proizvod ' . $model->product_barcode . ' količina ' . $model->quantity . ' na ' . $model->sscc_destination);
                                $model = new WebEmpty;
                            }
                        }


                    } else {
                        $model->addError('sloc_source', 'Greška. Pokušajte ponovo.');
                    }


                }


            }

        }

        $this->render('empty', array('model' => $model));
    }
    public function actionFill()
    {
        $model = new WebFill;

        if (isset($_POST['WebFill'])) {
            $model->attributes = $_POST['WebFill'];

            if ($model->validate()) {

                $activity_palett = ActivityPalett::model()->findByAttributes(array('sscc' => $model->sscc_source));
                if ($activity_palett === null) {
                    $model->addError('sscc_source', 'Paleta ne postoji.');

                }
                $sloc_destination = Sloc::model()->findByAttributes(array('sloc_code' => $model->sloc_destination));
                if ($sloc_destination === null) {
                    $model->addError('sloc_destination', 'SLOC ne postoji');
                }

                $activity_palett_has_product = ActivityPalettHasProduct::model()->findByAttributes(array('activity_palett_id' => $activity_palett->id, 'product_barcode' => $model->product_barcode));
                if ($activity_palett_has_product === null) {
                    $model->addError('product_barcode', 'Proizvod se ne nalazi na paleti');
                } else {
                    if ($activity_palett_has_product->stockQuantity < $model->quantity) {
                        $model->addError('quantity', 'Max: ' . $activity_palett_has_product->stockQuantity);
                    }
                }

                if (!$model->hasErrors()) {

                    $pick = new Pick;
                    $pick->attributes = array(
                        'sloc_id' => $activity_palett->inSloc ? $activity_palett->inSloc->sloc_id : null,
                        'sloc_code' => $activity_palett->inSloc ? $activity_palett->inSloc->sloc_code : null,
                        'activity_palett_id' => $activity_palett->id,
                        'sscc_source' => $activity_palett->sscc,
                        'sscc_destination' => $sloc_destination->sloc_code,
                        'product_id' => $activity_palett_has_product->product_id,
                        'product_barcode' => $activity_palett_has_product->product_barcode,
                        'target' => 0,
                        'quantity' => $model->quantity,
                        'packages' => $model->packages,
                        'units' => $model->units,
                        'pick_type' => 'product',
                        'status' => 1,
                    );
                    if ($pick->save()) {
                        $initial = false;
                        $sloc_has_product = SlocHasProduct::model()->findByAttributes(array('sloc_id' => $sloc_destination->id, 'product_id' => $activity_palett_has_product->product_id));
                        if ($sloc_has_product === null) {

                            $sloc_has_product = new SlocHasProduct;
                            $sloc_has_product->attributes = array(
                                'sloc_id' => $sloc_destination->id,
                                'sloc_code' => $sloc_destination->sloc_code,
                                'product_id' => $activity_palett_has_product->product_id,
                                'product_barcode' => $activity_palett_has_product->product_barcode,
                                'quantity' => $model->quantity
                            );

                            if (!$sloc_has_product->save()) {
                                $pick->delete();
                                $model->addError('sscc_source', 'Greška. Pokušajte ponovo.');
                            } else {
                                Yii::app()->user->setFlash('success', 'Proizvod ' . $model->product_barcode . ' količina ' . $model->quantity . ' na ' . $model->sloc_destination);
                                $model = new WebFill;
                            }
                        } else {


                            $sloc_has_product_log = new SlocHasProductLog;
                            $sloc_has_product_log->attributes = array(
                                'sloc_has_product_id' => $sloc_has_product->id,
                                'sloc_id' => $sloc_destination->id,
                                'sloc_code' => $sloc_destination->sloc_code,
                                'product_id' => $activity_palett_has_product->product_id,
                                'product_barcode' => $activity_palett_has_product->product_barcode,
                                'quantity' => $model->quantity,
                                'reason' => 'Dopuna',
                            );
                            if (!$sloc_has_product_log->save()) {
                                $pick->delete();
                                $model->addError('sscc_source', 'Greška. Pokušajte ponovo.');
                            } else {
                                Yii::app()->user->setFlash('success', 'Proizvod ' . $model->product_barcode . ' količina ' . $model->quantity . ' na ' . $model->sloc_destination);
                                $model = new WebFill;
                            }
                        }


                    } else {
                        $model->addError('sscc_source', 'Greška. Pokušajte ponovo.');
                    }


                }


            }


        }
        $this->render('fill', array('model' => $model));

    }

    public function actionStart()
    {
        $model = new WebOrder;
        if (isset($_POST['WebOrder'])) {
            $web_order_number = $_POST['WebOrder']['order_number'];
            $web_order = WebOrder::model()->findByAttributes(array('order_number' => $web_order_number));
            if ($web_order === null) {
                $model->addError('order_number', 'Web nalog ' . $web_order_number . ' ne postoji.');
            } else if ($web_order->status == 1) {
                $model->addError('order_number', 'Web nalog ' . $web_order_number . ' je već završen.');
            } else {


                if (empty(PickWeb::model()->findAllByAttributes(array('web_order_id' => $web_order->id)))) {

                    $active_slocs = $web_order->getActiveSlocs();
                    $picks = PickWeb::snakeSorting($active_slocs);



                    foreach ($picks as $pick) {
                        $pick_web = new PickWeb;
                        foreach ($pick as $k => $v) {
                            $pick_web->$k = $v;
                        }
                        if (!$pick_web->save()) {
                            $sql = "DELETE FROM pick_web WHERE web_order_id=" . $model->id;
                            Yii::app()->db->createCommand($sql)->execute();
                            throw new CHttpException('500', 'System Error. Try again.');
                        }
                    }





                }

                $this->redirect(array('view', 'id' => $web_order->id));
            }

        }
        $this->render('start', array(
            'model' => $model,
        ));
    }

    public function actionView($id)
    {
        $web_order = WebOrder::model()->findByPk($id);
        if ($web_order === null) {
            throw new CHttpException('404', 'Nalog ne postoji.');
        }

        $model = new PickWeb('search');
        $model->unsetAttributes();
        $model->web_order_id = $web_order->id;


        $this->render('view', array(
            'web_order' => $web_order,
            'model' => $model,
        ));
    }

    public function actionUpdate($id)
    {
        $model = PickWeb::model()->findByPk($id);

        if ($model === null) {
            throw new CHttpException('404', 'Pick ne postoji.');
        }

        $sloc_code = $model->sloc_code;
        $sscc_source = $model->sscc_source;
        $model->sscc_source = null;
        $model->product_barcode = null;
        $model->sloc_code = null;
        $model->quantity = $model->target;
        $success = isset($_GET['success']) ? $_GET['success'] : 2;
        if (isset($_POST['PickWeb'])) {
            $valid = PickWeb::model()->findByPk($id);
            $model->attributes = $_POST['PickWeb'];

            if ($model->quantity > $model->target) {
                $model->addError('quantity', 'Količina veća od tražene.');
            }
            if ($valid->activity_palett_id == null) {
                if ($valid->sloc_code != $model->sloc_code) {
                    $model->addError('sloc_code', 'Neispravan SLOC. Skeniraj ' . $valid->sloc_code);
                }

                if ($valid->product_barcode != $model->product_barcode) {
                    $model->addError('product_barcode', 'Neispravan proizvod. Skeniraj ' . $valid->product_barcode);
                }
                $sloc_has_product = SlocHasProduct::model()->findByAttributes(array('sloc_id' => $model->sloc_id, 'product_id' => $model->product_id));


                if ($sloc_has_product) {

                    if ($model->quantity > $sloc_has_product->realQuantity) {
                        $model->addError('quantity', 'MAX: ' . $sloc_has_product->quantity);
                    }
                } else {
                    $model->addError('sloc_code', 'Sloc ne sadrži proizvod');
                }
            } else {
                if ($valid->sscc_source != $model->sscc_source) {
                    $model->addError('sscc_source', 'Neispravan SSCC. Skeniraj ' . $valid->sscc_source);
                }

                if ($valid->product_barcode != $model->product_barcode) {
                    $model->addError('product_barcode', 'Neispravan proizvod. Skeniraj ' . $valid->product_barcode);
                }
                $activity_palett_has_product = ActivityPalettHasProduct::model()->findByAttributes(array('activity_palett_id' => $model->activity_palett_id, 'product_id' => $model->product_id));

                if ($activity_palett_has_product) {
                    $content = $activity_palett_has_product->getContent();
                    if ($model->quantity > $content['quantity']) {
                        $model->addError('quantity', 'MAX: ' . $content['quantity']);
                    }
                } else {
                    $model->addError('sscc_source', 'Paleta ne sadrži proizvod');
                }
            }

            $model->status = 1;



            if (!$model->hasErrors()) {


                    if ($model->save()) {
                        $picks = PickWeb::model()->findAllByAttributes(array('web_order_id' => $model->web_order_id,));
                        foreach ($picks as $k => $pick) {
                            if ($pick->id == $model->id) {
                                $next = (isset($picks[$k + 1]) && $picks[$k + 1]->status == 0) ? $picks[$k + 1] : false;
                            }
                        }
                        if ($next) {
                            $this->redirect(array('update', 'id' => $next->id, 'success' => 1));
                        } else {
                            $this->redirect(array('/web/' . $model->web_order_id));
                        }

                    } else {
                        $success = 0;
                        Yii::app()->user->setFlash('error', CHtml::errorSummary($model));
                    }

            }


        }

        $this->render('update', array('model' => $model, 'success' => $success, 'sloc_code' => $sloc_code, 'sscc_source' => $sscc_source));
    }

    public function actionReset($id)
    {
        $model = PickWeb::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException('404','Pick ne postoji.');
        }

        $model->status = 0;
        $model->quantity = null;

        $model->save();
        $this->redirect(array('/web/' . $model->web_order_id));
    }

    public function actionClose($id)
    {
        $web_order = WebOrder::model()->findByPk($id);
        if ($web_order === null) {
            throw new CHttpException('404','Nalog ne postoji.');
        }
        $web_order->status = 1;
        if ($web_order->save()) {
            Yii::app()->user->setFlash('success','Nalog ' . $web_order->order_number . ' završen.');
            $this->redirect(array('start'));
            Yii::app()->end();
        } else {
            $this->redirect(array('view',array('id'=>$id)));
            Yii::app()->end();
        }
    }


}