<?php

class TimeSlotController extends Controller

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
        $model = $this->loadModel($id);
        $order_products = new OrderProduct('perOrder');
        $order_products->unsetAttributes();

        $order_products->order_request_id = $model->order ? $model->order->id : 3;
        $this->render('view', array(
            'model' => $model,
            'order_products' => $order_products,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = TimeSlot::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {


        $model = new TimeSlot;

        if (isset($_POST['TimeSlot'])) {

            $model->attributes = $_POST['TimeSlot'];


            if ($model->save()) {
                //  $this->saveAttachmentsTmp($model);


                $this->redirect(array('create2', 'id' => $model->id, 'tab' => 1));
            }

        }


        $this->render('create', array(
            'model' => $model,

        ));
    }

    public function actionCreate2($id)
    {
        $model = TimeSlot::model()->findByPk($id);


        if (isset($_POST['TimeSlot'])) {


            $model->attributes = $_POST['TimeSlot'];


            if (isset($_POST['set-time']) && ($model->start_time == '' || $model->end_time == 0)) {
                $model->addError('start_time', Yii::t('app', 'Start time cannot be blank.'));
            } else {

                if ($model->save()) {

                    Yii::app()->user->setFlash('success', Yii::t('app', 'Saved'));
                    if ($model->gate) {
                        if ($model->order) {
                            $this->redirect(array('order/view', 'id' => $model->order_request_id));
                        } else {
                            $this->redirect(array('view', 'id' => $model->id));
                        }
                    } else {
                        $this->redirect(array('create2', 'id' => $model->id, 'tab' => 2));
                    }

                } else {
                    if (isset($_POST['_form_button'])) {
                        $this->redirect(array('create2', 'id' => $model->id));
                    } else {
                        Yii::app()->user->setFlash('success', Yii::t('app', 'Terms updated'));
                        $this->redirect(array('create2', 'id' => $model->id, 'tab' => 2));
                    }
                }


            }
        }


        $time_slot_detail = new TimeSlotDetail;


        if (isset($_POST['TimeSlotDetail'])) {
            $time_slot_detail->attributes = $_POST['TimeSlotDetail'];
            $time_slot_detail->time_slot_id = $id;
            if ($time_slot_detail->save()) {
                $this->saveAttachments($time_slot_detail);

                if (!$model->section || !$model->location) {

                    $model->section_id = $time_slot_detail->client->section_id;
                    $model->location_id = $time_slot_detail->client->location_id;

                    $model->save();

                    // echo '<pre>';var_dump($model->attributes);die();
                }
                $time_slot_detail = new TimeSlotDetail;
                Yii::app()->user->setFlash('success', Yii::t('app', 'Saved'));
                $this->redirect(array('create2', 'id' => $id, 'tab' => 1));
            }
        }
        $time_slot_details = new TimeSlotDetail('search');
        $time_slot_details->unsetAttributes();
        $time_slot_details->time_slot_id = $id;

        $clients = $this->user->clientsByAction('Create', $model->section_id);

        $terms = array();

        if (count($time_slot_details->search()->getData()) > 0 && $model->defined_date != null) {

            $terms = $model->getFreeTerms();
            $associative = array();
            foreach ($terms as $term) {
                $associative[$term] = $term;
            }
            $terms = $associative;

        }

        $this->render('create2', array(
            'model' => $model,
            'time_slot_details' => $time_slot_details,
            'time_slot_detail' => $time_slot_detail,

            'clients' => $clients,
            'terms' => $terms
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


        if (isset($_POST['TimeSlot'])) {

            $model->attributes = $_POST['TimeSlot'];

            if (isset($_POST['set-time']) && $model->start_time == "") {
                $model->addError('start_time', Yii::t('app', 'Start time cannot be blank.'));
            } else {
                if (!isset($_POST['set-time'])) {
                    $model->start_time = '';
                }

                if ($model->save()) {
                    //  $this->saveAttachments($model);
                    if (isset($_POST['set-time'])) {

                        Yii::app()->user->setFlash('success', Yii::t('app', 'Saved'));
                        if ($model->order) {
                            $this->redirect(array('order/view', 'id' => $model->order_request_id));
                        } else {
                            $this->redirect(array('view', 'id' => $model->id));
                        }
                    }
                    Yii::app()->user->setFlash('success', Yii::t('app', 'Terms updated'));
                    $this->redirect(array('update', 'id' => $model->id, 'tab' => 2));

                }
            }
        }


        $time_slot_detail = new TimeSlotDetail;


        if (isset($_POST['TimeSlotDetail'])) {
            $time_slot_detail->attributes = $_POST['TimeSlotDetail'];
            $time_slot_detail->time_slot_id = $id;
            if ($time_slot_detail->save()) {
                $this->saveAttachments($time_slot_detail);
                $model->start_time = '';
                if ($model->section_id == null || $model->location_id == null) {
                    $model->section_id = $time_slot_detail->client->section_id;
                    $model->location_id = $time_slot_detail->client->location_id;
                }

                $model->save();
                $time_slot_detail = new TimeSlotDetail;
                Yii::app()->user->setFlash('success', Yii::t('app', 'Terms updated'));
                $this->redirect(array('update', 'id' => $model->id, 'tab' => 1));
            }
        }
        $time_slot_details = new TimeSlotDetail('search');
        $time_slot_details->unsetAttributes();
        $time_slot_details->time_slot_id = $id;
        $clients = $this->user->clientsByAction('Update', $model->section_id);
        $terms = array();


        if (count($time_slot_details->search()->getData()) > 0 && $model->defined_date != null) {

            $terms = $model->getFreeTerms();
            $associative = array();
            foreach ($terms as $term) {
                $associative[$term] = $term;
            }
            $terms = $associative;

        }

        $this->render('update', array(
            'model' => $model,
            'time_slot_details' => $time_slot_details,
            'time_slot_detail' => $time_slot_detail,

            'clients' => $clients,
            'terms' => $terms
        ));
    }

    public function saveAttachments($time_slot_detail)
    {
        $attachments = CUploadedFile::getInstancesByName('TimeSlotDetailsAttachment[files]');


        if (isset($attachments) && count($attachments) > 0) {
            $folder = Yii::app()->basePath . '/../upload/time_slot/' . date('Ymd');

            if (!is_dir($folder)) {
                mkdir($folder);
            }
            $folder = $folder . '/' . $time_slot_detail->id;
            if (!is_dir($folder)) {
                mkdir($folder);
            }

            $filepath = '/upload/time_slot/' . date('Ymd') . '/' . $time_slot_detail->id;

            foreach ($attachments as $attachment => $file) {
                if ($file->saveAs($folder . '/' . $file->name)) {
                    $time_slot_detail_attachment = new TimeSlotDetailsAttachment;
                    $time_slot_detail_attachment->time_slot_details_id = $time_slot_detail->id;
                    $time_slot_detail_attachment->filename = $file->name;
                    $time_slot_detail_attachment->filepath = $filepath . '/' . $file->name;
                    $time_slot_detail_attachment->save();

                }
            }
        }
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest) {
            $this->loadModel($id)->delete();

            if (!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            } else {
                echo 'OK';
            }
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model = new TimeSlot('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['TimeSlot'])) {
            $model->attributes = $_GET['TimeSlot'];
        }
        if ($this->user->global_client == 0) {
            $client_ids = $this->user->canView;
            $sql = 'SELECT time_slot_id FROM time_slot_details WHERE client_id IN (' . implode(',', $client_ids) . ')';
            $model->filtered = Yii::app()->db->createCommand($sql)->queryColumn();
        } else {
            $model->location_id = $this->user->location_id;
        }

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Ajax functions for TSM
     */

    public function actionAjaxGetTermEnd($id)
    {

        $time_slot = $this->loadModel($id);


        $start_time = $_POST['start_time'];
        if ($start_time == '') {
            echo '';
            return;
        }

        echo $time_slot->getTermEnd($start_time);

    }

    public function actionAjaxDeleteDetail($id)
    {


        if (Yii::app()->request->isPostRequest) {
            $time_slot_detail = TimeSlotDetail::model()->findByPk($id);;
            if ($time_slot_detail) {
                $time_slot_id = $time_slot_detail->time_slot_id;
                if ($time_slot_detail->delete()) {
                    $model = $this->loadModel($time_slot_id);
                    if (!$model->timeSlotDetails) {
                        $model->location_id = null;
                        $model->section_id = null;
                        $model->defined_date = null;
                        $model->end_time = null;
                    }
                    $model->start_time = null;
                    Yii::app()->user->setFlash('success', Yii::t('app', 'Terms updated'));
                    $model->save();
                }
            }
        } else {

        //    throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }


    public function actionAjaxDeleteAttachment($id)
    {

        if (Yii::app()->request->isPostRequest) {
            $time_slot_attachment = TimeSlotDetailsAttachment::model()->findByPk($id);;
            if ($time_slot_attachment) {

                $time_slot_attachment->delete();


            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }


    public function actionAjaxReset($id)
    {

        if (Yii::app()->request->isPostRequest) {
            $this->loadModel($id)->delete();
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionAjaxDownloadAttachment($id)
    {
        $model = TimeSlotDetailsAttachment::model()->findByPk($id);
        $this->download($model->filepath);
    }


    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected
    function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'time-slot-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
