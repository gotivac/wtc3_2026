<?php

require Yii::getPathOfAlias('application') . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SlocHasActivityPalettController extends Controller
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
            array(
                'COutputCache',
                'duration' => 100,
                'varyByParam' => array('id'),
            ),
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
        $activity_palett_has_products = new ActivityPalettHasProduct('search');
        $activity_palett_has_products->unsetAttributes();
        $activity_palett_has_products->activity_palett_id = $model->activity_palett_id;
        $this->render('view', array(
            'model' => $model,
            'activity_palett_has_products' => $activity_palett_has_products
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = SlocHasActivityPalett::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function actionResGateIn()
    {
        $model = new ActivityPalett('gateIn');
        $model->unsetAttributes();
        if (isset($_GET['ActivityPalett'])) {
            $model->attributes = $_GET['ActivityPalett'];

            if ($_GET['product_id'] != '') {
                $product = Product::model()->findByPk($_GET['product_id']);
                if ($product === null) {
                    throw new ChttpException('404', 'Product not found.');
                }

                if (count($product->activityPalettIds) > 0) {
                    $model->ids_containing_product = $product->activityPalettIds;
                } else {
                    $model->ids_containing_product = array(0);
                }
            }
        }

        $model->location = $this->user->location;

        $this->render('unlocated_paletts', array(
            'model' => $model,
        ));

    }

    public function actionResGateOut()
    {
        $model = new ActivityPalett('gateOut');
        $model->unsetAttributes();
        if (isset($_GET['ActivityPalett'])) {
            $model->attributes = $_GET['ActivityPalett'];

            if ($_GET['product_id'] != '') {
                $product = Product::model()->findByPk($_GET['product_id']);
                if ($product === null) {
                    throw new ChttpException('404', 'Product not found.');
                }
                if (count($product->activityPalettIds) > 0) {
                    $model->ids_containing_product = $product->activityPalettIds;
                } else {
                    $model->ids_containing_product = array(0);
                }
            }
        }
        $model->location = $this->user->location;

        $this->render('picked_paletts', array(
            'model' => $model,
        ));

    }

    public function actionResEmptySSCC()
    {
        //var_dump($this->user->attributes);die();
        if ($this->user->roles != 'superadministrator') {
            throw new CHttpException(403, 'Pristup zabranjen.');
        }


        // $activity_paletts = ActivityPalett::model()->findAll(array('condition'=>'id IN (SELECT activity_palett_id FROM sloc_has_activity_palett) AND sscc NOT IN (SELECT sscc_destination FROM pick WHERE sscc_destination IS NOT NULL AND pick_type<>"move")'));


        // $sql = 'SELECT activity_palett.id FROM activity_palett JOIN activity ON activity_palett_id.activity_id = activity.id WHERE activity_palett.id IN (SELECT activity_palett_id FROM sloc_has_activity_palett) AND activity.direction="in"';

        $activity_paletts = ActivityPalett::model()->findAll(array('condition' => 'id IN (SELECT activity_palett.id FROM activity_palett JOIN activity ON activity_palett.activity_id = activity.id WHERE activity_palett.id IN (SELECT activity_palett_id FROM sloc_has_activity_palett) AND activity.direction="in")'));
        $empty = array();
        foreach ($activity_paletts as $activity_palett) {
            if ($activity_palett->getTotalRealQuantity() <= 0 && !$activity_palett->isLoaded()) {

                //   $activity_palett->delete();
                $empty[] = $activity_palett;
            }
        }

        $sort = array();
        $model = new CArrayDataProvider($empty, array(
            'id' => 'empty',
            'keyField' => 'id',
            'sort' => $sort,
            'pagination' => array(
                'pageSize' => 100,
                'pageVar' => 'page',
            ),
        ));

        $this->render('empty_sscc', array('model' => $model));

    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new SlocHasActivityPalett;

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        if (isset($_POST['SlocHasActivityPalett'])) {
            $model->attributes = $_POST['SlocHasActivityPalett'];
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

        if (isset($_POST['SlocHasActivityPalett'])) {
            $model->attributes = $_POST['SlocHasActivityPalett'];
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
        $model = new SlocHasActivityPalett('search');

        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['SlocHasActivityPalett'])) {
            $model->attributes = $_GET['SlocHasActivityPalett'];

            /*
                        if ($_GET['product_id'] != '') {

                            $product = Product::model()->findByPk($_GET['product_id']);
                            if ($product === null) {
                                throw new ChttpException('404', 'Product not found.');
                            }

                            $activity_palett_ids = $product->activityPalettIds;
                        }
            */
            if ($_GET['product_barcode'] != '') {

                $product = Product::model()->find(array('condition' => 'product_barcode LIKE "%' . $_GET['product_barcode'] . '%"'));
                if ($product === null) {
                    $activity_palett_ids = array();
                } else if (isset($activity_palett_ids)) {
                    $activity_palett_ids = array_intersect($activity_palett_ids, $product->activityPalettIds);
                } else {
                    $activity_palett_ids = $product->activityPalettIds;
                }

            }
            if ($_GET['order_number'] != '') {
                $activity_order = ActivityOrder::model()->find(array('condition' => 'order_number LIKE "%' . $_GET['order_number'] . '%"'));

                if ($activity_order === null) {
                    $activity_palett_ids = array();
                } else if (isset($activity_palett_ids)) {
                    $activity_palett_ids = array_intersect($activity_palett_ids, (array)$activity_order->getActivityPalettIds());
                } else {
                    $activity_palett_ids = (array)$activity_order->getActivityPalettIds();
                }
            }

            if ($_GET['status'] != '') {
                if ($_GET['status'] == 'touched') {
                    $picked_ids = Yii::app()->db->createCommand('SELECT DISTINCT activity_palett_id FROM pick WHERE status=0 AND pick_type="product"')->queryColumn();
                    $palett_ids = Yii::app()->db->createCommand('SELECT DISTINCT activity_palett_id FROM pick WHERE status=0 AND pick_type="palett"')->queryColumn();
                    $picked_ids = array_diff($picked_ids, $palett_ids);
                }
                if ($_GET['status'] == 'picked') {
                    $picked_ids = Yii::app()->db->createCommand('SELECT DISTINCT activity_palett_id FROM pick WHERE status=0 AND pick_type="palett"')->queryColumn();
                }

                if (empty($picked_ids)) {
                    $activity_palett_ids = array();
                } else if (isset($activity_palett_ids)) {
                    $activity_palett_ids = array_intersect($activity_palett_ids, $picked_ids);
                } else {
                    $activity_palett_ids = $picked_ids;
                }
            }

            if (isset($activity_palett_ids)) {
                $model->activity_palett_ids = $activity_palett_ids;
            }

            if (isset($_GET['excel'])) {
                $model->isExcel = 999999;
                $this->ExportExcel($model->search()->getData());
                Yii::app()->end();
            }


        }

        $activity_palett_has_product_log = new ActivityPalettHasProductLog;

        if (isset($_POST['ActivityPalettHasProductLog'])) {
            $activity_palett_has_product = ActivityPalettHasProduct::model()->findByPk($_POST['ActivityPalettHasProductLog']['activity_palett_has_product_id']);
            $attributes = $_POST['ActivityPalettHasProductLog'];

            $content = $activity_palett_has_product->getContent();
            $difference = array(
                'quantity' => $attributes['quantity'] - $content['quantity'],
                'packages' => $attributes['packages'] - $content['packages'],
                'units' => $attributes['units'] - $content['units'],
            );


            $activity_palett_has_product_log->attributes = array(
                'activity_palett_has_product_id' => $activity_palett_has_product->id,
                'activity_palett_id' => $activity_palett_has_product->activity_palett_id,
                'sscc' => $activity_palett_has_product->sscc,
                'product_id' => $activity_palett_has_product->product_id,
                'product_barcode' => $activity_palett_has_product->product_barcode,
                'quantity' => $difference['quantity'],
                'packages' => $difference['packages'],
                'units' => $difference['units'],
                'reason' => $attributes['reason'],
            );
            if ($activity_palett_has_product_log->save()) {
                echo json_encode($activity_palett_has_product_log->attributes);
            } else {
                echo CActiveForm::validate($activity_palett_has_product_log);
            }

            Yii::app()->end();


        }


        $this->render('index', array(
            'model' => $model,
            'activity_palett_has_product_log' => $activity_palett_has_product_log,
        ));
    }

    public function ExportExcel($model)
    {
        $paletts_has_products = array();
        foreach ($model as $sloc_has_activity_palett) {
            $activity_palett_has_products = ActivityPalettHasProduct::model()->findAllByAttributes(array('activity_palett_id' => $sloc_has_activity_palett->activity_palett_id));
            foreach ($activity_palett_has_products as $activity_palett_has_product) {
                $paletts_has_products[] = $activity_palett_has_product;
            }

        }
        $model = $paletts_has_products;

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $heading = array('SLOC kod', 'SSCC', 'Tip skladištenja', 'Šifra proizvoda', 'Naziv proizvoda', 'Barkod proizvoda', 'Količina', 'Nalog', 'Kreirano', 'Poslednje kretanje');

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

            if (!$data->activityPalett->isLocated()) {
                continue;
            }

            $i = 0;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->activityPalett->inSloc->sloc_code);


            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->activityPalett->sscc);

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->activityPalett->inSloc->storageType->title);

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
            $sheet->setCellValue($cell, $data->content['quantity']);
            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->activityPalett->activityOrder->order_number);

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, date('Y-m-d', strtotime($data->activityPalett->created_dt)));

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, date('Y-m-d', strtotime($data->created_dt)));


            $row++;
        }


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="SLOC.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');

    }

    public
    function actionAjaxGetActivityPalettHasProduct($id)
    {
        $model = ActivityPalettHasProduct::model()->findByPk($id);
        if ($model === null) {
            echo 'ERROR';
        }
        $attributes = $model->attributes;
        $attributes['product_info'] = $model->product->internal_product_number . ' - ' . $model->product->title;
        $content = $model->content;
        $attributes['quantity'] = $content['quantity'];
        $attributes['packages'] = $content['packages'];
        $attributes['units'] = $content['units'];
        echo json_encode($attributes);
    }

    public function actionAjaxGetActivityPalettHasProductHistory($id)
    {
        $activity_palett_has_product = ActivityPalettHasProduct::model()->findByPk($id);
        if ($activity_palett_has_product === null) {
            echo '';
            Yii::app()->end();
        }

        $user = User::model()->findByPk($activity_palett_has_product->created_user_id);
        $history = array();
        $history[] = array(
            'datetime' => $activity_palett_has_product->created_dt,
            'user' => $user ? $user->name : "",
            'quantity' => $activity_palett_has_product->quantity,
            'packages' => $activity_palett_has_product->packages,
            'units' => $activity_palett_has_product->units,
            'reason' => Yii::t('app', 'Acceptance'),
        );


        $condition = '(pick_type="move" OR pick_type="product" OR pick_type="palett") AND product_id=' . $activity_palett_has_product->product_id . ' AND activity_palett_id=' . $activity_palett_has_product->activity_palett_id . ' AND quantity > 0';

        $picks = Pick::model()->findAll(array('condition' => $condition));

        foreach ($picks as $pick) {
            $user = User::model()->findByPk($pick->updated_user_id);

            if ($user == null) {
                $user = User::model()->findByPk($pick->created_user_id);
            }

            if ($pick->pick_type == 'move') {
                $reason = Yii::t('app', 'Redistribution') . ' => ' . $pick->sscc_destination;
            } else if ($pick->activityOrder) {
                if ($pick->pick_type == 'palett') {
                    $add = ' <i class="fa fa-tasks"></i>';
                } else {
                    $add = '';
                }
                $reason = Yii::t('app', 'Picked') . ' ' . $pick->activityOrder->order_number . $add;
            } else {
                $reason = Yii::t('app', 'W Dopuna');
            }

            $history[] = array(
                'datetime' => $pick->created_dt,
                'user' => $user ? $user->name : "",
                'quantity' => -$pick->quantity,
                'packages' => -$pick->packages,
                'units' => -$pick->units,
                'reason' => $reason
            );
        }

        $condition = 'quantity IS NOT NULL AND product_id=' . $activity_palett_has_product->product_id . ' AND activity_palett_id=' . $activity_palett_has_product->activity_palett_id;

        $picks = PickWeb::model()->findAll(array('condition' => $condition));

        foreach ($picks as $pick) {
            $user = User::model()->findByPk($pick->created_user_id);


            $history[] = array(
                'datetime' => $pick->created_dt,
                'user' => $user ? $user->name : "",
                'quantity' => -$pick->quantity,

                'packages' => 0,
                'units' => -$pick->quantity,

                'reason' => 'Web prodaja'
            );
        }


        $condition = '(pick_type="move" OR pick_type="product" OR pick_type="palett") AND product_id=' . $activity_palett_has_product->product_id . ' AND activity_palett_id=' . $activity_palett_has_product->activity_palett_id . ' AND quantity = 0';
        $picks = Pick::model()->findAll(array('condition' => $condition));
        foreach ($picks as $pick) {
            $user = User::model()->findByPk($pick->updated_user_id);

            if ($user == null) {
                $user = User::model()->findByPk($pick->created_user_id);
            }

            if ($pick->pick_type == 'move') {
                $reason = Yii::t('app', 'Reserved for redistribution') . ' => ' . $pick->sscc_destination;
            } else if ($pick->activityOrder) {
                $reason = Yii::t('app', 'Reserved for') . ' ' . $pick->activityOrder->order_number;
            } else {
                $reason = Yii::t('app', 'Reserved for W Dopuna');
            }

            $history[] = array(
                'datetime' => $pick->created_dt,
                'user' => $user ? $user->name : "",
                'quantity' => -$pick->target,
                'packages' => -$pick->packages,
                'units' => -$pick->units,
                'reason' => $reason
            );
        }

        $logs = ActivityPalettHasProductLog::model()->findAllByAttributes(
            array(
                'activity_palett_id' => $activity_palett_has_product->activity_palett_id,
                'product_id' => $activity_palett_has_product->product_id
            )
        );

        foreach ($logs as $log) {
            $user = User::model()->findByPk($log->created_user_id);
            $history[] = array(
                'datetime' => $log->created_dt,
                'user' => $user ? $user->name : "",
                'quantity' => $log->quantity,
                'packages' => $log->packages,
                'units' => $log->units,
                'reason' => $log->reason,
            );
        }

        usort($history, function ($a, $b) {
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
                    'header' => Yii::t('app', 'Date And Time'),
                    'name' => 'datetime',
                ),
                array(
                    'header' => Yii::t('app', 'User'),
                    'name' => 'user',
                ),
                array(
                    'name' => 'packages',
                    'header' => Yii::t('app', 'Packages'),
                    'htmlOptions' => array('class' => 'text-right'),
                    'headerHtmlOptions' => array('class' => 'text-right'),
                ),
                array(
                    'name' => 'units',
                    'header' => Yii::t('app', 'Units'),
                    'htmlOptions' => array('class' => 'text-right'),
                    'headerHtmlOptions' => array('class' => 'text-right'),
                ),

                array(
                    'name' => 'quantity',
                    'header' => Yii::t('app', 'Quantity'),
                    'htmlOptions' => array('class' => 'text-right'),
                    'headerHtmlOptions' => array('class' => 'text-right'),
                ),
                array(
                    'header' => Yii::t('app', 'Remains'),
                    'class' => 'TotalColumn',
                    'attribute' => 'quantity',
                    'htmlOptions' => array('class' => 'text-right'),
                    'headerHtmlOptions' => array('class' => 'text-right'),
                ),
                array(
                    'name' => 'reason',
                    'type' => 'raw',
                    'header' => Yii::t('app', 'Reason'),
                ),

            ),
        ), true);

    }

    public
    function actionAjaxUpdate()
    {
        $attribute = $_POST['name'];
        $value = $_POST['value'];
        $id = $_POST['pk'];

        $model = $this->loadModel($id);
        $model->$attribute = $value;
        $model->save();
        echo 'ok';
    }

    public
    function actionAjaxArrangeProduct()
    {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        $product = Product::model()->findByPk($product_id);
        if ($product === null) {
            echo 'ERROR';
            Yii::app()->end();
        }
        if (!$product->defaultPackage || $product->defaultPackage->product_count == 0) {
            echo 'ERROR';
            Yii::app()->end();
        }
        $packages = floor($quantity / $product->defaultPackage->product_count);

        $units = $quantity - ($packages * $product->defaultPackage->product_count);


        echo json_encode(array(
            'packages' => $packages,
            'units' => $units,
        ));


    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected
    function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'sloc-has-activity-palett-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionResProductHistory()
    {

        $product = new Product;

        $sql = 'SELECT activity_order.id FROM activity_order JOIN activity ON activity_order.activity_id = activity.id WHERE activity.direction="in"';
        $in_activity_order_ids = Yii::app()->db->createCommand($sql)->queryColumn();
        $sql = 'SELECT activity_order.id FROM activity_order JOIN activity ON activity_order.activity_id = activity.id WHERE activity.direction="out"';
        $out_activity_order_ids = Yii::app()->db->createCommand($sql)->queryColumn();

        $activity_palett_has_product = false;
        $sloc_has_product = false;
        if (isset($_REQUEST['Product'])) {

            $product = Product::model()->findByAttributes(array('product_barcode' => $_REQUEST['Product']['product_barcode']));
            if ($product) {

                $sql = 'SELECT activity_order.id, activity_order.order_number,activity_order_product.quantity,activity_order_product.created_dt FROM activity_order_product JOIN activity_order ON activity_order_product.activity_order_id = activity_order.id WHERE activity_order_product.product_id=' . $product->id . ' AND activity_order.id IN(' . implode(',', $in_activity_order_ids) . ')';
                $quantities = Yii::app()->db->createCommand($sql)->queryAll();

                if ($quantities) {

                    $in = array();
                    $inq = 0;
                    $inqreal = 0;
                    foreach ($quantities as $quantity) {
                        $palett_quantity = false;

                        $sql = 'SELECT id FROM activity_palett WHERE activity_order_id=' . $quantity['id'];
                        $activity_palett_ids = Yii::app()->db->createCommand($sql)->queryColumn();

                        if ($activity_palett_ids) {
                            $sql = 'SELECT SUM(quantity) FROM activity_palett_has_product WHERE product_id=' . $product->id . ' AND activity_palett_id IN (' . implode(',', $activity_palett_ids) . ')';
                            $palett_quantity = Yii::app()->db->createCommand($sql)->queryScalar();
                        }
                        if (!$palett_quantity) {
                            $palett_quantity = '?';
                        }


                        $in[] = array(
                            'order_number' => $quantity['order_number'],
                            'datetime' => $quantity['created_dt'],
                            'quantity' => $quantity['quantity'],
                            'real_quantity' => $palett_quantity
                        );

                        $inq += $quantity['quantity'];
                        $inqreal += (int)$palett_quantity;
                    }


                    $sql = 'SELECT activity_order.id, activity_order.order_number,activity_order_product.quantity,activity_order_product.created_dt FROM activity_order_product JOIN activity_order ON activity_order_product.activity_order_id = activity_order.id WHERE activity_order_product.product_id=' . $product->id . ' AND activity_order.id IN(' . implode(',', $out_activity_order_ids) . ')';
                    $quantities = Yii::app()->db->createCommand($sql)->queryAll();

                    $out = array();

                    $outq = 0;
                    foreach ($quantities as $quantity) {
                        $palett_quantity = false;

                        $sql = 'SELECT id FROM activity_palett WHERE activity_order_id=' . $quantity['id'];
                        $activity_palett_ids = Yii::app()->db->createCommand($sql)->queryColumn();

                        if ($activity_palett_ids) {
                            $sql = 'SELECT SUM(quantity) FROM activity_palett_has_product WHERE product_id=' . $product->id . ' AND activity_palett_id IN (' . implode(',', $activity_palett_ids) . ')';
                            $palett_quantity = Yii::app()->db->createCommand($sql)->queryScalar();
                        }
                        if (!$palett_quantity) {
                            $palett_quantity = 0;
                        }


                        $out[] = array(
                            'order_number' => $quantity['order_number'],
                            'datetime' => $quantity['created_dt'],
                            'quantity' => $quantity['quantity'],
                            'real_quantity' => $palett_quantity
                        );
                        $outq += $palett_quantity;

                    }


                    $web = array();
                    $webq = 0;
                    $sql = 'SELECT web_order.id, web_order.order_number,web_order_product.product_id, web_order_product.quantity,web_order_product.created_dt FROM web_order_product JOIN web_order ON web_order_product.web_order_id = web_order.id WHERE web_order.status=1 AND web_order_product.product_id=' . $product->id;
                    $quantities = Yii::app()->db->createCommand($sql)->queryAll();


                    foreach ($quantities as $quantity) {


                        $sql = 'SELECT SUM(quantity) FROM pick_web WHERE quantity IS NOT NULL AND product_id=' . $product->id . ' AND web_order_id = ' . $quantity['id'];
                        $picked_quantity = Yii::app()->db->createCommand($sql)->queryScalar();

                        if (!$picked_quantity) {
                            $picked_quantity = 0;
                        }


                        $web[] = array(
                            'order_number' => $quantity['order_number'],
                            'datetime' => $quantity['created_dt'],
                            'quantity' => $quantity['quantity'],
                            'real_quantity' => $picked_quantity
                        );
                        $webq += $picked_quantity;
                    }


                    $corr = array();
                    $corrq = 0;
                    $sql = 'SELECT sscc, quantity, reason , created_dt, created_user_id FROM activity_palett_has_product_log WHERE LEFT(reason,13) = "*** POPIS ***" AND product_id=' . $product->id;
                    $quantities = Yii::app()->db->createCommand($sql)->queryAll();
                    foreach ($quantities as $quantity) {
                        $user = User::model()->findByPk($quantity['created_user_id']);
                        if ($user === null) {
                            $user_name = 'Obrisan korisnik ID: ' . $quantity['created_user_id'];
                        } else {
                            $user_name = $user->name;
                        }
                        $corr[] = array(
                            'sscc' => $quantity['sscc'],
                            'quantity' => $quantity['quantity'],
                            'datetime' => $quantity['created_dt'],
                            'reason' => $quantity['reason'],
                            'username' => $user_name,
                        );
                        $corrq += $quantity['quantity'];
                    }
                    /*** WEB DOPUNE ***
                     *
                    $sql = 'SELECT sscc_source, quantity, "W dopuna" reason,created_dt,created_user_id FROM pick WHERE activity_order_id IS NULL AND pick_type <> "move" AND status=1 AND product_id='.$product->id;
                    $quantities = Yii::app()->db->createCommand($sql)->queryAll();
                    foreach ($quantities as $quantity) {
                        $user = User::model()->findByPk($quantity['created_user_id']);
                        if ($user === null) {
                            $user_name = 'Obrisan korisnik ID: ' . $quantity['created_user_id'];
                        } else {
                            $user_name = $user->name;
                        }
                        $corr[] = array(
                            'sscc' => $quantity['sscc_source'],
                            'quantity' => - $quantity['quantity'],
                            'datetime' => $quantity['created_dt'],
                            'reason' => $quantity['reason'],
                            'username' => $user_name,
                        );
                        $corrq += - $quantity['quantity'];
                    }
                     * */

                    $sql = 'SELECT sloc_code, quantity, reason , created_dt, created_user_id FROM sloc_has_product_log WHERE LEFT(reason,13) = "*** POPIS ***" AND product_id=' . $product->id;
                    $quantities = Yii::app()->db->createCommand($sql)->queryAll();
                    foreach ($quantities as $quantity) {
                        $user = User::model()->findByPk($quantity['created_user_id']);
                        if ($user === null) {
                            $user_name = 'Obrisan korisnik ID: ' . $quantity['created_user_id'];
                        } else {
                            $user_name = $user->name;
                        }
                        $corr[] = array(
                            'sscc' => $quantity['sloc_code'],
                            'quantity' => $quantity['quantity'],
                            'datetime' => $quantity['created_dt'],
                            'reason' => $quantity['reason'],
                            'username' => $user_name,
                        );
                        $corrq += $quantity['quantity'];
                    }
                }
                $model = array(
                    'product' => $product,
                    'in' => $in,
                    'inq' => $inq,
                    'inqreal' => $inqreal,
                    'out' => $out,
                    'outq' => $outq,
                    'web' => $web,
                    'webq' => $webq,
                    'corr' => $corr,
                    'corrq' => $corrq,

                );


                $activity_palett_has_product = new ActivityPalettHasProduct('searchPresent');
                $activity_palett_has_product->unsetAttributes();
                $activity_palett_has_product->product_id = $product->id;

                $sloc_has_product = new SlocHasProduct('search');
                $sloc_has_product->unsetAttributes();
                $sloc_has_product->product_id = $product->id;

                $result = [];
                foreach ($activity_palett_has_product->searchPresent()->getData() as $a) {
                    if (!$a->activityPalett->isLoaded() && $a->realQuantity > 0) {
                        $result[] = $a;
                    }
                }

                $activity_palett_has_product = new CArrayDataProvider($result, array(
                    'id' => 'palett-provider',
                    'sort' => array(),
                    'pagination' => array(
                        'pageSize' => 9999,
                    ),
                ));


            } else {

                $product = new Product;
                $product->addError('product_barcode', 'Proizvod ne postoji.');
            }


        }


        if (isset($_GET['excel'])) {

            $this->productHistoryToExcel($product, $model, $activity_palett_has_product, $sloc_has_product);
            Yii::app()->end();
        }


        $this->render('product_history', array(
            'product' => $product,
            'model' => $model ?? array(),
            'activity_palett_has_product' => $activity_palett_has_product,
            'sloc_has_product' => $sloc_has_product,
        ));
    }


    public function productHistoryToExcel($product, $model, $activity_palett_has_product, $sloc_has_product)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle($product->product_barcode);
        $letters = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],


        ];

        $fillArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],

            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,

                'startColor' => [
                    'argb' => 'FFA0A0A0',
                ],

            ],
        ];

        $borderArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => array('argb' => '00000000'),
                ),
            ),
        );

        $row = 1;
        $sheet->getRowDimension($row)->setRowHeight(30);
        $sheet->mergeCells("A$row:N$row");


        $cell = 'A' . $row;
        $sheet->setCellValue($cell, $product->internal_product_number . ' - ' . $product->title . ' - ' . $product->product_barcode);
        $sheet->getStyle($cell)->applyFromArray($styleArray);

        $row = 2;
        $sheet->getRowDimension($row)->setRowHeight(30);
        $sheet->mergeCells("A$row:C$row");
        $cell = 'A' . $row;
        $sheet->setCellValue($cell, "ULAZ");
        $sheet->getStyle($cell)->applyFromArray($fillArray);
        $sheet->mergeCells("D$row:F$row");
        $cell = 'D' . $row;
        $sheet->setCellValue($cell, "IZLAZ");
        $sheet->getStyle($cell)->applyFromArray($fillArray);

        $sheet->mergeCells("G$row:I$row");
        $cell = 'G' . $row;
        $sheet->setCellValue($cell, "WEB");
        $sheet->getStyle($cell)->applyFromArray($fillArray);

        $sheet->mergeCells("J$row:L$row");
        $cell = 'J' . $row;
        $sheet->setCellValue($cell, "POPIS");
        $sheet->getStyle($cell)->applyFromArray($fillArray);

        $sheet->mergeCells("M$row:N$row");
        $cell = 'M' . $row;
        $sheet->setCellValue($cell, "STANJE");
        $sheet->getStyle($cell)->applyFromArray($fillArray);

        $row = 3;

        $sheet->getRowDimension($row)->setRowHeight(30);
        $sheet->mergeCells("A$row:C$row");
        $cell = 'A' . $row;
        $sheet->setCellValue($cell, $model['inq']);
        $sheet->getStyle($cell)->applyFromArray($styleArray);
        $sheet->mergeCells("D$row:F$row");
        $cell = 'D' . $row;
        $sheet->setCellValue($cell, $model['outq']);
        $sheet->getStyle($cell)->applyFromArray($styleArray);

        $sheet->mergeCells("G$row:I$row");
        $cell = 'G' . $row;
        $sheet->setCellValue($cell, $model['webq']);
        $sheet->getStyle($cell)->applyFromArray($styleArray);

        $sheet->mergeCells("J$row:L$row");
        $cell = 'J' . $row;
        $sheet->setCellValue($cell, $model['corrq']);
        $sheet->getStyle($cell)->applyFromArray($styleArray);

        $sheet->mergeCells("M$row:N$row");
        $cell = 'M' . $row;
        $sheet->setCellValue($cell, $model['inq'] - ($model['outq'] + $model['webq']) + $model['corrq']);
        $sheet->getStyle($cell)->applyFromArray($styleArray);


        $sheet->getStyle("A1:N$row")->applyFromArray($borderArray);

        $row = 5;

        $sheet->getRowDimension($row)->setRowHeight(30);
        $sheet->mergeCells("A$row:D$row");

        $cell = 'A' . $row;
        $sheet->setCellValue($cell, "ULAZI");
        $sheet->getStyle($cell)->applyFromArray($fillArray);


        $sheet->mergeCells("F$row:I$row");
        $cell = 'F' . $row;
        $sheet->setCellValue($cell, "KOMERCIJALNI NALOZI");
        $sheet->getStyle($cell)->applyFromArray($fillArray);

        $sheet->mergeCells("K$row:N$row");
        $cell = 'K' . $row;
        $sheet->setCellValue($cell, "WEB NALOZI");
        $sheet->getStyle($cell)->applyFromArray($fillArray);




        $borderArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => '00000000'),
                ),
            ),
        );
        $row = 6;


        $heading = array(
            'Datum',
            'Broj naloga',
            'Najavljena količina',
            'Primljena količina',
            '',
            'Datum',
            'Broj naloga',
            'Tražena količina',
            'Pikovana količina',
            '',
            'Datum',
            'Broj naloga',
            'Tražena količina',
            'Pikovana količina',


        );
        $sheet->getRowDimension($row)->setRowHeight(30);

        for ($i = 0; $i <= 13; $i++) {
            $sheet->getColumnDimension($letters[$i])->setAutoSize(true);
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $heading[$i]);
            $sheet->getStyle($cell)->applyFromArray($styleArray);
        }

        $row = 7;

        foreach ($model['in'] as $data) {
            $i = 0;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, date('Y-m-d', strtotime($data['datetime'])));
            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data['order_number']);
            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data['quantity']);
            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data['real_quantity']);
            $row++;

        }
        $row--;
        $sheet->getStyle("A6:$letters[$i]$row")->applyFromArray($borderArray);


        $row = 7;

        foreach ($model['out'] as $data) {
            $i = 5;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, date('Y-m-d', strtotime($data['datetime'])));

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data['order_number']);
            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data['quantity']);
            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data['real_quantity']);
            $row++;

        }
        $row--;
        $sheet->getStyle("F6:$letters[$i]$row")->applyFromArray($borderArray);

        $row = 7;

        foreach ($model['web'] as $data) {
            $i = 10;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, date('Y-m-d', strtotime($data['datetime'])));

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data['order_number']);
            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data['quantity']);
            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data['real_quantity']);
            $row++;

        }
        $row--;
        $sheet->getStyle("K6:$letters[$i]$row")->applyFromArray($borderArray);







        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $product->product_barcode . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');

    }

}
