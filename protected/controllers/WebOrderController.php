<?php
require Yii::getPathOfAlias('application') . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class WebOrderController extends Controller
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
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new WebOrder;

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        if (isset($_POST['WebOrder'])) {
            $model->attributes = $_POST['WebOrder'];
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

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        if (isset($_POST['WebOrder'])) {
            $model->attributes = $_POST['WebOrder'];
            if ($model->save()) {
                Yii::app()->user->setFlash('success', Yii::t('app', 'Saved'));
            }
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

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model = new WebOrder('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['WebOrder']))
            $model->attributes = $_GET['WebOrder'];

        $this->render('index', array(
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
        $model = WebOrder::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'web-order-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionResClose($id)
    {
        $model = $this->loadModel($id);
        $model->status = 1;
        if ($model->save()) {
            Yii::app()->user->setFlash('success', 'Nalog ' . $model->order_number . ' zatvoren.');

        } else {
            Yii::app()->user->setFlash('success', 'Greška prilikom zatvaranja naloga ' . $model->order_number);
        }
        $this->redirect(array('index'));

    }

    public function actionResExportExcel()
    {
        $web_orders = WebOrder::model()->findAll();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $heading = array('Klijent', 'Broj naloga', 'Kupac', 'Load Lista', 'Proizvodi', 'Broj proizvoda', 'Način isporuke', 'Kreiran', 'Status');

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
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],


        ];
        $styleTop = [

            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
            ],


        ];
        foreach ($web_orders as $web_order) {

            $i = 0;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $web_order->client->title);
            $sheet->getStyle($cell)->applyFromArray($styleCenter);

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $web_order->order_number);
            $sheet->getStyle($cell)->applyFromArray($styleCenter);

            $customer_data = json_decode($web_order->customer_data, true);
            $customer = '';


            if (is_array($customer_data)) {
                foreach ($customer_data as $k => $v) {
                    if (is_array($v)) {
                        $v = implode(',', $v);
                    }
                    $customer .= substr($k,7) . ': ' . $v . "\n";
                }
            }
            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $customer);
            $sheet->getStyle($cell)->getAlignment()->setWrapText(true);
            $sheet->getStyle($cell)->applyFromArray($styleTop);

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $web_order->load_list);
            $sheet->getStyle($cell)->applyFromArray($styleCenter);

            $products = '';
            $number_of_products=0;
            foreach ($web_order->webOrderProducts as $web_order_product) {
                $products .= $web_order_product->product->product_barcode . ' * ' . $web_order_product->product->internal_product_number . ' * ' . $web_order_product->product->title . ': ' . $web_order_product->quantity . "\n";
                $number_of_products += $web_order_product->quantity;
            }

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $products);
            $sheet->getStyle($cell)->getAlignment()->setWrapText(true);
            $sheet->getStyle($cell)->applyFromArray($styleTop);

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $number_of_products);
            $sheet->getStyle($cell)->applyFromArray($styleCenter);

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $web_order->delivery_type);
            $sheet->getStyle($cell)->applyFromArray($styleCenter);

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $web_order->created_dt);
            $sheet->getStyle($cell)->applyFromArray($styleCenter);

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $web_order->status == 0 ? 'PRIMLJEN' : 'OBRAĐEN');
            $sheet->getStyle($cell)->applyFromArray($styleCenter);


            /*
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
            */
            $row++;

        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="WebNalozi_'.date('Ymd\THi').'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');

    }
}
