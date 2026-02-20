<?php
require Yii::getPathOfAlias('application') . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ActivityController extends Controller
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
        $products = new ActivityPalettHasProduct('search');
        $products->unsetAttributes();
        $products->activity = $model;


        if ($model->direction == 'out') {
            $picks = new Pick('activitySearch');
            $picks->unsetAttributes();
            $picks->activity_id = $id;
        } else {
            $picks = false;
        }


        $order_request = $model->orderRequest;
        if ($order_request != NULL) {
            $order_products = new OrderProduct('perOrder');
            $order_products->unsetAttributes();
            $order_products->order_request_id = $order_request->id;
        } else {
            $order_products = false;
        }
        $activity_paletts = new ActivityPalett('search');
        $activity_paletts->unsetAttributes();
        $activity_paletts->activity_id = $model->id;

        $activity_order_controls = new ActivityOrderProduct('search');
        $activity_order_controls->unsetAttributes();
        $activity_order_controls->activity_id = $id;
        /*
        $activity_order_controls = new ActivityOrderControl('search');
        $activity_order_controls->unsetAttributes();
        $activity_order_controls->activity_id = $id;
*/
/*
        $activity_order = ActivityOrder::model()->findByPk($id);
        if ($activity_order === null) {
            throw new CHttpException('404', 'Nalog ne postoji.');
        }
*/

        $errors = array();

        if ($model->direction == 'out') {
            foreach ($model->activityOrders as $activity_order) {
                if (empty(Pick::model()->findAllByAttributes(array('activity_order_id'=>$activity_order->id)))) {
                    continue;
                }
                foreach ($activity_order->activityOrderProducts as $activity_order_product) {

                    $sql = 'SELECT SUM(target) FROM pick WHERE activity_order_id=' . $activity_order_product->activity_order_id . ' AND product_id = ' . $activity_order_product->product_id;
                    $res = Yii::app()->db->createCommand($sql)->queryScalar();
                    if ($activity_order_product->quantity > $res || $res == null) {

                        $product = Product::model()->findByPk($activity_order_product->product_id);


                        $errors[] = array(
                            'product_barcode' => $product->product_barcode,
                            'product_title' => $product->title,
                            'target' => $activity_order_product->quantity,
                            'quantity' => $res
                        );
                    }


                }
            }
        }


        $this->render('view', array(
            'model' => $model,
            'products' => $products,
            'order_products' => $order_products,
            'order_request' => $order_request,
            'activity_paletts' => $activity_paletts,
            'activity_order_controls' => $activity_order_controls,
            'picks' => $picks,
            'errors' => $errors,
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

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($id = false)
    {
        $model = new Activity;
        if ($id) {
            $order_request = OrderRequest::model()->findByPk($id);
            if ($order_request) {
                $model->urgent = $order_request->urgent;
                $model->location_id = $order_request->location_id;
                $model->order_request_id = $order_request->id;
                $model->activity_type_id = $order_request->activity_type_id;
                $model->direction = $order_request->activityType->direction;
                $model->gate_id = $order_request->timeSlot ? $order_request->timeSlot->gate_id : null;
                $model->license_plate = $order_request->timeSlot ? $order_request->timeSlot->license_plate : null;
                $model->notes = $order_request->timeSlot ? $order_request->timeSlot->notes : null;
            }
        }


        if (isset($_POST['Activity'])) {
            $model->attributes = $_POST['Activity'];


            if ($model->save()) {


                if ($model->orderRequest) {
                    $model->createOrdersFromOrderRequest();
                }

                Yii::app()->user->setFlash('success', Yii::t('app', 'Created'));
                $this->redirect(array('update', 'id' => $model->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
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

        if ($model->direction == 'in' && $model->truck_dispatch_time != null && $this->user->roles != 'superadministrator') {
            $this->redirect(array('view', 'id' => $id));
        }

        $activity_order = new ActivityOrder;

        if (isset($_POST['ActivityOrder'])) {
            $activity_order->attributes = $_POST['ActivityOrder'];
            $activity_order->activity_id = $model->id;
            if ($activity_order->save()) {
                echo json_encode($activity_order->attributes);

            } else {
                echo CActiveForm::validate($activity_order);
            }
            Yii::app()->end();
        }

        if (isset($_POST['CloseOrder'])) {
            $activity_order = ActivityOrder::model()->findByPk($_POST['CloseOrder']['activity_order_id']);
            if ($activity_order === null) {
                throw new CHttpException('404', 'Nalog ne postoji');
            }
            $activity_order->notes = $_POST['CloseOrder']['notes'];

            if ($activity_order->notes != '') {
                $activity_order->notes .= "\n-- " . $this->user->name . ", " . date('d.m.Y H:i:s');

                $activity_order->status = 1;
                if ($activity_order->save()) {
                    Yii::app()->user->setFlash('success', 'Nalog ' . $activity_order->order_number . ' zatvoren sa obrazloženjem: ' . $activity_order->notes);
                }
            } else {
                Yii::app()->user->setFlash('error', 'Obrazloženje ne može biti prazno.');
            }
            $this->redirect('/activity/update/' . $model->id . '?tab=1');
        }


// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        if (isset($_POST['Activity'])) {
            $model->attributes = $_POST['Activity'];
            if ($model->save()) {
                $this->saveAttachments($model);
                Yii::app()->user->setFlash('success', Yii::t('app', 'Saved'));
                $this->redirect('/activity/update/' . $model->id . '?tab=0');
            }
        }

        $activity_attachments = new ActivityAttachment('search');
        $activity_attachments->unsetAttributes();
        $activity_attachments->activity_id = $model->id;

        $activity_orders = new ActivityOrder('search');
        $activity_orders->unsetAttributes();
        $activity_orders->activity_id = $model->id;

        if (count($model->activityPaletts) == 0 && $model->direction == 'in') {
            $model->generatePaletts();
            $model->refresh();
        }

        if (isset($_POST['AddPaletts'])) {
            $post = $_POST['AddPaletts'];
            $added = 0;
            if (isset($post['activity_order_id']) && $post['palett_count'] > 0) {
                $palett_count = (int)$post['palett_count'];
                $activity_order_id = (int)$post['activity_order_id'];


                for ($i = 0; $i < $palett_count; $i++) {
                    $activity_palett = new ActivityPalett;
                    $activity_palett->attributes = array(
                        'activity_id' => $model->id,
                        'activity_order_id' => $activity_order_id,
                        'sscc' => ActivityPalett::newSSCC(),

                    );
                    if ($activity_palett->save()) {
                        $added++;
                    }

                }

            }
            Yii::app()->user->setFlash('success', Yii::t('app', 'Added Paletts') . ': ' . $added);
            $this->redirect(array('update', 'id' => $model->id, 'tab' => 2));
        }

        $activity_paletts = new ActivityPalett('search');
        $activity_paletts->unsetAttributes();
        $activity_paletts->activity_id = $model->id;


        $this->render('update', array(
            'model' => $model,
            'activity_order' => $activity_order,
            'activity_orders' => $activity_orders,
            'activity_paletts' => $activity_paletts,
            'activity_attachments' => $activity_attachments,

        ));
    }

    public function saveAttachments($activity)
    {
        $attachments = CUploadedFile::getInstancesByName('ActivityAttachment[files]');


        if (isset($attachments) && count($attachments) > 0) {
            $folder = Yii::app()->basePath . '/../upload/activity/' . date('Ymd');

            if (!is_dir($folder)) {
                mkdir($folder);
            }
            $folder = $folder . '/' . $activity->id;
            if (!is_dir($folder)) {
                mkdir($folder);
            }

            $filepath = '/upload/activity/' . date('Ymd') . '/' . $activity->id;

            foreach ($attachments as $attachment => $file) {
                if ($file->saveAs($folder . '/' . $file->name)) {
                    $activity_attachment = new ActivityAttachment;
                    $activity_attachment->activity_id = $activity->id;
                    $activity_attachment->filename = $file->name;
                    $activity_attachment->filepath = $filepath . '/' . $file->name;
                    $activity_attachment->save();

                }
            }


        }
    }

    public function actionResDownloadAttachment($id)
    {
        $model = ActivityAttachment::model()->findByPk($id);
        $this->download($model->filepath);
    }

    public function actionAjaxDeleteAttachment($id)
    {

        if (Yii::app()->request->isPostRequest) {
            $activity_attachment = ActivityAttachment::model()->findByPk($id);;
            if ($activity_attachment) {

                $activity_attachment->delete();


            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
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
// we only allow deletion via POST request
            $this->loadModel($id)->delete();

// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionAjaxDeleteOrder($id)
    {
        if (Yii::app()->request->isPostRequest) {

            $activity_order = ActivityOrder::model()->findByPk($id);
            $activity_order->delete();

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
            $activity_palett->delete();

            if (!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model = new Activity('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Activity']))
            $model->attributes = $_GET['Activity'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionAjaxDeleteDetails($id)
    {
        if (Yii::app()->request->isPostRequest) {


            $model = ActivityDetails::model()->findByPk($id);
            if ($model) {
                $model->delete();
            }

            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionResSticker($id)
    {
        $activity_palett = ActivityPalett::model()->findByPk($id);
        if ($activity_palett === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        $activity_palett->printSSCC();
    }

    public function actionResStickers($id)
    {

        $model = $this->loadModel($id);

        if (count($model->activityPaletts) == 0) {
            $this->redirect(array('update', 'id' => $id, 'tab' => 2));
        }

        /*         * *************************************************************************** */
        $pdf = Yii::createComponent('application.extensions.tcpdf.ETcPdf', 'L', 'cm', 'A5', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor("WTC3");
        $pdf->SetTitle("SSCC");
        $pdf->SetSubject("SSCC");
        $pdf->SetKeywords('');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AliasNbPages();
        foreach ($model->activityPaletts as $activity_palett) {
            $pdf->AddPage();
            $pdf->SetMargins(1, 1, 1);
            $x = 2.5;
            $y = 4;

            $logo_path = Yii::getPathOfAlias("webroot") . '/themes/wtc3/img/logo.jpg';
            $pdf->Image($logo_path, 1, 1, 3, 0.6, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
            $barcode_path = Yii::getPathOfAlias("webroot") . '/barcodes/paletts/' . $activity_palett->sscc;
            if (!is_file($barcode_path)) {
                $activity_palett->createBarcode();
                $barcode_path = Yii::getPathOfAlias("webroot") . '/barcodes/paletts/' . $activity_palett->sscc;
            }

            $pdf->SetFont("freesans", "", 84);
            $pdf->MultiCell(8, 3, substr($activity_palett->sscc, -4), 0, 'C', 0, 0, 6, 1, true);

            $pdf->SetFont("freesans", "", 8);
            $pdf->MultiCell(15.7, 3, date('Ymd\THis'), 0, 'R', 0, 0, $x, $y + 0.2, true);
            $pdf->Image($barcode_path, $x, $y + 0.6, 16, 6, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
            $pdf->SetFont("freesans", "", 32);
            $pdf->MultiCell(16, 1, $activity_palett->sscc, 0, 'C', 0, 0, $x, $y + 6.6, true);
            $pdf->SetFont("freesans", "", 12);
            $pdf->MultiCell(16, 1, $activity_palett->activity->gate->title . " * " . $activity_palett->activityOrder->order_number, 0, 'C', 0, 0, $x, $y + 8, true);


        }
        $pdf->Output("SSCC_" . (is_array($model->orderNumber) ? implode('_', $model->orderNumber) : $model->orderNumber) . '_' . date('Ymd\THis') . ".pdf", "D");

    }

    public function actionResInboundIssues($id)
    {
        $model = $this->loadModel($id);
        $issues = $model->inboundIssues();
        $pdf = Yii::createComponent('application.extensions.tcpdf.ETcPdf', 'P', 'cm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor("WTC3");
        $pdf->SetTitle("Zapisnik o razlikama");
        $pdf->SetSubject("Zapisnik o razlikama");
        $pdf->SetKeywords('');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AliasNbPages();
        $pdf->addPage();
        $pdf->SetMargins(1, 1, 1);

        $logo_path = Yii::getPathOfAlias("webroot") . '/themes/wtc3/img/logo.jpg';
        $pdf->Image($logo_path, 1, 1, 5, 1, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
        $pdf->SetFont("freesans", "B", 8);
        $pdf->MultiCell(6, 0.4, "Schenker doo", 0, 'R', 0, 0, 14, 1, true);
        $pdf->SetFont("freesans", "", 8);
        $pdf->MultiCell(6, 0.4, "Logistik Center", 0, 'R', 0, 0, 14, 1.4, true);
        $pdf->MultiCell(6, 0.4, "Dositejeva 45", 0, 'R', 0, 0, 14, 1.8, true);
        $pdf->MultiCell(6, 0.4, "RS-22310 Šimanovci", 0, 'R', 0, 0, 14, 2.2, true);
        $pdf->SetFont("freesans", "B", 11);

        $pdf->MultiCell(19, 1, "ZAPISNIK O RAZLIKAMA", 0, 'C', 0, 0, 1, 3, true);
        $pdf->SetFont("freesans", "B", 9);
        $pdf->SetFillColor(235, 235, 235);
        $pdf->MultiCell(19, 0.5, 'Zapisnik o razlikama broj: DIFF-' . $model->id . '/' . date('Y', strtotime($model->truck_arrived_date)), 'LRBT', 'L', 1, 0, 1, 5, true);
        $pdf->SetFont("freesans", "", 8);
        $pdf->MultiCell(3, 0.5, 'Datum prijema', 'LRBT', 'C', 1, 0, 1, 5.5, true);
        $pdf->MultiCell(3, 0.5, 'Broj naloga', 'LRBT', 'C', 1, 0, 4, 5.5, true);
        $pdf->MultiCell(7, 0.5, $model->direction == 'in' ? 'Dobavljač' : 'Kupac', 'LRBT', 'C', 1, 0, 7, 5.5, true);
        $pdf->MultiCell(6, 0.5, 'Naziv klijenta', 'LRBT', 'C', 1, 0, 14, 5.5, true);

        $pdf->MultiCell(3, 1, date('d.m.Y', strtotime($model->truck_arrived_date)), 'LRBT', 'C', 1, 0, 1, 6, true);

        $order_numbers = "";
        $suppliers = "";
        $clients = "";
        foreach ($model->activityOrders as $activity_order) {
            $order_numbers .= $activity_order->order_number . "\n";
            $suppliers .= $activity_order->customerSupplier->title . "\n";
            $clients .= $activity_order->client->title . "\n";
        }

        $pdf->MultiCell(3, 1, $order_numbers, 'LRBT', 'C', 1, 0, 4, 6, true);
        $pdf->MultiCell(7, 1, $suppliers, 'LRBT', 'C', 1, 0, 7, 6, true);
        $pdf->MultiCell(6, 1, $clients, 'LRBT', 'C', 1, 0, 14, 6, true);

        $x = 1;
        $y = 7;
        $pdf->SetFont("freesans", "", 7);
        $pdf->MultiCell(2, 0.8, 'Broj artikla', 'LRBT', 'C', 0, 0, $x, $y, true);
        $pdf->MultiCell(9, 0.8, 'Opis artikla', 'LRBT', 'C', 0, 0, $x + 2, $y, true);
        $pdf->MultiCell(3, 0.8, 'Barkod artikla', 'LRBT', 'C', 0, 0, $x + 11, $y, true);

        $pdf->MultiCell(1.8, 0.8, 'Očekivana količina', 'LRBT', 'C', 0, 0, $x + 14, $y, true);
        $pdf->MultiCell(1.8, 0.8, 'Isporučena količina', 'LRBT', 'C', 0, 0, $x + 15.8, $y, true);
        $pdf->MultiCell(1.4, 0.8, 'Razlika', 'LRBT', 'C', 0, 0, $x + 17.6, $y, true);

        $y += 0.8;
        if ($issues) {
            foreach ($issues as $issue) {
                $product = Product::model()->findByPk($issue['product_id']);
                if ($product !== null) {
                    $pdf->MultiCell(2, 0.8, $product->external_product_number, 'LRBT', 'L', 0, 0, $x, $y, true);
                    $pdf->MultiCell(9, 0.8, $product->title, 'LRBT', 'L', 0, 0, $x + 2, $y, true);
                    $pdf->MultiCell(3, 0.8, $product->product_barcode, 'LRBT', 'L', 0, 0, $x + 11, $y, true);

                    $pdf->MultiCell(1.8, 0.8, number_format($issue['order'], 0, ',', '.'), 'LRBT', 'R', 0, 0, $x + 14, $y, true);
                    $pdf->MultiCell(1.8, 0.8, number_format($issue['activity'], 0, ',', '.'), 'LRBT', 'R', 0, 0, $x + 15.8, $y, true);
                    $pdf->MultiCell(1.4, 0.8, number_format($issue['activity'] - $issue['order'], 0, ',', '.'), 'LRBT', 'R', 0, 0, $x + 17.6, $y, true);


                    $y += 0.8;

                    if ($y >= 27) {
                        $pdf->addPage();
                        $y = 1;
                    }
                }
            }
        }
        $y += 2;

        $pdf->MultiCell(4, 1.5, 'Robu primio', 'B', 'C', 0, 0, $x, $y, true);
        $pdf->MultiCell(4, 1.5, 'Robu izdao', 'B', 'C', 0, 0, $x + 15, $y, true);

        $pdf->Output("Zapisnik_o_razlikama" . $model->id . '-' . $model->activityOrders[0]->order_number . '-' . date('Y') . ".pdf", "D");
    }

    public function actionResReceipt($id)
    {
        $model = $this->loadModel($id);
        $pdf = Yii::createComponent('application.extensions.tcpdf.ETcPdf', 'P', 'cm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor("WTC3");
        $pdf->SetTitle("Prijemnica");
        $pdf->SetSubject("Prijemnica");
        $pdf->SetKeywords('');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AliasNbPages();
        $pdf->addPage();
        $pdf->SetMargins(1, 1, 1);
        $x = 1;
        $y = 6;
        $logo_path = Yii::getPathOfAlias("webroot") . '/themes/wtc3/img/logo.jpg';
        $pdf->Image($logo_path, 1, 1, 5, 1, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
        $pdf->SetFont("freesans", "B", 8);
        $pdf->MultiCell(6, 0.4, "Schenker doo", 0, 'R', 0, 0, 14, 1, true);
        $pdf->SetFont("freesans", "", 8);
        $pdf->MultiCell(6, 0.4, "Logistik Center", 0, 'R', 0, 0, 14, 1.4, true);
        $pdf->MultiCell(6, 0.4, "Dositejeva 45", 0, 'R', 0, 0, 14, 1.8, true);
        $pdf->MultiCell(6, 0.4, "RS-22310 Šimanovci", 0, 'R', 0, 0, 14, 2.2, true);
        $pdf->SetFont("freesans", "B", 9);
        $pdf->SetFillColor(235, 235, 235);
        $pdf->MultiCell(19, 0.5, 'Prijemnica broj: ' . $model->id . '/' . date('Y', strtotime($model->truck_arrived_date)), 'LRBT', 'L', 1, 0, 1, 3, true);
        $pdf->SetFont("freesans", "", 8);
        $pdf->MultiCell(3, 0.5, 'Datum prijema', 'LRBT', 'C', 1, 0, 1, 3.5, true);
        $pdf->MultiCell(6, 0.5, 'Broj naloga', 'LRBT', 'C', 1, 0, 4, 3.5, true);
        $pdf->MultiCell(6, 0.5, 'Dobavljač', 'LRBT', 'C', 1, 0, 10, 3.5, true);
        $pdf->MultiCell(4, 0.5, 'Naziv klijenta', 'LRBT', 'C', 1, 0, 16, 3.5, true);

        $pdf->MultiCell(3, 2, date('d.m.Y', strtotime($model->truck_arrived_date)), 'LRBT', 'C', 1, 0, 1, 4, true);

        $order_numbers = "";
        $suppliers = "";
        $clients = "";
        foreach ($model->activityOrders as $activity_order) {
            $order_numbers .= $activity_order->order_number . ", ";
            $suppliers .= $activity_order->customerSupplier->title . ", ";
            $clients .= $activity_order->client->title . ", ";
        }
        $order_numbers = rtrim($order_numbers, ', ');
        $suppliers = rtrim($suppliers, ', ');
        $clients = rtrim($clients, ', ');

        $pdf->MultiCell(6, 2, $order_numbers, 'LRBT', 'C', 1, 0, 4, 4, true);
        $pdf->MultiCell(6, 2, $suppliers, 'LRBT', 'C', 1, 0, 10, 4, true);
        $pdf->MultiCell(4, 2, $clients, 'LRBT', 'C', 1, 0, 16, 4, true);

        $pdf->SetFont("freesans", "", 7);


        $pdf->MultiCell(2, 0.8, 'Broj artikla', 'LRBT', 'C', 0, 0, $x, $y, 1, false, false, true, 0.8, 'M');

        $pdf->MultiCell(9, 0.8, 'Opis artikla', 'LRBT', 'C', 0, 0, $x + 2, $y, 1, false, false, true, 0.8, 'M');

        $pdf->MultiCell(4, 0.8, 'Šarža', 'LRBT', 'C', 0, 0, $x + 11, $y, 1, false, false, true, 0.8, 'M');
        $pdf->MultiCell(2, 0.8, 'BBD', 'LRBT', 'C', 0, 0, $x + 15, $y, 1, false, false, true, 0.8, 'M');
        $pdf->MultiCell(2, 0.8, 'Količina', 'LRBT', 'C', 0, 0, $x + 17, $y, 1, false, false, true, 0.8, 'M');
        $y += 0.8;
        foreach ($model->activityPaletts as $palett) {
            foreach ($palett->hasProducts as $hasProduct) {
                $pdf->MultiCell(2, 0.8, $hasProduct->product->external_product_number, 'LRBT', 'L', 0, 0, $x, $y, true);
                $pdf->MultiCell(9, 0.8, $hasProduct->product->title, 'LRBT', 'L', 0, 0, $x + 2, $y, true);

                $pdf->MultiCell(4, 0.8, $hasProduct->batch, 'LRBT', 'C', 0, 0, $x + 11, $y, true);
                $pdf->MultiCell(2, 0.8, $hasProduct->expire_date, 'LRBT', 'C', 0, 0, $x + 15, $y, true);
                $pdf->MultiCell(2, 0.8, number_format($hasProduct->quantity, 0, ',', '.'), 'LRBT', 'R', 0, 0, $x + 17, $y, true);

                $y += 0.8;

                if ($y >= 27) {
                    $pdf->addPage();
                    $y = 1;
                }

            }
        }
        $y += 1;


        foreach ($model->activityOrders as $activity_order) {
            if ($activity_order->notes != null) {
                $pdf->SetFont("freesans", "B", 8);
                $pdf->MultiCell(3, 2, $activity_order->order_number . ':', '', 'R', 0, 0, $x, $y, 1);
                $pdf->SetFont("freesans", "", 8);
                $pdf->MultiCell(16, 2, $activity_order->notes, '', 'L', 0, 0, $x + 3, $y, 1);
                $y += 2;
            }
        }
        $y += 1;
        $pdf->SetFont("freesans", "", 8);
        $pdf->MultiCell(4, 1.5, 'Robu primio', 'B', 'C', 0, 0, $x, $y, true);
        $pdf->MultiCell(4, 1.5, 'Robu izdao', 'B', 'C', 0, 0, $x + 15, $y, true);

        $pdf->Output("Prijemnica_" . $model->id . '-' . $model->activityOrders[0]->order_number . '-' . date('Y') . ".pdf", "D");
    }

    public function actionResDeliveryNote($id)
    {
        $activity = $this->loadModel($id);
        $pdf = Yii::createComponent('application.extensions.tcpdf.ETcPdf', 'P', 'cm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor("WTC3");
        $pdf->SetTitle("Otpremnica");
        $pdf->SetSubject("Otpremnica");
        $pdf->SetKeywords('');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AliasNbPages();
        foreach ($activity->activityOrders as $model) {
            $pdf->addPage();
            $pdf->SetMargins(1, 1, 1);
            $pdf = $model->createDeliveryNotice($pdf);

        }
        $pdf->Output("Otpremnica_" . $model->id . '-' . $model->order_number . '-' . date('Y') . ".pdf", "D");
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'activity-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionResPicksExportToExcel($id)
    {
        $activity = $this->loadModel($id);

        $picks = new Pick('activitySearch');
        $picks->unsetAttributes();
        $picks->activity_id = $id;

        $model = $picks->activitySearch()->getData();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($activity->orderRequest ? $activity->orderRequest->load_list : $activity->id);


        $heading = array('R.Br.', 'Broj naloga', 'SLOC', 'SSCC', 'Šifra proizvoda', 'Naziv proizvoda', 'Barkod proizvoda', 'Zadato', 'Pikovano');

        $letters = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];

        $sheet->getRowDimension('1')->setRowHeight(30);

        foreach ($heading as $index => $title) {
            $sheet->getColumnDimension($letters[$index])->setAutoSize(true);
            $sheet->setCellValue($letters[$index] . '1', $title);
            $sheet->getStyle($letters[$index] . '1')->applyFromArray($styleArray);
        }
        $row = 2;

        foreach ($model as $data) {


            $i = 0;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $row - 1);


            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->activityOrder ? $data->activityOrder->order_number : '');

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->sloc_code);

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->sscc_source);

            $i++;
            $cell = $letters[$i] . $row;
            $spreadsheet->getActiveSheet()
                ->getCell($cell)
                ->setValueExplicit(
                    $data->product->internal_product_number,
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2
                );

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->product->title);

            $i++;
            $cell = $letters[$i] . $row;


            $spreadsheet->getActiveSheet()
                ->getCell($cell)
                ->setValueExplicit(
                    $data->product->product_barcode,
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2
                );

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->target);
            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->quantity);


            $row++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Pikovanje_' . $activity->id . '_' . date('Ymd\THi') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');

    }

}
