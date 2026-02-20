<?php

class OutboundController extends Controller
{
    public function init()
    {
        parent::init();
        if (!in_array('outbound', $this->user->rf_access)) {
            throw new CHttpException('403', 'Zabranjen pristup.');
        }


    }

    public function actionIndex()
    {
        $sections = Section::model()->findAllByAttributes(array('location_id' => $this->user->location_id));
        if (isset($_POST['section_id'])) {
            Yii::app()->session['section_id'] = $_POST['section_id'];
            $this->redirect(array('orders'));
        }
        $this->render('index', array(
            'sections' => $sections,
        ));

    }

    public function actionOrders()
    {
        if (!isset(Yii::app()->session['section_id'])) {
            $this->redirect(array('index'));
            Yii::app()->end();
        }
        $section = Section::model()->findByPk(Yii::app()->session['section_id']);
        if ($section === null) {
            $activity_orders = ActivityOrder::model()->getActiveOutboundActivityOrders($this->user->location_id);
        } else {

            $activity_orders = $section->getActiveOutboundActivityOrders($this->user->location_id);
        }

        $filtered = array();


        if (isset($_POST['filter']) && $_POST['filter'] != '') {
            foreach ($activity_orders as $activity_order) {
                if ($_POST['filter'] == 0) {
                    if ($activity_order->activity->system_acceptance == 0) {
                        $filtered[] = $activity_order;
                    }
                } else {
                    if (count($activity_order->activityPaletts) > count($activity_order->loadedSSCCs) && $activity_order->activity->system_acceptance == 1) {
                        $filtered[] = $activity_order;
                    }
                }
            }
        } else {
            $filtered = $activity_orders;
        }


        if (isset($_POST['order_number']) && $_POST['order_number'] != '') {
            $activity_orders = $filtered;
            $filtered = array();

            foreach ($activity_orders as $activity_order) {
                if (strpos($activity_order->order_number, $_POST['order_number']) !== false) {
                    $filtered[] = $activity_order;
                }
            }

        }

        $sort = array();
        $model = new CArrayDataProvider($filtered, array(
            'id' => 'activity_orders',
            'keyField' => 'id',
            'sort' => $sort,
            'pagination' => array(
                'pageSize' => 999,
                'pageVar' => 'page',
            ),
        ));

        $this->render('orders', array(
            'model' => $model,
            'order_number' => isset($_POST['order_number']) ? $_POST['order_number'] : '',
        ));


    }

    public function actionCloseOrder($id)
    {
        $activity_order = ActivityOrder::model()->findByPk($id);
        if ($activity_order === null) {
            throw new CHttpException('404', 'Nalog ne postoji.');
        }


        $target = 0;
        foreach ($activity_order->activityOrderProducts as $activity_order_product) {

            $target += $activity_order_product->quantity;

        }

        $picked = 0;
        foreach ($activity_order->activityPaletts as $activity_palett) {
            foreach ($activity_palett->hasProducts as $hasProduct) {
                $picked += $hasProduct->quantity;
            }
        }

        if ($target == $picked) {


            $activity_order->status = 1;
            $activity_order->save();
            $activity_order = ActivityOrder::model()->findByPk($id);
            $close = true;
            foreach ($activity_order->activity->activityOrders as $order) {
                if ($order->status == 0) {
                    $close = false;
                }
            }

            if ($close) {
                $activity_order->activity->system_acceptance_datetime = date('Y-m-d H:i:s');
                if (!$activity_order->activity->save()) {
                    $activity_order->status = 0;
                    $activity_order->save();
                    throw new CHttpException('500', 'Greška pri zatvaranju naloga.');
                }
            }
        } else {
            Yii::app()->user->setFlash('error', 'Traženo: ' . $target . '; Pikovano: ' . $picked . '.');
            $this->render('pick_type', array(
                'order' => $activity_order
            ));
            Yii::app()->end();
        }

        $this->render('closed', array(
            'close' => $close,
            'activity_order' => $activity_order
        ));

    }

    public function actionOrder($id)
    {
        $activity_order = ActivityOrder::model()->findByPk($id);
        if ($activity_order === null) {
            throw new CHttpException('404', 'Nalog ne postoji.');
        }

        if ($activity_order->picking_list_dt == null) {
            $activity_order->picking_list_dt = date('Y-m-d H:i:s');
            $activity_order->save();
        } else {
            $seconds = strtotime(date('Y-m-d H:i:s')) - strtotime($activity_order->picking_list_dt);
            if ($seconds < 300) {
                $minutes = floor((300 - $seconds) / 60);
                $seconds = (300 - $seconds) % 60;
                if ($seconds < 10) {
                    $seconds = '0' . $seconds;
                }
                throw new CHttpException('500', 'Kreiranje piking liste u toku. Sačekajte još ' . $minutes . ':' . $seconds);
            }
        }

        /*
        if (!empty(Pick::model()->findAllByAttributes(array('activity_order_id' => $activity_order->id)))) {
            $this->redirect(array('pickType', 'id' => $activity_order->id));
            Yii::app()->end();
        }
        */

        if ($activity_order->totalProducts <= 50) {
            $active_slocs = $activity_order->getActiveSlocs();

            $picks_web = PickWeb::snakeSorting($active_slocs);

            foreach ($picks_web as $pick_web) {
                $pick = new Pick;
                $pick->attributes = array(
                    'activity_order_id' => $activity_order->id,
                    'sloc_id' => $pick_web['sloc_id'],
                    'sloc_code' => $pick_web['sloc_code'],
                    'activity_palett_id' => $pick_web['activity_palett_id'],
                    'sscc_source' => $pick_web['sscc_source'],
                    'product_id' => $pick_web['product_id'],
                    'product_barcode' => $pick_web['product_barcode'],
                    'target' => $pick_web['target'],
                    'quantity' => 0,
                    'packages' => 0,
                    'units' => 0,
                    'pick_type' => 'product',

                );
                if (!$pick->save()) {
                    var_dump($pick->getErrors());
                    die();
                }

            }
        } else {


            $active_paletts = $activity_order->getActivePaletts();

            $picks = Pick::snakeSorting($active_paletts);

            foreach ($picks as $palett) {
                $pick = new Pick;
                if ($palett['pick_palett']) {
                    $packages = $palett['packages'] ? $palett['packages'] : 0;
                    $units = $palett['units'] ? $palett['units'] : 0;

                } else {
                    $packages = 0;
                    $units = 0;
                }

                $pick->attributes = array(
                    'activity_order_id' => $activity_order->id,
                    'sloc_id' => $palett['sloc_id'],
                    'sloc_code' => $palett['sloc_code'],
                    'activity_palett_id' => $palett['activity_palett_id'],
                    'sscc_source' => $palett['sscc'],
                    'product_id' => $palett['product_id'],
                    'product_barcode' => $palett['product_barcode'],
                    'target' => $palett['pick_quantity'],
                    'quantity' => 0,
                    'packages' => $packages,
                    'units' => $units,
                    'pick_type' => $palett['pick_palett'] ? 'palett' : 'product',
                );
                if (!$pick->save()) {
                    var_dump($pick->getErrors());
                    die();
                }

            }
        }

        $picks = Pick::model()->findAllByAttributes(array('activity_order_id' => $id));

        $missing = '';
        foreach ($activity_order->activityOrderProducts as $activity_order_product) {
            $located = Pick::model()->findByAttributes(array('product_id' => $activity_order_product->product_id, 'activity_order_id' => $activity_order->id));
            if (!$located) {
                $missing .= '<li>' . $activity_order_product->product->product_barcode . ' - ' . $activity_order_product->product->title . ': ' . $activity_order_product->quantity . ' komada.</li>';
            }
        }


        $sort = array();
        $model = new CArrayDataProvider($picks, array(
            'id' => 'active_paletts',
            'keyField' => 'id',
            'sort' => $sort,
            'pagination' => array(
                'pageSize' => 9999,
                'pageVar' => 'page',
            ),
        ));
        $this->render('order', array(
            'model' => $model,
            'order' => $activity_order,
            'missing' => $missing
        ));

    }

    public function actionPickType($id)
    {

        $activity_order = ActivityOrder::model()->findByPk($id);
        if ($activity_order === null) {
            throw new CHttpException('404', 'Nalog ne postoji.');
        }

        $this->render('pick_type', array(
            'order' => $activity_order
        ));
    }

    public function actionPickPalett($id)
    {
        $activity_order = ActivityOrder::model()->findByPk($id);
        if ($activity_order === null) {
            throw new CHttpException('404', 'Nalog ne postoji.');
        }

        if (isset($_POST['unpicked'])) {
            $condition = 'activity_order_id = ' . $activity_order->id . ' AND pick_type="palett"';

            if ($_POST['unpicked'] == true) {
                $condition .= ' AND quantity =0';
            }

            if ($_POST['street'] != '') {
                $condition .= ' AND LEFT(sloc_code,3)  = "' . $_POST['street'] . '"';
            }
            $pick_paletts = Pick::model()->findAll(array('condition' => $condition));
        } else {
            $pick_paletts = Pick::model()->findAllByAttributes(array('activity_order_id' => $activity_order->id, 'pick_type' => 'palett'));
        }


        $sort = array();
        $model = new CArrayDataProvider($pick_paletts, array(
            'id' => 'paletts',
            'keyField' => 'id',
            'sort' => $sort,
            'pagination' => array(
                'pageSize' => 9999,
                'pageVar' => 'page',
            ),
        ));
        $this->render('pick', array(
            'model' => $model,
            'order' => $activity_order,
        ));

    }


    public function actionPickProduct($id)
    {
        $activity_order = ActivityOrder::model()->findByPk($id);
        if ($activity_order === null) {
            throw new CHttpException('404', 'Nalog ne postoji.');
        }


        $condition = 'activity_order_id = ' . $activity_order->id . ' AND pick_type="product" AND quantity=0';

        if (isset($_POST['unpicked'])) {
            $condition = 'activity_order_id = ' . $activity_order->id . ' AND pick_type="product"';

            if ($_POST['unpicked'] == true) {
                $condition .= ' AND quantity =0';
            }

            if ($_POST['street'] != '') {
                $condition .= ' AND LEFT(sloc_code,3)  = "' . $_POST['street'] . '"';
            }
            $pick_paletts = Pick::model()->findAll(array('condition' => $condition));
        } else {
            $pick_paletts = Pick::model()->findAllByAttributes(array('activity_order_id' => $activity_order->id, 'pick_type' => 'product'));
        }
        $sort = array();
        $model = new CArrayDataProvider($pick_paletts, array(
            'id' => 'products',
            'keyField' => 'id',
            'sort' => $sort,
            'pagination' => array(
                'pageSize' => 9999,
                'pageVar' => 'page',
            ),
        ));
        $this->render('pick', array(
            'model' => $model,
            'order' => $activity_order,
        ));

    }

    public function actionReset($id)
    {
        $activity_order = ActivityOrder::model()->findByPk($id);
        if ($activity_order == null) {
            throw new CHttpException('404', 'Nalog ne postoji.');
        }

        $sql = 'SELECT id FROM activity_palett WHERE activity_order_id = ' . $id;

        $activity_palett_ids = Yii::app()->db->createCommand($sql)->queryColumn();

        $sql = 'DELETE FROM pick WHERE activity_order_id = ' . $id;

        Yii::app()->db->createCommand($sql)->execute();
        if (!empty($activity_palett_ids)) {
            $sql = 'DELETE FROM activity_palett_has_product WHERE activity_palett_id IN (' . implode(',', $activity_palett_ids) . ')';
            Yii::app()->db->createCommand($sql)->execute();
        }
        $sql = 'UPDATE activity_order SET picking_list_dt = NULL WHERE id=' . $id;
        Yii::app()->db->createCommand($sql)->execute();


        $this->redirect(array('/outbound/order/' . $id));


    }

    public function actionLoad($id)
    {

        $activity_order = ActivityOrder::model()->findByPk($id);
        if ($activity_order === null) {
            throw new CHttpException('404', 'Nalog ne postoji.');
        }
        $model = new Pick;

        if (isset($_POST['Pick']['sscc_source']) && $_POST['Pick']['sscc_source'] != '') {

            $sscc_destination = $_POST['Pick']['sscc_source'];

            $picks = Pick::model()->findAllByAttributes(array('sscc_destination' => $sscc_destination, 'activity_order_id' => $id));

            if (count($picks) == 0) {
                $model->addError('sscc_source', 'Paleta ne pripada ovom nalogu.');
            } else {

                foreach ($picks as $pick) {

                    if ($pick->pick_type == 'palett' && $pick->status == 0) {
                        $pick->status = 1;
                        if ($pick->save()) {
                        $sloc_has_activity_palett = SlocHasActivityPalett::model()->findByAttributes(array('activity_palett_id' => $pick->activity_palett_id));

                        if ($sloc_has_activity_palett !== null) {
                                /*** remove palett from sloc and refresh form */
                                $sloc_has_activity_palett->delete();
                            }
                        } else {
                            $model->addError('sscc_source', 'Sistemska greška.');
                        }


                    } else {

                        if ($pick->status == 0) {

                            $pick->status = 1;
                            $pick->save();


                        } else {

                            $model->addError('sscc_source', 'Sistemska greška. Nemoguć utovar palete.');

                        }
                    }


                }


                if (count($activity_order->loadedSSCCs) == count($activity_order->pickedSSCCs)) {
                    $timestump = strtotime(date('Y-m-d H:i:s'));
                    $picks = Pick::model()->findAll(array('condition' => 'activity_order_id=' . $id . ' AND status=1 AND load_group IS NULL'));
                    foreach ($picks as $pick) {
                        $pick->load_group = $timestump;
                        $pick->save();
                    }
                    Yii::app()->user->setFlash('success', 'Sve palete iz naloga su utovarene.');
                    $this->redirect(array('loaded', 'id' => $id));
                    Yii::app()->end();
                } else if (!$model->hasErrors()) {
                    Yii::app()->user->setFlash('success', 'Utovarena paleta ' . $pick->sscc_destination);
                    $this->redirect(array('load', 'id' => $id));
                    Yii::app()->end();
                }
            }
        }


        $this->render('load', array(
                'order' => $activity_order,
                'model' => $model,
            )

        );
    }

    public function actionLoadCut($id)
    {
        $activity_order = ActivityOrder::model()->findByPk($id);
        if ($activity_order === null) {
            throw new CHttpException('404', 'Nalog ne postoji');
        }
        $timestump = strtotime(date('Y-m-d H:i:s'));
        $picks = Pick::model()->findAll(array('condition' => 'activity_order_id=' . $id . ' AND status=1 AND load_group IS NULL'));
        foreach ($picks as $pick) {
            $pick->load_group = $timestump;
            $pick->save();
        }
        Yii::app()->user->setFlash('warning', 'Kamion završen.');
        $this->redirect(array('load', 'id' => $id));
    }

    public function actionLoaded($id)
    {
        $model = ActivityOrder::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException('404', 'Nalog ne postoji.');
        }
        $this->render('loaded', array('model' => $model));
    }

    public function actionGateOut($id)
    {

        $activity_order = ActivityOrder::model()->findByPk($id);
        if ($activity_order === null) {
            throw new CHttpException('404', 'Nalog ne postoji.');
        }
        $model = new ActivityPalett('search');
        $model->unsetAttributes();
        $model->activity_order_id = $id;

        $this->render('gateout', array('model' => $model, 'order' => $activity_order));

    }


}