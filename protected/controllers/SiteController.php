<?php
require Yii::getPathOfAlias('application') . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SiteController extends Controller
{

    public $layout = '//layouts/column1';
    public $menu = array();
    public $breadcrumbs = array();

    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    public function actionSos($id)
    {
        $sql = 'SELECT product_id, SUM(quantity) suma FROM activity_palett_has_product WHERE activity_palett_id IN (SELECT id FROM activity_palett WHERE activity_order_id=' . $id . ') GROUP BY product_id';
        $result = Yii::app()->db->createCommand($sql)->queryAll();


        foreach ($result as $r) {
            $sql = 'SELECT SUM(quantity) FROM pick WHERE activity_order_id=' . $id . ' AND product_id = ' . $r['product_id'];
            $res = Yii::app()->db->createCommand($sql)->queryScalar();
            if ($r['suma'] != $res || $res == null) {

                $product = Product::model()->findByPk($r['product_id']);
                echo $r['product_id'] . ' ' . $product->product_barcode . ' ' . $product->title . ' ' . $r['suma'] . ' ' . $res . '<br>';
            }
        }

        echo 'PICK CHECK DONE';
        echo '<br>';


        $activity_order = ActivityOrder::model()->findByPk($id);
        if ($activity_order === null) {
            throw new CHttpException('404', 'Nalog ne postoji.');
        }


        $target = 0;
        foreach ($activity_order->activityOrderProducts as $activity_order_product) {

            $sql = 'SELECT SUM(quantity) FROM pick WHERE activity_order_id=' . $id . ' AND product_id = ' . $activity_order_product->product_id;
            $res = Yii::app()->db->createCommand($sql)->queryScalar();
            if ($activity_order_product->quantity != $res || $res == null) {

                $product = Product::model()->findByPk($activity_order_product->product_id);
                echo $activity_order_product->product_id . ' ' . $product->product_barcode . ' ' . $product->title . ' ' . $activity_order_product->quantity . ' ' . $res . '<br>';
            }


            $target += $activity_order_product->quantity;

        }
        echo 'TARGET : ' . $target . '<br>';

    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionAuthenticate()
    {
        $user = User::model()->findByPk(Yii::app()->user->id);

        if (isset($_POST['code'])) {

            if ($user->code == $_POST['code']) {

                Yii::app()->session['authenticated'] = true;
                Yii::app()->user->setFlash('success', Yii::t('app', 'Welcome'));
                $this->redirect('index');
            } else {
                Yii::app()->user->setFlash('error', Yii::t('app', 'Incorrect code'));
            }
        } else {
            $mail = new YiiMailer();
            $mail->setFrom('no-reply@wt-control.com', 'WTC');
            $mail->setTo($user->email);
            $mail->setSubject('Code');
            $mail->setBody($user->code);
            $mail->send();
        }


        $this->render('auth', array());
    }


    public function actionResExcel()
    {
        $activity_orders = ActivityOrder::model()->findAll(array('condition' => 'status = 0', 'order' => 'created_dt'));

        $inbound = array();
        $outbound = array();

        foreach ($activity_orders as $activity_order) {
            if ($activity_order->activity->direction == 'in') {
                $inbound[] = $activity_order;
            } else {
                $outbound[] = $activity_order;
            }

        }

        $web_orders = WebOrder::model()->findAll(array('condition' => 'status = 0', 'order' => 'created_dt'));

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Inbound');


        $heading = array('Vreme dodeljivanja', 'Početak rada pikera', 'Broj naloga', 'Broj zadatih artikala', 'Broj zadatih komada', 'Broj primljenih artikala', 'Broj primljenih komada', 'Broj preostalih artikala', 'Broj preostalih komada', 'Piker', 'Trajanje rada');

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


        foreach ($inbound as $data) {
            $i = 0;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->created_dt);


            $sql = 'SELECT activity_palett_has_product.created_dt FROM activity_palett_has_product JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id WHERE activity_palett.activity_order_id = ' . $data->id . ' ORDER BY activity_palett_has_product.created_dt ASC LIMIT 0,1';
            $result = Yii::app()->db->createCommand($sql)->queryScalar();
            $value = $result ? date('d.m.Y H:i', strtotime($result)) : "";

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $value);

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->order_number);

            $sql = 'SELECT DISTINCT product_id FROM activity_order_product WHERE activity_order_id = ' . $data->id;
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            $value = $result ? count($result) : 0;


            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $value);

            $sql = 'SELECT SUM(quantity) product_id FROM activity_order_product WHERE activity_order_id = ' . $data->id;
            $result = Yii::app()->db->createCommand($sql)->queryScalar();
            $value = $result ? $result : 0;

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $value);

            $sql = 'SELECT DISTINCT product_id FROM activity_palett_has_product JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id WHERE activity_palett.activity_order_id = ' . $data->id;
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            $value = $result ? count($result) : 0;

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $value);

            $sql = 'SELECT SUM(quantity) FROM activity_palett_has_product JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id WHERE activity_palett.activity_order_id = ' . $data->id;
            $result = Yii::app()->db->createCommand($sql)->queryScalar();
            $value = $result ? $result : 0;

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $value);

            $sql = 'SELECT DISTINCT product_id FROM activity_order_product WHERE activity_order_id = ' . $data->id;
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            $total = $result ? count($result) : 0;

            $sql = 'SELECT DISTINCT product_id FROM activity_palett_has_product JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id WHERE activity_palett.activity_order_id = ' . $data->id;
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            $completed = $result ? count($result) : 0;

            $value = $total - $completed;

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $value);

            $sql = 'SELECT SUM(quantity) product_id FROM activity_order_product WHERE activity_order_id = ' . $data->id;
            $result = Yii::app()->db->createCommand($sql)->queryScalar();
            $total = $result;

            $sql = 'SELECT SUM(quantity) FROM activity_palett_has_product JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id WHERE activity_palett.activity_order_id = ' . $data->id;
            $result = Yii::app()->db->createCommand($sql)->queryScalar();
            $completed = $result;

            $value = $total - $completed;

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $value);

            $sql = 'SELECT activity_palett_has_product.created_user_id FROM activity_palett_has_product JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id WHERE activity_palett.activity_order_id = ' . $data->id . ' ORDER BY activity_palett_has_product.created_dt ASC LIMIT 0,1';
            $result = Yii::app()->db->createCommand($sql)->queryScalar();
            $user = User::model()->findByPk($result);
            if ($user) {
                $value = $user->name;
            } else {
                $value = '';
            }

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $value);

            $start = new DateTime($data->created_dt);
            $end = new DateTime(date('Y-m-d H:i:s'));
            $diff = $end->diff($start);
            $hours = ($diff->format("%a") * 24) + $diff->format("%h");
            $minutes = $diff->format("%I");

            $value = $hours . ':' . $minutes;
            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $value);

            $row++;

        }

        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(1);

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Outbound');

        $heading = array('Vreme dodeljivanja', 'Početak rada pikera', 'Broj naloga', 'Broj zadatih artikala', 'Broj zadatih komada', 'Broj urađenih artikala', 'Broj urađenih komada', 'Broj preostalih artikala', 'Broj preostalih komada', 'Piker', 'Trajanje rada');

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

        foreach ($outbound as $data) {
            $i = 0;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->created_dt);


            $sql = 'SELECT activity_palett_has_product.created_dt FROM activity_palett_has_product JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id WHERE activity_palett.activity_order_id = ' . $data->id . ' ORDER BY activity_palett_has_product.created_dt ASC LIMIT 0,1';
            $result = Yii::app()->db->createCommand($sql)->queryScalar();
            $value = $result ? date('d.m.Y H:i', strtotime($result)) : "";

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $value);

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->order_number);

            $sql = 'SELECT DISTINCT product_id FROM activity_order_product WHERE activity_order_id = ' . $data->id;
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            $value = $result ? count($result) : 0;


            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $value);

            $sql = 'SELECT SUM(quantity) product_id FROM activity_order_product WHERE activity_order_id = ' . $data->id;
            $result = Yii::app()->db->createCommand($sql)->queryScalar();
            $value = $result ? $result : 0;

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $value);

            $sql = 'SELECT DISTINCT product_id FROM activity_palett_has_product JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id WHERE activity_palett.activity_order_id = ' . $data->id;
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            $value = $result ? count($result) : 0;

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $value);

            $sql = 'SELECT SUM(quantity) FROM activity_palett_has_product JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id WHERE activity_palett.activity_order_id = ' . $data->id;
            $result = Yii::app()->db->createCommand($sql)->queryScalar();
            $value = $result ? $result : 0;

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $value);

            $sql = 'SELECT DISTINCT product_id FROM activity_order_product WHERE activity_order_id = ' . $data->id;
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            $total = $result ? count($result) : 0;

            $sql = 'SELECT DISTINCT product_id FROM activity_palett_has_product JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id WHERE activity_palett.activity_order_id = ' . $data->id;
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            $completed = $result ? count($result) : 0;

            $value = $total - $completed;

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $value);

            $sql = 'SELECT SUM(quantity) product_id FROM activity_order_product WHERE activity_order_id = ' . $data->id;
            $result = Yii::app()->db->createCommand($sql)->queryScalar();
            $total = $result;

            $sql = 'SELECT SUM(quantity) FROM activity_palett_has_product JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id WHERE activity_palett.activity_order_id = ' . $data->id;
            $result = Yii::app()->db->createCommand($sql)->queryScalar();
            $completed = $result;

            $value = $total - $completed;

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $value);

            $sql = 'SELECT activity_palett_has_product.created_user_id FROM activity_palett_has_product JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id WHERE activity_palett.activity_order_id = ' . $data->id . ' ORDER BY activity_palett_has_product.created_dt ASC LIMIT 0,1';
            $result = Yii::app()->db->createCommand($sql)->queryScalar();
            $user = User::model()->findByPk($result);
            if ($user) {
                $value = $user->name;
            } else {
                $value = '';
            }

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $value);

            $start = new DateTime($data->created_dt);
            $end = new DateTime(date('Y-m-d H:i:s'));
            $diff = $end->diff($start);
            $hours = ($diff->format("%a") * 24) + $diff->format("%h");
            $minutes = $diff->format("%I");

            $value = $hours . ':' . $minutes;
            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $value);

            $row++;

        }

        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(2);

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Internet nalozi');

        $heading = array('Vreme dodeljivanja', 'Početak rada pikera', 'Broj naloga', 'Broj zadatih artikala', 'Broj zadatih komada', 'Broj urađenih artikala', 'Broj urađenih komada', 'Broj preostalih artikala', 'Broj preostalih komada', 'Piker', 'Trajanje rada');

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

        foreach ($outbound as $data) {
            $i = 0;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->created_dt);


            $sql = 'SELECT pick_web.created_dt FROM pick_web WHERE pick_web.web_order_id = ' . $data->id . ' ORDER BY pick_web.created_dt ASC LIMIT 0,1';
            $result = Yii::app()->db->createCommand($sql)->queryScalar();
            $value = $result ? date('d.m.Y H:i', strtotime($result)) : "";

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $value);

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->order_number);

            $sql = 'SELECT DISTINCT product_id FROM web_order_product WHERE web_order_id = ' . $data->id;
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            $value = $result ? count($result) : 0;


            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $value);

            $sql = 'SELECT SUM(quantity) product_id FROM web_order_product WHERE web_order_id = ' . $data->id;
            $result = Yii::app()->db->createCommand($sql)->queryScalar();
            $value = $result ? $result : 0;

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $value);

            $sql = 'SELECT DISTINCT product_id FROM pick_web  WHERE web_order_id = ' . $data->id . ' AND status=1';
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            $value = $result ? count($result) : 0;

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $value);

            $sql = 'SELECT SUM(quantity) FROM pick_web WHERE web_order_id = ' . $data->id . ' AND status=1';
            $result = Yii::app()->db->createCommand($sql)->queryScalar();
            $value = $result ? $result : 0;

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $value);

            $sql = 'SELECT DISTINCT product_id FROM web_order_product WHERE web_order_id = ' . $data->id;
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            $total = $result ? count($result) : 0;

            $sql = 'SELECT DISTINCT product_id FROM pick_web  WHERE web_order_id = ' . $data->id . ' AND status=1';
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            $completed = $result ? count($result) : 0;

            $value = $total - $completed;

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $value);

            $sql = 'SELECT SUM(quantity) product_id FROM web_order_product WHERE web_order_id = ' . $data->id;
            $result = Yii::app()->db->createCommand($sql)->queryScalar();
            $total = $result;

            $sql = 'SELECT SUM(quantity) FROM pick_web WHERE web_order_id = ' . $data->id . ' AND status=1';
            $result = Yii::app()->db->createCommand($sql)->queryScalar();
            $completed = $result;

            $value = $total - $completed;

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $value);

            $sql = 'SELECT pick_web.created_user_id FROM pick_web WHERE web_order_id = ' . $data->id . ' ORDER BY created_dt ASC LIMIT 0,1';
            $result = Yii::app()->db->createCommand($sql)->queryScalar();
            $user = User::model()->findByPk($result);
            if ($user) {
                $value = $user->name;
            } else {
                $value = '';
            }

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $value);

            $start = new DateTime($data->created_dt);
            $end = new DateTime(date('Y-m-d H:i:s'));
            $diff = $end->diff($start);
            $hours = ($diff->format("%a") * 24) + $diff->format("%h");
            $minutes = $diff->format("%I");

            $value = $hours . ':' . $minutes;
            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $value);

            $row++;

        }


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Dashboard_' . date('Ymd\THi') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');


    }

    public function actionIndex()
    {

        $activity_orders = ActivityOrder::model()->findAll(array('condition' => 'status = 0', 'order' => 'created_dt'));


        $completed_inbound_products = 0;
        $completed_inbound_quantity = 0;
        $completed_outbound_products = 0;
        $completed_outbound_quantity = 0;
        $total_inbound_orders = 0;
        $total_outbound_orders = 0;

        $inbound = array();
        $outbound = array();

        foreach ($activity_orders as $activity_order) {

            if ($activity_order->activity->direction == 'in') {
                $inbound[] = $activity_order;
                $total_inbound_orders++;
                $sql = 'SELECT DISTINCT product_id FROM activity_palett_has_product JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id WHERE activity_palett.activity_order_id = ' . $activity_order->id;
                $result = Yii::app()->db->createCommand($sql)->queryAll();
                if ($result) {
                    $completed_inbound_products += count($result);
                }
                $sql = 'SELECT SUM(quantity) FROM activity_palett_has_product JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id WHERE activity_palett.activity_order_id = ' . $activity_order->id;
                $result = Yii::app()->db->createCommand($sql)->queryScalar();
                if ($result) {
                    $completed_inbound_quantity += $result;
                }
            } else {
                $outbound[] = $activity_order;
                $total_outbound_orders++;
                $sql = 'SELECT DISTINCT product_id FROM activity_palett_has_product JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id WHERE activity_palett.activity_order_id = ' . $activity_order->id;
                $result = Yii::app()->db->createCommand($sql)->queryAll();
                if ($result) {
                    $completed_outbound_products += count($result);
                }
                $sql = 'SELECT SUM(quantity) FROM activity_palett_has_product JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id WHERE activity_palett.activity_order_id = ' . $activity_order->id;
                $result = Yii::app()->db->createCommand($sql)->queryScalar();
                if ($result) {
                    $completed_outbound_quantity += $result;
                }
            }


        }

        $model[0] = new CArrayDataProvider($inbound, array(
            'id' => 'inbound-provider',
            'sort' => array(),
            'pagination' => array(
                'pageSize' => 9999,
            ),
        ));
        $model[1] = new CArrayDataProvider($outbound, array(
            'id' => 'outbound-provider',
            'sort' => array(),
            'pagination' => array(
                'pageSize' => 9999,
            ),
        ));


        $web_orders = WebOrder::model()->findAll(array('condition' => 'status = 0', 'order' => 'created_dt'));

        $total_web_orders = count($web_orders);
        $completed_web_products = 0;
        $completed_web_quantity = 0;

        foreach ($web_orders as $web_order) {
            $sql = 'SELECT DISTINCT product_id FROM pick_web WHERE web_order_id = ' . $web_order->id . ' AND status=1';
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            if ($result) {
                $completed_web_products += count($result);
            }

            $sql = 'SELECT SUM(quantity) FROM pick_web WHERE web_order_id = ' . $web_order->id . ' AND status=1';
            $result = Yii::app()->db->createCommand($sql)->queryScalar();
            if ($result) {
                $completed_web_quantity += $result;
            }
        }

        $model[2] = new CArrayDataProvider($web_orders, array(
            'id' => 'web-provider',
            'sort' => array(),
            'pagination' => array(
                'pageSize' => 9999,
            ),
        ));

        $this->render('index', array(
                'model' => $model,
                'total_inbound_orders' => $total_inbound_orders,
                'total_outbound_orders' => $total_outbound_orders,
                'completed_inbound_products' => $completed_inbound_products,
                'completed_inbound_quantity' => $completed_inbound_quantity,
                'completed_outbound_products' => $completed_outbound_products,
                'completed_outbound_quantity' => $completed_outbound_quantity,
                'total_web_orders' => $total_web_orders,
                'completed_web_products' => $completed_web_products,
                'completed_web_quantity' => $completed_web_quantity

            )
        );

    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest) {
                echo $error['message'];
            } else {
                $this->render('error', $error);
            }
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact()
    {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
                $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
                $headers = "From: $name <{$model->email}>\r\n" .
                    "Reply-To: {$model->email}\r\n" .
                    "MIME-Version: 1.0\r\n" .
                    "Content-Type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {

        $this->layout = '/layouts/login';
        $model = new LoginForm;
        $user = new User;

        $visible = 'login';

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            if ($model->rememberMe == 'on') {
                $model->rememberMe = 1;
            } else {
                $model->rememberMe = 0;
            }

            if ($model->validate() && $model->login()) {

                Yii::app()->session['location'] = Location::model()->findByPk(User::model()->findByPk(Yii::app()->user->id)->location_id);


             //   CronJobs::check();   !!! PRIVREMENO ISKLJUCENO 30.01.2026 !!!
                // $this->redirect(Yii::app()->baseUrl);
                $this->redirect(Yii::app()->controller->createUrl('index')); // ova linija sredjuje problem sa Chromeom nakon logina u nekim konfiguracijama servera

            }

        }


        // display the login form
        $this->render('login', array('model' => $model, 'user' => $user, 'visible' => $visible));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();

        $this->redirect(Yii::app()->homeUrl);
    }


    public function actionLoadOldPaletts()
    {
        $date = '2022-08-01';


        $picks = Pick::model()->findAll(array('condition' => '(pick_type="palett" OR pick_type="product") AND activity_order_id IS NOT NULL AND status=0'));

        echo 'Pronadjeno ' . count($picks) . ' pikova.<br>';

        $counter = 0;
        $counter1 = 0;
        $counter2 = 0;

        $skip = array(/*
            '22-334-000027',
            '22-PTP-000166',
            '22-NAL-000025',
            '22-PTP-000182',
            '22-PTP-000050',
            '22-PTP000138',
            '22-PTP000025',
            '22-010-000441',
            '22-PTP-000134',
            '22-PTP-000133',
            '22-PTP-000125',
            '22-PTP-000124',
            '22-010-000361',
            '22-KMS-000315-1',
            */
        );

        foreach ($picks as $pick) {
            if (!is_object($pick)) {
                var_dump($pick);
                die();
            }


            if ($pick->activityOrder->created_dt <= $date . ' 00:00:00') {
                $counter1++;

                if (in_array($pick->activityOrder->order_number, $skip)) {
                    continue;
                }


                if ($pick->status == 0) {
                    $load_group = strtotime(date('Y-m-d H:i:s'));
                    $pick->load_group = $load_group;
                    if ($pick->pick_type == 'palett') {


                        $pick->status = 1;

                        if ($pick->save()) {
                            $counter++;
                            $sloc_has_activity_palett = SlocHasActivityPalett::model()->findByAttributes(array('sscc' => $pick->sscc_source));
                            if ($sloc_has_activity_palett !== null) {
                                $sloc_has_activity_palett->delete();
                            }
                        } else {
                            var_dump($pick->getErrors());
                            die();
                        }

                    } else {
                        $pick->status = 1;
                        if ($pick->save()) {
                            $counter++;
                        } else {
                            var_dump($pick->getErrors());
                            die();
                        }
                    }


                }


                if ($pick->activityOrder->status == 0) {
                    $pick->activityOrder->status = 1;
                    if (!$pick->activityOrder->save()) {
                        var_dump($pick->activityOrder->getErrors());
                        die();
                    }
                }
            } else {
                $counter2++;
            }
        }
        $counter3 = 0;
        foreach ($picks as $pick) {
            if ($pick->activityOrder->created_dt <= $date . ' 00:00:00') {
                if ($pick->activityOrder->activity->truck_dispatch_datetime == null) {
                    $pick->activityOrder->activity->truck_dispatch_datetime = date('Y-m-d H:i:s');
                }
                if ($pick->activityOrder->activity->system_acceptance_datetime == null) {
                    $pick->activityOrder->activity->system_acceptance_datetime = date('Y-m-d H:i:s');
                }
                if (!$pick->activityOrder->activity->save()) {
                    echo '<pre>';
                    var_dump($pick->activityOrder->activity->getErrors());
                    var_dump($pick, $pick->activityOrder, $pick->activityOrder->activity);
                    die();
                }
                $counter3++;
            }
        }
        echo 'Pikova pre datuma: ' . $counter1 . '<br>';
        echo 'Pikova posle datuma: ' . $counter2 . '<br>';
        echo 'Utovareno ' . $counter . ' paleta.<br>';
        echo 'Zatvoreno ' . $counter3 . ' loading lista.<br>';


    }

    public function actionCloseInbound()
    {
        $date = '2022-07-01';
        $activities = Activity::model()->findAll(array('condition' => 'direction="in" AND created_dt <= "' . $date . ' 00:00:00"'));
        $counter = 0;
        foreach ($activities as $activity) {
            foreach ($activity->activityOrders as $activity_order) {
                if ($activity_order->status == 0) {
                    $activity_order->status = 1;
                    $activity_order->save();
                }
            }


        }

        foreach ($activities as $activity) {
            if ($activity->system_acceptance_datetime == NULL) {
                $activity->system_acceptance_datetime = date('Y-m-d H:i:s');
            }
            if ($activity->truck_dispatch_datetime == NULL) {
                $activity->truck_dispatch_datetime = date('Y-m-d H:i:s');
            }
            if ($activity->save()) {

                $counter++;
            } else {
                echo 'ERROR ***  ' . $activity->orderRequest->load_list . '<br>';
            }
        }
        echo 'Zatvoreno ' . $counter . ' aktivnosti.</br>';

        $counter = 0;
        $activity_orders = ActivityOrder::model()->findAll(array('condition' => 'created_dt <= "' . $date . ' 00:00:00" AND status=0'));

        foreach ($activity_orders as $activity_order) {
            if ($activity_order->activity->direction != 'in') {

                continue;
            }
            $activity_order->status = 1;
            if ($activity_order->save()) {

                $counter++;
            } else {
                var_dump($activity_order->getErrors());
                die();
            }
        }
        echo 'Zatvoreno ' . $counter . ' naloga.</br>';

    }

    public function actionLoadActivityOrder($id)
    {
        $activity_order = ActivityOrder::model()->findByPk($id);
        if ($activity_order === null) {
            throw new CHttpException('404', 'Activity Order Not Found!');
        }

        $picks = Pick::model()->findAll(array('condition' => 'status = 0 AND (pick_type="palett" OR pick_type="product") AND activity_order_id = ' . $id));

        $counter = 0;
        foreach ($picks as $pick) {

            if (!is_object($pick)) {
                var_dump($pick);
                die();
            }
            $load_group = strtotime(date('Y-m-d H:i:s'));
            $pick->load_group = $load_group;
            if ($pick->pick_type == 'palett') {

                $sloc_has_activity_palett = SlocHasActivityPalett::model()->findByAttributes(array('sscc' => $pick->sscc_source));
                if ($sloc_has_activity_palett !== null) {
                    $pick->status = 1;
                    if ($pick->save()) {
                        $counter++;
                        $sloc_has_activity_palett->delete();
                    }
                }
            } else {
                $pick->status = 1;
                $pick->save();
                $counter++;
            }
        }

        echo 'Utovareno ' . $counter . ' paleta.<br>';

        if ($activity_order->status == 0) {
            $activity_order->status = 1;
            $activity_order->save();
        }
        echo 'Nalog ' . $activity_order->order_number . ' zatvoren.<br>';

        if ($activity_order->activity->truck_dispatch_datetime == null) {
            $activity_order->activity->truck_dispatch_datetime = date('Y-m-d H:i:s');
        }
        if ($activity_order->activity->system_acceptance_datetime == null) {
            $activity_order->activity->system_acceptance_datetime = date('Y-m-d H:i:s');
        }
        $activity_order->activity->save();

        echo 'Aktivnost završena.<br>';

    }

    public function actionTest()
    {
     $product = Product::model()->findByAttributes(array('product_barcode' => '9788661097133'));
     echo '<pre>';
    // var_dump($product->attributes);
     var_dump($product->getTotalQuantity());
     var_dump($product->getStockQuantity());


    }

    public function actionCloseActivityOrders()
    {
        $skip = array();
        $counter = 0;
        $activity_orders = ActivityOrder::model()->findAll(array("condition" => "status=1"));
        foreach ($activity_orders as $activity_order) {
            if ($activity_order->activity->direction == "in") {
                continue;
            }
            /*
            if (in_array($activity_order->order_number, $skip)) {
                continue;
            }
            */
            if ($activity_order->activity->system_acceptance_datetime == NULL || $activity_order->activity->truck_dispatch_datetime == null) {
                continue;
            }

            $picks = Pick::model()->findAllByAttributes(array('status' => 0, 'activity_order_id' => $activity_order->id));
            foreach ($picks as $pick) {
                if ($pick->sscc_destination == null) {
                    $pick->delete();
                    continue;
                }
                $load_group = strtotime(date('Y-m-d H:i:s'));
                $pick->load_group = $load_group;
                if ($pick->pick_type == 'palett') {
                    $sloc_has_activity_palett = SlocHasActivityPalett::model()->findByAttributes(array('sscc' => $pick->sscc_source));
                    $pick->status = 1;
                    $counter++;
                    if ($pick->save()) {
                        if ($sloc_has_activity_palett !== null) {
                            $sloc_has_activity_palett->delete();
                        }
                    } else {
                        var_dump($pick->getErrors());
                        die();
                    }
                } else {
                    $pick->status = 1;
                    if ($pick->save()) {
                        $counter++;
                    } else {
                        var_dump($pick->getErrors());
                        die();
                    }
                }

            }
            /*
            if ($activity_order->activity->truck_dispatch_datetime == null) {
                $activity_order->activity->truck_dispatch_datetime = date('Y-m-d H:i:s');
            }
            if ($activity_order->activity->system_acceptance_datetime == null) {
                $activity_order->activity->system_acceptance_datetime = date('Y-m-d H:i:s');
            }
            */



        }
        echo 'Utovareno ' . $counter . ' pikova.';
    }

    public function actionReserveSlocs()
    {
        $start = time();
        $inputFileName = Yii::getPathOfAlias('application') . '/../import/Reservations.xlsx';
        $sheetnames = ['Sheet1'];

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setLoadSheetsOnly($sheetnames);
        $spreadsheets = $reader->load($inputFileName);

        $worksheets = array();
        foreach ($spreadsheets->getWorksheetIterator() as $worksheet) {
            $worksheet_array = $worksheet->toArray();
            array_shift($worksheet_array);
            $worksheets[] = $worksheet_array;
        }

        $rows = array_slice($worksheets[0], 0);
        $counter = 1;
        foreach ($rows as $row) {
            $product_barcode = trim($row[2]);
            // $product_barcode = trim($row[2],"'");


            $sloc_code = trim($row[4]);


            if ($sloc_code != '') {
                $product = Product::model()->findByAttributes(array('product_barcode' => $product_barcode));
                $sloc = Sloc::model()->findByAttributes(array('sloc_code' => $sloc_code));

                if ($product != null && $sloc != null) {
                    $sloc->reserved_product_id = $product->id;
                    echo $counter . '. ' . $product_barcode . ' ' . $sloc_code . "<br>";

                    $sloc->reserved_product_id = $product->id;
                    if (!$sloc->save()) {
                        die($sloc->sloc_code);
                    }

                    $counter++;

                }
            }

        }
    }

    public function actionCheck()
    {
        $activity_order_product = ActivityOrderProduct::model()->findByPk(34195);
        echo '<pre>';
        var_dump($activity_order_product->product);
    }

    public function actionCronStock()
    {
        $this->runCronjobTool('checkstock');
    }

    public function actionDailyReport()
    {
        $this->runDailyReportTool('send');
    }

    private function runCronjobTool($action)
    {
        $commandPath = Yii::app()->getBasePath() . DIRECTORY_SEPARATOR . 'commands';
        $runner = new CConsoleCommandRunner();
        $runner->addCommands($commandPath);
        $commandPath = Yii::getFrameworkPath() . DIRECTORY_SEPARATOR . 'cli' . DIRECTORY_SEPARATOR . 'commands';
        $runner->addCommands($commandPath);
        $args = array('yiic', 'cronjob', $action);

        $runner->run($args);
        echo 'Finished';
    }

    private function runDailyReportTool($action)
    {
        $commandPath = Yii::app()->getBasePath() . DIRECTORY_SEPARATOR . 'commands';
        $runner = new CConsoleCommandRunner();
        $runner->addCommands($commandPath);
        $commandPath = Yii::getFrameworkPath() . DIRECTORY_SEPARATOR . 'cli' . DIRECTORY_SEPARATOR . 'commands';
        $runner->addCommands($commandPath);
        $args = array('yiic', 'dailyreport', $action);
        ob_start();
        $runner->run($args);
        echo htmlentities(ob_get_clean(), null, Yii::app()->charset);
    }


    public function actionImportWebSlocs()
    {
        $start = time();
        $inputFileName = Yii::getPathOfAlias('application') . '/../import/' . 'ws23.xlsx';
        $sheetnames = ['Sheet1'];

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setLoadSheetsOnly($sheetnames);
        $spreadsheets = $reader->load($inputFileName);

        $worksheets = array();
        foreach ($spreadsheets->getWorksheetIterator() as $worksheet) {
            $worksheet_array = $worksheet->toArray();
            array_shift($worksheet_array);
            $worksheets[] = $worksheet_array;
        }

        // $rows = array_slice($worksheets[0], 3);
        $rows = $worksheets[0];

        $i = 1;
        echo '<pre>';
        foreach ($rows as $row) {


            $model = new Sloc;
            $model->attributes = array(
                'location_id' => 4,
                'sloc_type_id' => 5,
                'section_id' => 5,
                'sloc_code' => $row[0],
                'sloc_street' => substr($row[0], 0, 3),
                'sloc_field' => substr($row[0], 3, 2),
                'sloc_position' => substr($row[0], 5, 2),
                'sloc_vertical' => substr($row[0], 7, 2),
            );

            if (!$model->save()) {
                var_dump($model->getErrors());
                die();
            }

            // var_dump($model->attributes);
            // var_dump($model->attributes);

        }


        echo 'Finished';


    }
}
