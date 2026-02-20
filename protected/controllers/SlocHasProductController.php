<?php
require Yii::getPathOfAlias('application') . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SlocHasProductController extends Controller
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
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = SlocHasProduct::model()->findByPk($id);
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
        $model = new SlocHasProduct;

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        if (isset($_POST['SlocHasProduct'])) {
            $model->attributes = $_POST['SlocHasProduct'];
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

        if (isset($_POST['SlocHasProduct'])) {
            $model->attributes = $_POST['SlocHasProduct'];
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
        $model = new SlocHasProduct('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['SlocHasProduct'])) {
            $model->attributes = $_GET['SlocHasProduct'];
        }

        $sloc_has_product_log = new SlocHasProductLog;

        if (isset($_POST['SlocHasProductLog'])) {
            $sloc_has_product = SlocHasProduct::model()->findByPk($_POST['SlocHasProductLog']['sloc_has_product_id']);

            $attributes = $_POST['SlocHasProductLog'];

            $content = $sloc_has_product->realQuantity;
            $difference = $attributes['quantity'] - $content;




            $sloc_has_product_log->attributes = array(
                'sloc_has_product_id' => $sloc_has_product->id,
                'sloc_id' => $sloc_has_product->sloc_id,
                'sloc_code' => $sloc_has_product->sloc_code,
                'product_id' => $sloc_has_product->product_id,
                'product_barcode' => $sloc_has_product->product_barcode,
                'quantity' => $difference,
                'reason' => $attributes['reason'],
            );

            if ($sloc_has_product_log->save()) {
                echo json_encode($sloc_has_product_log->attributes);
            } else {
                echo CActiveForm::validate($sloc_has_product_log);
            }

            Yii::app()->end();


        }


        $this->render('index', array(
            'model' => $model,
            'sloc_has_product_log' => $sloc_has_product_log
        ));
    }

    public function actionAjaxGetSlocHasProduct($id)
    {
        $model = SlocHasProduct::model()->findByPk($id);
        if ($model === null) {
            echo 'ERROR';
        }
        $attributes = $model->attributes;
        $attributes['product_info'] = $model->product->internal_product_number . ' - ' . $model->product->title;
        $attributes['quantity'] = $model->realQuantity;


        echo json_encode($attributes);
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'sloc-has-product-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionAjaxGetSlocHasProductHistory($id)
    {
        $sloc_has_product = SlocHasProduct::model()->findByPk($id);
        if ($sloc_has_product === null) {
            echo 'ERROR';
            Yii::app()->end();
        }

        $user = User::model()->findByPk($sloc_has_product->created_user_id);
        $history = array();
        $history[] = array(
            'datetime' => $sloc_has_product->created_dt,
            'user' => $user ? $user->name : "",
            'quantity' => $sloc_has_product->quantity,
            'reason' => Yii::t('app','Dopuna'),
        );


/*
        $condition = '(pick_type="move" OR pick_type="product") AND product_id='.$activity_palett_has_product->product_id.' AND activity_palett_id='.$activity_palett_has_product->activity_palett_id;

        $picks = Pick::model()->findAll(array('condition'=>$condition));

        foreach ($picks as $pick) {
            $user = User::model()->findByPk($pick->created_user_id);
            $reason = $pick->pick_type == 'move' ? Yii::t('app','Redistribution') . ' => ' . $pick->sscc_destination : Yii::t('app','Picked') . ($pick->activityOrder ? ' - ' . $pick->activityOrder->order_number : '');
            $history[] = array(
                'datetime' => $pick->created_dt,
                'user' => $user ? $user->name : "",
                'quantity' => -$pick->quantity,
                'packages' => -$pick->packages,
                'units' => -$pick->units,
                'reason' => $reason
            );
        }
*/

        $condition = 'status=1 AND product_id=' . $sloc_has_product->product_id . ' AND sloc_id=' . $sloc_has_product->sloc_id;

        $picks_web = PickWeb::model()->findAll(array('condition' => $condition));


        foreach ($picks_web as $pick_web) {
            $user = User::model()->findByPk($pick_web->created_user_id);


            $history[] = array(
                'datetime' => $pick_web->created_dt,
                'user' => $user ? $user->name : "",
                'quantity' => -$pick_web->quantity,

                'reason' => $pick_web->webOrder ? 'Pikovano ' . $pick_web->webOrder->order_number : 'Povrat',
            );
        }
        $condition = 'quantity>0 AND sscc_source IS NULL AND product_id=' . $sloc_has_product->product_id . ' AND sloc_id=' . $sloc_has_product->sloc_id;

        $picks = Pick::model()->findAll(array('condition' => $condition));
        foreach ($picks as $pick) {
            $user = User::model()->findByPk($pick->created_user_id);


            $history[] = array(
                'datetime' => $pick->created_dt,
                'user' => $user ? $user->name : "",
                'quantity' => -$pick->quantity,

                'reason' => $pick->activityOrder ? 'Pikovano ' . $pick->activityOrder->order_number : 'Povrat',
            );
        }
        $logs = SlocHasProductLog::model()->findAllByAttributes(
            array(
                'sloc_id' => $sloc_has_product->sloc_id,
                'product_id' => $sloc_has_product->product_id
            )
        );

        foreach ($logs as $log) {
            $user = User::model()->findByPk($log->created_user_id);
            $history[] = array(
                'datetime' => $log->created_dt,
                'user' => $user ? $user->name : "",
                'quantity' => $log->quantity,
                'reason' => $log->reason,
            );
        }

        usort($history, function($a, $b) {
            return $a['datetime'] <=> $b['datetime'];
        });

        $sort = array();
        $model = new CArrayDataProvider($history, array(
            'id' => 'history',
            'keyField' => 'datetime',
            'sort' => $sort,
            'pagination' => array(
                'pageSize' => 999,
                'pageVar' => 'page',
            ),
        ));

        echo $this->widget('ext.groupgridview.BootGroupGridView', array(
            'id' => 'history-grid',
            'dataProvider' => $model,

            'summaryText' => false,

            'filter' => null,



            'columns' => array(

                array(
                    'header' => 'R.Br.',
                    'value' => '($row + ($this->grid->dataProvider->pagination->currentPage  * $this->grid->dataProvider->pagination->pageSize) +1)."."',
                    'htmlOptions' => array(
                        'class' => 'text-right'
                    )
                ),
                array(
                    'header' => Yii::t('app','Date And Time'),
                    'name' => 'datetime',
                ),
                array(
                    'header' => Yii::t('app','User'),
                    'name' => 'user',
                ),

                array(
                    'name' => 'quantity',
                    'header' => Yii::t('app','Quantity'),
                    'htmlOptions'=>array('class'=>'text-right'),
                    'headerHtmlOptions'=>array('class'=>'text-right'),
                ),
                array(
                    'header' => Yii::t('app','Remains'),
                    'class' => 'TotalColumn',
                    'attribute' => 'quantity',
                    'htmlOptions'=>array('class'=>'text-right'),
                    'headerHtmlOptions'=>array('class'=>'text-right'),
                ),
                array(
                    'name' => 'reason',
                    'header' => Yii::t('app','Reason'),
                ),

            ),
        ),true);

    }

    public function actionResExportExcel()
    {
        $model = SlocHasProduct::model()->findAll();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $heading = array('SLOC kod',  'Šifra proizvoda', 'Naziv proizvoda','Barkod proizvoda',  'Količina');

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
            $sheet->setCellValue($cell, $data->sloc_code);


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
            $sheet->setCellValue($cell, $data->realQuantity);





            $row++;
        }


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="WEB.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');

    }


}
