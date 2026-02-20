<?php

require Yii::getPathOfAlias('application') . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class OrderController extends Controller
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


    public function actionAjaxGetTimeSlot($id)
    {
        $model = $this->loadModel($id);
        $time_slot = TimeSlot::model()->findByAttributes(array('order_request_id' => $model->id));

        if ($time_slot) {
            $result = array(
                'result' => 'link',
                'content' => '/timeSlot/' . $time_slot->id,
            );
            echo json_encode($result);
            return;
        }

        $time_slots = TimeSlot::model()->findAll(array('condition' => 'gate_id IS NOT NULL AND order_request_id IS NULL AND activity_type_id = ' . $model->activity_type_id . ' AND location_id = ' . $model->location_id));
        // var_dump($time_slots,$model->activity_type_id,$model->location_id);die();
        $candidate_ids = array();
        foreach ($time_slots as $time_slot) {
            if (count($time_slot->timeSlotDetails) != count($model->orderClients)) {

                continue;
            }
            if ($time_slot->totalPaletts != $model->totalPaletts) {

                continue;
            }
            foreach ($time_slot->timeSlotDetails as $time_slot_detail) {
                $order_client = OrderClient::model()->findByAttributes(array('order_request_id' => $model->id, 'client_id' => $time_slot_detail->client_id));
                if (!$order_client) {

                    continue;
                }
            }

            $candidate_ids[] = $time_slot->id;

        }

        if (!empty($candidate_ids)) {
            $candidates = new TimeSlot('search');
            $candidates->filtered = $candidate_ids;

            $content = '<div class="col-md-12 text-right"><button class="btn btn-primary" onclick="showCreate(' . $model->id . ',' . $model->activity_type_id . ');">' . Yii::t('app', 'Create') . '</button></div><hr>';

            $content .= $this->widget('booster.widgets.TbGridView', array(
                'id' => 'time-slot-grid',
                'dataProvider' => $candidates->search(),
                'summaryText' => false,
                'filter' => null,


                'columns' => array(

                    array(
                        'name' => 'defined_date',
                        'htmlOptions' => array('class' => 'col-md-1')

                    ),

                    array(
                        'name' => 'section_id',
                        'value' => '$data->section ? $data->section->title : ""',
                    ),
                    array(
                        'name' => 'gate_id',
                        'value' => '$data->gate ? $data->gate->title : ""',
                    ),

                    array(
                        'name' => 'start_time',
                        'value' => '($data->start_time) ? date("H:i",strtotime($data->start_time)) : ""',
                        'htmlOptions' => array('class' => 'text-center col-md-1'),
                    ),
                    array(
                        'name' => 'end_time',
                        'value' => '($data->end_time) ? date("H:i",strtotime($data->end_time)) : ""',
                        'htmlOptions' => array('class' => 'text-center col-md-1'),
                    ),
                    array(
                        'name' => 'truck_type_id',
                        'value' => '$data->truckType ? $data->truckType->title : ""',

                    ),

                    array(
                        'name' => 'license_plate',

                        'htmlOptions' => array('class' => 'col-md-1'),
                    ),

                    array(
                        'htmlOptions' => array('nowrap' => 'nowrap'),
                        'template' => '{select}',
                        'class' => 'booster.widgets.TbButtonColumn',
                        'buttons' => array(
                            'select' => array(
                                'label' => '<i class="glyphicon glyphicon-ok"></i>',
                                'url' => function ($data) use ($model) {
                                    return '/order/' . $model->id . '?time_slot_id=' . $data->id;
                                },
                                'options' => array(
                                    'class' => 'btn btn-xs view',
                                    'title' => 'select',

                                )
                            ),

                        ),
                    ),
                ),
            ), true);

            echo json_encode(
                array(
                    'result' => 'select',
                    'content' => $content,
                )
            );
            return;
        }
        echo json_encode(array(
            'result' => 'create',
            'order' => $model->attributes
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = OrderRequest::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)

    {
        if (isset($_GET['time_slot_id'])) {
            $time_slot = TimeSlot::model()->findByPk($_GET['time_slot_id']);
            if ($time_slot) {
                $time_slot->order_request_id = $id;
                if ($time_slot->save()) {
                    Yii::app()->user->setFlash('success', Yii::t('app', 'Time Slot Selected'));
                    $this->redirect(array('view', 'id' => $id));
                }
            }
        }

        $model = $this->loadModel($id);

        $order_products = new OrderProduct('perOrder');
        $order_products->unsetAttributes();
        $order_products->order_request_id = $model->id;


        $this->render('view', array(
            'model' => $model,
            'order_products' => $order_products,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new OrderRequest;

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        if (isset($_POST['OrderRequest'])) {
            $model->attributes = $_POST['OrderRequest'];
            if ($model->save()) {
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

        $order_client = new OrderClient;

        if (isset($_POST['OrderClient'])) {
            $order_client->attributes = $_POST['OrderClient'];
            $order_client->order_request_id = $model->id;
            if ($order_client->save()) {
                echo json_encode($order_client->attributes);
            } else {
                echo CActiveForm::validate($order_client);
            }
            Yii::app()->end();
        }

        $time_slot = new TimeSlot;

        if (isset($_POST['TimeSlot'])) {
            $time_slot->attributes = $_POST['TimeSlot'];

            if ($time_slot->save()) {


                if ($time_slot->order) {
                    foreach ($time_slot->order->orderClients as $order_client) {
                        $time_slot_detail = new TimeSlotDetail;
                        $time_slot_detail->attributes = array(
                            'time_slot_id' => $time_slot->id,
                            'order_client_id' => $order_client->id,
                            'client_id' => $order_client->client_id,
                            'paletts' => $order_client->totalPaletts,
                        );
                        $time_slot_detail->save();


                    }
                }


                echo json_encode($time_slot->attributes);
            } else {
                echo CActiveForm::validate($time_slot);
            }
            Yii::app()->end();

        }


// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        if (isset($_POST['OrderRequest'])) {
            $model->attributes = $_POST['OrderRequest'];
            if ($model->save()) {
                Yii::app()->user->setFlash('success', Yii::t('app', 'Saved'));
                $this->redirect('/order/update/' . $model->id . '?tab=0');
            }
        }

        $order_clients = new OrderClient('search');
        $order_clients->unsetAttributes();
        $order_clients->order_request_id = $model->id;


        $this->render('update', array(
            'model' => $model,
            'order_client' => $order_client,
            'order_clients' => $order_clients,
            'time_slot' => $time_slot
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

    public function actionAjaxDeleteClient($id)
    {
        if (Yii::app()->request->isPostRequest) {

            $order_client = OrderClient::model()->findByPk($id);
            $order_client->delete();

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
        $model = new OrderRequest('search');
        $model->unsetAttributes();  // clear any default values


        if (isset($_GET['OrderRequest'])) {

            $model->attributes = $_GET['OrderRequest'];

            $model->saveSearchValues();
        } else {
            $model->readSearchValues();
        }



        $array = $model->search()->getData();
        $quantity = 0;
        foreach ($array as $data) {
            foreach ($data->orderClients as $order_client) {

                foreach ($order_client->orderProducts as $order_product) {
                    $quantity += $order_product->quantity;
                }
            }
        }
        $total = $quantity;

        $quantity = 0;
        foreach ($array as $data) {
            if ($data->activity) {

                if ($data->activity->isReady() || $data->activity->truck_dispatch_datetime != NULL) {

                    foreach ($data->activity->activityPaletts as $activity_palett) {
                        foreach ($activity_palett->hasProducts as $hasProduct) {
                            $quantity += $hasProduct->quantity;
                        }
                    }
                }

            }
        }
        $completed = $quantity;

        if (isset($_GET['excel'])) {
           $model->isExcel = 999999;
           $model->status_filter = false;
            $this->ExportExcel($model->search()->getData());
            Yii::app()->end();
        }

        if (isset($_GET['filter'])) {
            // $model->resetSearchValues();
            $model->status_filter = $_GET['filter'];
        }

        $time_slot = new TimeSlot;

        if (isset($_POST['TimeSlot'])) {
            $time_slot->attributes = $_POST['TimeSlot'];


            if ($time_slot->save()) {

                if ($time_slot->order) {
                    foreach ($time_slot->order->orderClients as $order_client) {
                        $time_slot_detail = new TimeSlotDetail;
                        $time_slot_detail->attributes = array(
                            'time_slot_id' => $time_slot->id,
                            'order_client_id' => $order_client->id,
                            'client_id' => $order_client->client_id,
                            'paletts' => $order_client->totalPaletts,
                        );
                        $time_slot_detail->save();

                    }


                }


                echo json_encode($time_slot->attributes);
            } else {
                echo CActiveForm::validate($time_slot);
            }
            Yii::app()->end();


        }

        $this->render('index', array(
            'model' => $model,
            'time_slot' => $time_slot,
            'total' => $total,
            'completed' => $completed,

        ));
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'order-request-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionResPrint($id)
    {
        $model = $this->loadModel($id);
        $pdf = Yii::createComponent('application.extensions.tcpdf.ETcPdf', 'P', 'cm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor("WTC3");
        $pdf->SetTitle("Nalog");
        $pdf->SetSubject("Nalog");
        $pdf->SetKeywords('');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AliasNbPages();
        $pdf->addPage();
        $pdf->SetMargins(1, 1, 1);
        $x = 1;
        $y = 3.5;
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
        $pdf->MultiCell(9, 0.5, 'Load lista: ' . $model->load_list, 'LRBT', 'L', 1, 0, 1, 3, true);
        $pdf->MultiCell(10, 0.5, 'Datum prijema: ' . date('d.m.Y', strtotime($model->created_dt)), 'LRBT', 'R', 1, 0, 10, 3, true);
        $pdf->SetFont("freesans", "", 8);


        foreach ($model->orderClients as $order_client) {
            $pdf->SetFont("freesans", "B", 7);
            $pdf->MultiCell(7, 0.8, 'Broj naloga: ' . $order_client->order_number, 'LRBT', 'C', 0, 0, $x, $y, true, 0, false, true, 0.8, 'M', true);

            $pdf->MultiCell(6, 0.8, 'Kupac / Dobavljač:' . $order_client->customerSupplier->title, 'LRBT', 'L', 0, 0, $x + 7, $y, true, 0, false, true, 0.8, 'M', true);
            $pdf->MultiCell(6, 0.8, 'Klijent: ' . $order_client->client->title, 'LRBT', 'L', 0, 0, $x + 13, $y, true, 0, false, true, 0.8, 'M', true);
            $pdf->SetFont("freesans", "", 7);
            $y += 0.8;
            $pdf->MultiCell(4, 0.8, 'Broj artikla', 'LRBT', 'C', 0, 0, $x, $y, true, 0, false, true, 0.8, 'M', true);
            $pdf->MultiCell(9, 0.8, 'Opis artikla', 'LRBT', 'C', 0, 0, $x + 4, $y, true, 0, false, true, 0.8, 'M', true);

            $pdf->MultiCell(4, 0.8, 'Barkod', 'LRBT', 'C', 0, 0, $x + 13, $y, true, 0, false, true, 0.8, 'M', true);

            $pdf->MultiCell(2, 0.8, 'Količina', 'LRBT', 'C', 0, 0, $x + 17, $y, true, 0, false, true, 0.8, 'M', true);
            $y += 0.8;
            foreach ($order_client->orderProducts as $order_product) {
                $pdf->MultiCell(4, 0.6, $order_product->product->external_product_number, 'LRBT', 'L', 0, 0, $x, $y, true);
                $pdf->MultiCell(9, 0.6, $order_product->product->title, 'LRBT', 'L', 0, 0, $x + 4, $y, true);

                $pdf->MultiCell(4, 0.6, $order_product->product->product_barcode, 'LRBT', 'C', 0, 0, $x + 13, $y, true);

                $pdf->MultiCell(2, 0.6, number_format($order_product->quantity, 0, ',', '.'), 'LRBT', 'R', 0, 0, $x + 17, $y, true);

                $y += 0.6;

                if ($y >= 27) {
                    $pdf->addPage();
                    $y = 1;
                }

            }
        }


        $pdf->Output("Nalog_" . $model->load_list . '-' . date('Y') . ".pdf", "D");
    }

    public function actionResLabels($id)
    {
        $model = $this->loadModel($id);
        if ($model->activity == null) {
            throw new CHttpException('404', 'Aktivnost ne postoji.');
        }
        $activity = $model->activity;
        $pdf = Yii::createComponent('application.extensions.tcpdf.ETcPdf', 'L', 'cm', 'A5', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor("WTC3");
        $pdf->SetTitle("Oznake");
        $pdf->SetSubject("Oznake");
        $pdf->SetKeywords('');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AliasNbPages();
        foreach ($activity->activityOrders as $activity_order) {

            $pdf = $activity_order->createLabels($pdf);

        }
        $pdf->Output("Oznake_" . $model->id . '-' . $model->load_list . '-' . date('Y') . ".pdf", "D");
    }

    public function ExportExcel($orders)
    {
        // $orders = OrderRequest::model()->findAll();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $heading = array('Klijent', 'Broj naloga', 'Kupac / Dobavljač', 'Tip aktivnosti', 'Lokacija', 'Load Lista', 'Način isporuke', 'Kreiran', 'Traženo komada', 'Završeno', 'Isporučeno komada', 'Isporučeno', 'Status');

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
        $styleCenter = [

            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],


        ];

        $load_list = $orders[0]->load_list;

        $total_products = 0;
        $completed_products = 0;

        foreach ($orders as $order) {

            foreach ($order->orderClients as $order_client) {
                $i = 0;
                $cell = $letters[$i] . $row;
                $sheet->setCellValue($cell, $order_client->client->title);

                $i++;
                $cell = $letters[$i] . $row;
                $sheet->setCellValue($cell, $order_client->order_number);

                $i++;
                $cell = $letters[$i] . $row;
                $sheet->setCellValue($cell, $order_client->customerSupplier->title);

                $i++;
                $cell = $letters[$i] . $row;
                $sheet->setCellValue($cell, $order_client->orderRequest->direction == 'in' ? 'Inbound' : 'Outbound');

                $i++;
                $cell = $letters[$i] . $row;
                $sheet->setCellValue($cell, $order_client->orderRequest->location->title);

                $i++;
                $cell = $letters[$i] . $row;
                $sheet->setCellValue($cell, $order_client->orderRequest->load_list);


                $i++;
                $cell = $letters[$i] . $row;
                $sheet->setCellValue($cell, $order_client->delivery_type);

                $i++;
                $cell = $letters[$i] . $row;
                $sheet->setCellValue($cell, $order_client->created_dt);

                /** TRAZENA KOLICINA */
                $number_of_products = 0;

                foreach ($order_client->orderProducts as $order_product) {
                    $number_of_products += $order_product->quantity;
                }

                $total_products += $number_of_products;
                $i++;
                $cell = $letters[$i] . $row;
                $sheet->setCellValue($cell, $number_of_products);


                /** ZAVRSENO DATUM */

                $activity_order = ActivityOrder::model()->findByAttributes(array('order_client_id'=>$order_client->id));
                if ($activity_order && $activity_order->status == 1) {
                    $finished = $activity_order->updated_dt;
                } else {
                    $finished = '';
                }

                $i++;
                $cell = $letters[$i] . $row;
                $sheet->setCellValue($cell, $finished);

                /** ISPORUCENA KOLICINA */
                $number_of_products = 0;
                if ($order_client->orderRequest->activity) {

                    if ($order_client->orderRequest->activity->isReady() || $order_client->orderRequest->activity->truck_dispatch_datetime != NULL) {

                        $number_of_products = 0;

                        $activity_order = ActivityOrder::model()->findByAttributes(array('order_client_id' => $order_client->id));
                        if ($activity_order) {
                            foreach ($activity_order->activityPaletts as $activity_palett) {
                                foreach ($activity_palett->hasProducts as $activity_palett_has_product) {
                                    $number_of_products += $activity_palett_has_product->quantity;
                                    $completed_products += $activity_palett_has_product->quantity;
                                }
                            }
                        }


                    }

                }

                $i++;
                $cell = $letters[$i] . $row;
                $sheet->setCellValue($cell, $number_of_products > 0 ? $number_of_products : '');


                $i++;
                $cell = $letters[$i] . $row;
                $sheet->setCellValue($cell, $order_client->orderRequest->activity ? $order_client->orderRequest->activity->truck_dispatch_datetime : "");


                if ($order_client->orderRequest->activity) {
                    if ($order_client->orderRequest->activity->isReady()) {
                        $status = 'SPREMAN';
                    } else if ($order_client->orderRequest->activity->truck_dispatch_datetime != NULL) {
                        $status = 'ZAVRŠEN';
                    } else {
                        $status = 'U OBRADI';
                    }
                } else {
                    $status = 'PRIMLJEN';
                }
                $i++;
                $cell = $letters[$i] . $row;
                $sheet->setCellValue($cell, $status);

                $row++;
            }
        }
        $i=8;
        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, $total_products);
        $i=10;
        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, $completed_products);


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Nalozi_' . date('Ymd\THi') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');

    }
}
