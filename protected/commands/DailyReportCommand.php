<?php

require Yii::getPathOfAlias('application') . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DailyReportCommand extends CConsoleCommand
{


    public function actionSend()
    {

        $from = date('Y-m-d 00:00:00');
        $to = date('Y-m-d H:i:s');

/*
        $from = date('2022-07-13 00:00:00');
        $to = date('2022-07-13 23:55:55');
*/


        // $email = array('rs.dl.beg.warehouse@dbschenker.com','Nazar.Hamadto@dbschenker.com');

        $email = array('gotivac@gmail.com');
       // $email = array('gotivac@gmail.com', 'Vladimir.Lacic@dbschenker.com','Nazar.Hamadto@dbschenker.com');
        $email_schedule = EmailSchedule::model()->findByAttributes(array('command'=>'dailyreport','action'=>'send'));
        $email = explode(',',$email_schedule->recipients);

        $subject = 'Dnevni izveštaj ' . date('d.m.Y H:i', strtotime($to));
        $body = '<h1>Dnevni izveštaj za ' . date('d.m.Y H:i', strtotime($to)) . '</h1>';

        $attachment = self::createExcel($from, $to);

        $from = array(
            'email' => 'wtc3@schenker.co.rs',
            'name' => 'WTC3 Dnevni izvestaj'
        );
          Email::send($subject, $body, $email, $attachment, $from);


    }

    private static function createExcel($from, $to)
    {


        /** FIRST SHEET START */


        $model = SlocHasActivityPalett::model()->findAll();
        $paletts_has_products = array();
        foreach ($model as $sloc_has_activity_palett) {
            $activity_palett_has_products = ActivityPalettHasProduct::model()->findAllByAttributes(array('activity_palett_id' => $sloc_has_activity_palett->activity_palett_id));


            foreach ($activity_palett_has_products as $activity_palett_has_product) {
                if ($activity_palett_has_product->product && Pick::model()->findByAttributes(array('sscc_source' => $activity_palett_has_product->sscc, 'sscc_destination' => $activity_palett_has_product->sscc, 'pick_type' => 'palett')) == null) {
                    $paletts_has_products[] = $activity_palett_has_product;
                }
            }

        }


        $model = $paletts_has_products;

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Komercijalne zalihe');


        $heading = array('Klijent','SLOC kod', 'SSCC', 'Tip skladištenja', 'Šifra proizvoda', 'Naziv proizvoda', 'Barkod proizvoda', 'Količina', 'Nalog', 'Kreirano', 'Poslednje kretanje');

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
            $sheet->setCellValue($cell, $data->product->client->title);

            $i++;
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

        /** SECOND SHEET START */

        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(1);

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Web zalihe');

        $model = SlocHasProduct::model()->findAll();

        $heading = array('SLOC kod', 'Šifra proizvoda', 'Naziv proizvoda', 'Barkod proizvoda', 'Količina');

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


        /** SECOND SHEET END */


        /** THIRD SHEET START */

        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(2);

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Gate In');


        $sql = 'SELECT activity_palett.id FROM activity_palett WHERE activity_palett.id NOT IN (SELECT DISTINCT activity_palett_id FROM sloc_has_activity_palett) AND activity_palett.direction="in" AND activity_palett.id NOT IN (SELECT activity_palett_id FROM pick WHERE pick_type="palett" AND status=1)';
        $gatein = Yii::app()->db->createCommand($sql)->queryColumn();


        $paletts_has_products = array();

        $activity_palett_has_products = ActivityPalettHasProduct::model()->findAll(array('condition' => 'activity_palett_id IN (' . implode(',', $gatein) . ')'));
        foreach ($activity_palett_has_products as $activity_palett_has_product) {
            if ($activity_palett_has_product->product) {
                $paletts_has_products[] = $activity_palett_has_product;
            }
        }


        $model = $paletts_has_products;


        $heading = array('SSCC', 'Šifra proizvoda', 'Naziv proizvoda', 'Barkod proizvoda', 'Količina', 'Nalog', 'Kreirano', 'Poslednje kretanje');

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


            if ($data->activityPalett->activity->direction == 'out') {
                continue;
            }

            $i = 0;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->activityPalett->sscc);


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

        /** THIRD SHEET END */

        /** FOURTH SHEET START */

        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(3);

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Zauzeti SLOC i SSCC');


        $clients = Client::model()->findAll();

        $model = array();
        foreach ($clients as $client) {





            $sql = 'SELECT DISTINCT sloc_id FROM sloc_has_activity_palett  
    JOIN activity_palett ON sloc_has_activity_palett.activity_palett_id = activity_palett.id 
    JOIN activity_palett_has_product ON activity_palett.id = activity_palett_has_product.activity_palett_id
    JOIN product ON activity_palett_has_product.product_id = product.id
    WHERE product.client_id =' . $client->id . ' AND activity_palett.sscc NOT IN (SELECT DISTINCT sscc_destination FROM pick JOIN activity_order ON pick.activity_order_id = activity_order.id JOIN activity ON activity_order.activity_id = activity.id WHERE pick.pick_type <> "move" AND pick.sscc_destination = pick.sscc_source AND activity.direction="out")';

            $result = Yii::app()->db->createCommand($sql)->queryAll();

            $model[$client->title]['sloc'] = $result ? count($result) : 0;

            $sql = 'SELECT DISTINCT sscc FROM  activity_palett_has_product JOIN product ON activity_palett_has_product.product_id = product.id
    WHERE product.client_id =' . $client->id . ' AND sscc NOT IN (SELECT DISTINCT sscc_destination FROM pick JOIN activity_order ON pick.activity_order_id = activity_order.id JOIN activity ON activity_order.activity_id = activity.id WHERE pick.status=1 AND pick.pick_type <> "move" AND pick.sscc_destination IS NOT NULL AND activity.direction="out")';

            $result = Yii::app()->db->createCommand($sql)->queryAll();
            $model[$client->title]['sscc'] = $result ? count($result) : 0;

            $sql = 'SELECT DISTINCT sloc_id FROM sloc_has_product JOIN product ON sloc_has_product.product_id = product.id WHERE product.client_id =' . $client->id;

            $result = Yii::app()->db->createCommand($sql)->queryAll();
            $model[$client->title]['web'] = $result ? count($result) : 0;

            $model_gate_out = new ActivityPalett('gateOut');
            $sql = 'SELECT DISTINCT sscc_destination FROM pick JOIN activity_order ON pick.activity_order_id = activity_order.id  WHERE pick.status=0 AND pick.pick_type <> "move" AND pick.sscc_destination IS NOT NULL AND  activity_order.client_id=' . $client->id;
            $result = Yii::app()->db->createCommand($sql)->queryColumn();
            $model[$client->title]['gateOut'] = $result ? count($result) : 0;



        }

        foreach ($model as $client => $values) {
            if ($values['sloc'] + $values['sscc'] + $values['web'] + $values['gateOut'] == 0) {
                unset($model[$client]);
            }
        }


        $row = 2;
        $sheet->getColumnDimension('B')->setAutoSize(true);

        foreach ($model as $k => $v) {


            $sheet->setCellValue('B' . $row, 'Broj zauzetih lokacija ' . $k);
            $sheet->setCellValue('C' . $row, $v['sloc']);
            $row++;
            $sheet->setCellValue('B' . $row, 'Broj SSCC kodova ' . $k);
            $sheet->setCellValue('C' . $row, $v['sscc']);
            $row++;
            $sheet->setCellValue('B' . $row, 'WEB zauzete lokacije ' . $k);
            $sheet->setCellValue('C' . $row, $v['web']);
            $row++;
            $sheet->setCellValue('B' . $row, 'Gate Out ' . $k);
            $sheet->setCellValue('C' . $row, $v['gateOut']);

            $row++;
            $row++;

        }

        /** FOURTH SHEET END */

        /** FIFTH SHEET START */

        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(4);

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('IN-OUT performance');

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

        $i = 0;
        $row = 1;

        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, "INBOUND");
        $sheet->getStyle($cell)->applyFromArray($styleArray);
        $sheet->getColumnDimension($letters[$i])->setAutoSize(true);

        $i++;
        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, "PRIMLJENO");
        $sheet->getStyle($cell)->applyFromArray($styleArray);
        $sheet->getColumnDimension($letters[$i])->setAutoSize(true);

        $i++;
        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, "OBRAĐENO");
        $sheet->getStyle($cell)->applyFromArray($styleArray);
        $sheet->getColumnDimension($letters[$i])->setAutoSize(true);

        $row++;
        $i = 0;

        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, "BROJ NALOGA");
        $row++;
        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, "BROJ ARTIKALA");
        $row++;
        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, "BROJ KOMADA");
        $row++;
        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, "BROJ PALETA");


        /** INBOUND PRIMLJENO */


        $row = 2;
        $i++;

        $sql = 'SELECT COUNT(*) FROM order_client JOIN order_request on order_client.order_request_id = order_request.id WHERE order_request.direction="in" AND order_client.created_dt BETWEEN "' . $from . '" AND "' . $to . '"';

        $orders = Yii::app()->db->createCommand($sql)->queryScalar();
        if (!$orders) {
            $orders = 0;
        }

        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, $orders);


        $row++;

        $sql = 'SELECT DISTINCT product_id FROM order_product JOIN order_client ON order_product.order_client_id = order_client.id JOIN order_request on order_client.order_request_id = order_request.id WHERE order_request.direction="in" AND order_client.created_dt BETWEEN "' . $from . '" AND "' . $to . '"';

        $products = Yii::app()->db->createCommand($sql)->queryAll();
        if ($products) {
            $products = count($products);
        } else {
            $products = 0;
        }
        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, $products);

        $row++;

        $sql = 'SELECT SUM(quantity) FROM order_product JOIN order_client ON order_product.order_client_id = order_client.id JOIN order_request on order_client.order_request_id = order_request.id WHERE order_request.direction="in" AND order_client.created_dt BETWEEN "' . $from . '" AND "' . $to . '"';

        $pcs = Yii::app()->db->createCommand($sql)->queryScalar();
        if (!$pcs) {
            $pcs = 0;
        }
        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, $pcs);


        $row++;


        $sql = 'SELECT SUM(paletts) FROM order_product JOIN order_client ON order_product.order_client_id = order_client.id JOIN order_request on order_client.order_request_id = order_request.id WHERE order_request.direction="in" AND order_client.created_dt BETWEEN "' . $from . '" AND "' . $to . '"';

        $paletts = Yii::app()->db->createCommand($sql)->queryScalar();
        if (!$paletts) {
            $paletts = 0;
        }
        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, $paletts);

        /** INBOUND OBRADJENO */

        $row = 2;
        $i = 2;

        $sql = 'SELECT activity_palett_id FROM accept JOIN activity_palett ON accept.activity_palett_id = activity_palett.id 
    JOIN activity ON activity_palett.activity_id = activity.id 
                WHERE activity.direction = "in" && activity.system_acceptance = 1 && accept.created_dt BETWEEN "' . $from . '" AND "' . $to . '"';

        $activity_palett_ids = Yii::app()->db->createCommand($sql)->queryColumn();

        if (!empty($activity_palett_ids)) {
            $sql = 'SELECT DISTINCT activity_order_id FROM activity_palett WHERE id IN (' . implode(',', $activity_palett_ids) . ')';
            $activity_order_ids = Yii::app()->db->createCommand($sql)->queryColumn();
            if (!empty($activity_order_ids)) {
                $orders = count($activity_order_ids);
            } else {
                $orders = 0;
            }
        } else {
            $orders = 0;
        }

        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, $orders);

        $row++;

        $sql = 'SELECT DISTINCT activity_palett_has_product.product_id FROM activity_palett_has_product 
    JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id 
    JOIN activity ON activity_palett.activity_id = activity.id 
                WHERE activity.direction = "in" AND (activity.system_acceptance = 1 OR activity.truck_dispatch_datetime IS NOT NULL) AND activity.system_acceptance_datetime BETWEEN "' . $from . '" AND "' . $to . '"';
        $result = Yii::app()->db->createCommand($sql)->queryColumn();

        $products = empty($result) ? 0 : count($result);

        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, $products);

        $row++;

        $sql = 'SELECT SUM(activity_palett_has_product.quantity) FROM activity_palett_has_product 
    JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id 
    JOIN activity ON activity_palett.activity_id = activity.id 
                WHERE activity.direction = "in" AND (activity.system_acceptance = 1 OR activity.truck_dispatch_datetime IS NOT NULL) AND  activity.system_acceptance_datetime BETWEEN "' . $from . '" AND "' . $to . '"';
        $result = Yii::app()->db->createCommand($sql)->queryScalar();

        $pcs = $result != NULL ? $result : 0;
        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, $pcs);

        $row++;

        $sql = 'SELECT DISTINCT activity_palett_id FROM activity_palett_has_product 
    JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id 
    JOIN activity ON activity_palett.activity_id = activity.id 
                WHERE activity.direction = "in"  AND activity_palett_has_product.created_dt BETWEEN "' . $from . '" AND "' . $to . '"';

        $paletts = count(Yii::app()->db->createCommand($sql)->queryColumn());

        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, $paletts);


        $row += 2;

        $i = 0;

        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, "OUTBOUND + WEB");
        $sheet->getStyle($cell)->applyFromArray($styleArray);
        $sheet->getColumnDimension($letters[$i])->setAutoSize(true);

        $i++;
        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, "PRIMLJENO");
        $sheet->getStyle($cell)->applyFromArray($styleArray);
        $sheet->getColumnDimension($letters[$i])->setAutoSize(true);

        $i++;
        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, "OBRAĐENI WEB NALOZI)");
        $sheet->getStyle($cell)->applyFromArray($styleArray);
        $sheet->getColumnDimension($letters[$i])->setAutoSize(true);
        $i++;
        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, "OBRAĐENO (KORAK1)");
        $sheet->getStyle($cell)->applyFromArray($styleArray);
        $sheet->getColumnDimension($letters[$i])->setAutoSize(true);

        $i++;
        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, "OTPREMLJENO (KORAK2)");
        $sheet->getStyle($cell)->applyFromArray($styleArray);
        $sheet->getColumnDimension($letters[$i])->setAutoSize(true);

        $i++;
        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, "OTVORENI NALOZI");
        $sheet->getStyle($cell)->applyFromArray($styleArray);
        $sheet->getColumnDimension($letters[$i])->setAutoSize(true);

        $row++;
        $i = 0;

        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, "BROJ NALOGA");
        $row++;
        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, "BROJ ARTIKALA");
        $row++;
        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, "BROJ KOMADA");
        $row++;
        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, "BROJ PALETA");



        /** OUTBOUND PRIMLJENO */
        $row -= 3;
        $i = 1;

        $sql = 'SELECT COUNT(*) FROM order_client JOIN order_request on order_client.order_request_id = order_request.id WHERE order_request.direction="out" AND order_client.created_dt BETWEEN "' . $from . '" AND "' . $to . '"';

        $orders = Yii::app()->db->createCommand($sql)->queryScalar();
        if ($orders == null) {
            $orders = 0;
        }

        $sql = 'SELECT COUNT(*) FROM web_order WHERE created_dt BETWEEN "' . $from . '" AND "' . $to . '"';
        $web_orders = Yii::app()->db->createCommand($sql)->queryScalar();
        if ($web_orders == null) {
            $web_orders = 0;
        }
        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, $orders + $web_orders);


        $row++;

        $sql = 'SELECT DISTINCT product_id FROM order_product JOIN order_client ON order_product.order_client_id = order_client.id JOIN order_request on order_client.order_request_id = order_request.id WHERE order_request.direction="out" AND order_client.created_dt BETWEEN "' . $from . '" AND "' . $to . '"';

        $products = Yii::app()->db->createCommand($sql)->queryAll();
        if ($products) {
            $products = count($products);
        } else {
            $products = 0;
        }

        $sql = 'SELECT DISTINCT product_id FROM web_order_product WHERE created_dt BETWEEN "' . $from . '" AND "' . $to . '"';
        $web_products = Yii::app()->db->createCommand($sql)->queryAll();
        if ($web_products) {
            $web_products = count($web_products);
        } else {
            $web_products = 0;
        }


        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, $products + $web_products);

        $row++;

        $sql = 'SELECT SUM(quantity) FROM order_product JOIN order_client ON order_product.order_client_id = order_client.id JOIN order_request on order_client.order_request_id = order_request.id WHERE order_request.direction="out" AND order_client.created_dt BETWEEN "' . $from . '" AND "' . $to . '"';

        $pcs = Yii::app()->db->createCommand($sql)->queryScalar();
        if (!$pcs) {
            $pcs = 0;
        }

        $sql = 'SELECT SUM(quantity) FROM web_order_product WHERE created_dt BETWEEN "' . $from . '" AND "' . $to . '"';
        $web_pcs = Yii::app()->db->createCommand($sql)->queryScalar();
        if ($web_pcs == null) {
            $web_pcs = 0;
        }
        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, $pcs + $web_pcs);


        $row++;


        $sql = 'SELECT SUM(paletts) FROM order_product JOIN order_client ON order_product.order_client_id = order_client.id JOIN order_request on order_client.order_request_id = order_request.id WHERE order_request.direction="out" AND order_client.created_dt BETWEEN "' . $from . '" AND "' . $to . '"';

        $paletts = Yii::app()->db->createCommand($sql)->queryScalar();
        if (!$paletts) {
            $paletts = 0;
        }
        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, $paletts);

        /*** OUTBOUND OBRADJENO WEB */

        $i = 2;
        $row -= 3;


        $sql = 'SELECT COUNT(*) FROM web_order WHERE status=1 AND updated_dt BETWEEN "' . $from . '" AND "' . $to . '"';
        $web_orders = Yii::app()->db->createCommand($sql)->queryScalar();
        if ($web_orders == null) {
            $web_orders = 0;
        }

        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, $web_orders);


        $row++;

        $sql = 'SELECT DISTINCT product_id FROM pick_web JOIN web_order ON pick_web.web_order_id = web_order.id 
        WHERE  web_order.status = 1 AND web_order.updated_dt BETWEEN "' . $from . '" AND "' . $to . '"';

        $web_products = Yii::app()->db->createCommand($sql)->queryAll();
        if ($web_products) {
            $web_products = count($web_products);
        } else {
            $web_products = 0;
        }
        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, $web_products);

        $row++;


        $sql = 'SELECT SUM(web_order_product.quantity) FROM web_order_product JOIN web_order ON web_order_product.web_order_id = web_order.id WHERE web_order.status=1 AND web_order.updated_dt BETWEEN "' . $from . '" AND "' . $to . '"';
        $sql ='SELECT SUM(quantity) FROM pick_web JOIN web_order ON pick_web.web_order_id = web_order.id 
    WHERE web_order.status=1 AND web_order.updated_dt BETWEEN "' . $from . '" AND "' . $to . '"';

        $web_pcs = Yii::app()->db->createCommand($sql)->queryScalar();
        if (!$web_pcs) {
            $web_pcs = 0;
        }
        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, $web_pcs);





        /*** OUTBOUND OBRADJENO */

        $i = 3;
        $row -= 2;

        $sql = 'SELECT COUNT(*) FROM activity_order JOIN activity ON activity_order.activity_id = activity.id WHERE activity.direction="out" AND activity_order.status=1 AND activity.system_acceptance_datetime BETWEEN "' . $from . '" AND "' . $to . '"';
        $orders = Yii::app()->db->createCommand($sql)->queryScalar();
        if ($orders == null) {

            $orders = 0;
        }

        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, $orders);


        $row++;


        $sql = 'SELECT DISTINCT product_id FROM activity_palett_has_product JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id JOIN activity_order ON activity_palett.activity_order_id = activity_order.id WHERE activity_order.status = 1 AND activity_order.activity_id IN (SELECT id FROM activity WHERE direction="out" AND system_acceptance_datetime BETWEEN "' . $from . '" AND "' . $to . '") ';


        $products = Yii::app()->db->createCommand($sql)->queryAll();
        if ($products) {
            $products = count($products);
        } else {
            $products = 0;
        }
        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, $products);

        $row++;

        $sql = 'SELECT SUM(activity_palett_has_product.quantity) FROM activity_palett_has_product 
    JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id 
    JOIN activity_order ON activity_palett.activity_order_id = activity_order.id 
    WHERE activity_order.status = 1 AND activity_order.activity_id IN (SELECT id FROM activity WHERE direction="out" AND system_acceptance_datetime BETWEEN "' . $from . '" AND "' . $to . '") ';
        $pcs = Yii::app()->db->createCommand($sql)->queryScalar();
        if (!$pcs) {
            $pcs = 0;
        }


        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, $pcs);

        $row++;


        $sql = 'SELECT DISTINCT activity_palett_has_product.activity_palett_id FROM activity_palett_has_product JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id JOIN activity_order ON activity_palett.activity_order_id = activity_order.id WHERE activity_order.status=1 AND activity_order.activity_id IN (SELECT id FROM activity WHERE direction="out" AND system_acceptance_datetime BETWEEN "' . $from . '" AND "' . $to . '") ';
        $paletts = Yii::app()->db->createCommand($sql)->queryAll();
        if ($paletts) {
            $paletts = count($paletts);
        } else {
            $paletts = 0;
        }
        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, $paletts);



        /*** OUTBOUND POSLATI */

        $i = 4;
        $row -= 3;

        $sql = 'SELECT COUNT(*) FROM activity_order JOIN activity ON activity_order.activity_id = activity.id WHERE activity.direction="out" AND activity_order.status=1 AND activity.truck_dispatch_datetime BETWEEN "' . $from . '" AND "' . $to . '"';
        $orders = Yii::app()->db->createCommand($sql)->queryScalar();
        if ($orders == null) {

            $orders = 0;
        }


        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, $orders);


        $row++;

        // $sql = 'SELECT DISTINCT product_id FROM activity_palett_has_product JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id JOIN activity_order ON activity_palett.activity_order_id = activity_order.id WHERE activity_palett_has_product.created_dt BETWEEN "' . $from . '" AND "' . $to . '" AND activity_order.activity_id IN (SELECT id FROM activity WHERE direction="out" AND system_acceptance_datetime BETWEEN "' . $from . '" AND "' . $to . '") ';
        $sql = 'SELECT DISTINCT product_id FROM activity_palett_has_product JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id JOIN activity_order ON activity_palett.activity_order_id = activity_order.id WHERE activity_order.status = 1 AND activity_order.activity_id IN (SELECT id FROM activity WHERE direction="out" AND truck_dispatch_datetime BETWEEN "' . $from . '" AND "' . $to . '") ';


        $products = Yii::app()->db->createCommand($sql)->queryAll();
        if ($products) {
            $products = count($products);
        } else {
            $products = 0;
        }


        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, $products);

        $row++;

        $sql = 'SELECT SUM(activity_palett_has_product.quantity) FROM activity_palett_has_product 
    JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id 
    JOIN activity_order ON activity_palett.activity_order_id = activity_order.id 
    WHERE activity_order.status = 1 AND activity_order.activity_id IN (SELECT id FROM activity WHERE direction="out" AND truck_dispatch_datetime BETWEEN "' . $from . '" AND "' . $to . '") ';
        $pcs = Yii::app()->db->createCommand($sql)->queryScalar();
        if (!$pcs) {
            $pcs = 0;
        }

        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, $pcs);

        $row++;


        $sql = 'SELECT DISTINCT activity_palett_has_product.activity_palett_id FROM activity_palett_has_product JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id JOIN activity_order ON activity_palett.activity_order_id = activity_order.id WHERE activity_order.status=1 AND activity_order.activity_id IN (SELECT id FROM activity WHERE direction="out" AND truck_dispatch_datetime BETWEEN "' . $from . '" AND "' . $to . '") ';
        $paletts = Yii::app()->db->createCommand($sql)->queryAll();
        if ($paletts) {
            $paletts = count($paletts);
        } else {
            $paletts = 0;
        }
        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, $paletts);





        /*** OUTBOUND OTVORENO OBRADJENO */

        $i = 5;
        $row -= 3;

        $sql = 'SELECT COUNT(*) FROM activity_order JOIN activity ON activity_order.activity_id = activity.id WHERE activity.direction="out" AND activity_order.status=0 AND activity_order.created_dt BETWEEN "' . $from . '" AND "' . $to . '"';


        $orders = Yii::app()->db->createCommand($sql)->queryScalar();
        if ($orders == null) {

            $orders = 0;
        }

        $sql = 'SELECT DISTINCT web_order_id FROM pick_web JOIN web_order ON pick_web.web_order_id = web_order_id WHERE web_order.status=0 AND pick_web.created_dt BETWEEN "' . $from . '" AND "' . $to . '"';


        $result = Yii::app()->db->createCommand($sql)->QueryAll();

        if ($result) {

            $web_orders = count($result);
        } else {
            $web_orders = 0;
        }

        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, $orders + $web_orders);


        $row++;


        $sql = 'SELECT DISTINCT product_id FROM activity_palett_has_product JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id JOIN activity_order ON activity_palett.activity_order_id = activity_order.id WHERE activity_palett_has_product.created_dt BETWEEN "' . $from . '" AND "' . $to . '" AND activity_order.activity_id IN (SELECT id FROM activity WHERE direction="out" AND system_acceptance_datetime IS NULL) ';

        $products = Yii::app()->db->createCommand($sql)->queryAll();
        if ($products) {
            $products = count($products);
        } else {
            $products = 0;
        }


        $sql = 'SELECT DISTINCT product_id FROM pick_web JOIN web_order ON pick_web.web_order_id = web_order.id WHERE web_order.status=0 AND pick_web.created_dt BETWEEN "' . $from . '" AND "' . $to . '"';


        $web_products = Yii::app()->db->createCommand($sql)->queryAll();
        if ($web_products) {
            $web_products = count($web_products);
        } else {
            $web_products = 0;
        }

        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, $products + $web_products);

        $row++;


        $sql = 'SELECT SUM(activity_palett_has_product.quantity) FROM activity_palett_has_product 
    JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id 
    JOIN activity_order ON activity_palett.activity_order_id = activity_order.id 
    WHERE activity_palett_has_product.created_dt BETWEEN "' . $from . '" AND "' . $to . '" 
    AND activity_order.activity_id IN (SELECT id FROM activity WHERE direction="out" AND system_acceptance_datetime IS NULL) ';

        $pcs = Yii::app()->db->createCommand($sql)->queryScalar();
        if (!$pcs) {
            $pcs = 0;
        }

        $sql = 'SELECT SUM(quantity) FROM pick_web JOIN web_order ON pick_web.web_order_id = web_order.id WHERE web_order.status=0 AND pick_web.created_dt BETWEEN "' . $from . '" AND "' . $to . '"';

        $web_pcs = Yii::app()->db->createCommand($sql)->queryScalar();
        if (!$web_pcs) {
            $web_pcs = 0;
        }
        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, $pcs + $web_pcs);

        $row++;

        $sql = 'SELECT DISTINCT activity_palett_id FROM activity_palett_has_product JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id JOIN activity_order ON activity_palett.activity_order_id = activity_order.id WHERE activity_palett_has_product.created_dt BETWEEN "' . $from . '" AND "' . $to . '" AND activity_order.activity_id IN (SELECT id FROM activity WHERE direction="out" AND system_acceptance_datetime IS NULL)';
        $paletts = Yii::app()->db->createCommand($sql)->queryAll();
        if ($paletts) {
            $paletts = count($paletts);
        } else {
            $paletts = 0;
        }

        $cell = $letters[$i] . $row;
        $sheet->setCellValue($cell, $paletts);

        /** FIFTH SHEET END */


        /** SIXTH SHEET START */

        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(5);

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Produktivnost');

        $model = User::model()->findAllByAttributes(array('roles' => 'rf_korisnik', 'active' => '1'));


        $heading = array('R. Br.', 'Ime pikera', 'INBOUND broj naloga', 'Broj paleta', 'Inbound artikala', 'Inbound pcs', 'Locirano SSCC', 'OUTBOUND broj naloga', 'Outbound artikala', 'Outbound pcs', 'Broj paleta', 'WEB broj naloga', 'Web artikala', 'Web pcs', 'Broj Paleta', 'Broj kontrolisanih naloga', 'Broj kontrolisanih artikala', 'Broj kontrolisanih pcs', 'Dopuna WEB-a');

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
            $sheet->setCellValue($cell, $data->name);

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->getStatsOrdersInbound($from, $to));

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->getStatsPalettsInbound($from, $to));

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->getStatsProductsInbound($from, $to));

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->getStatsPiecesInbound($from, $to));

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->getStatsLocatedInbound($from, $to));

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->getStatsOrdersOutbound($from, $to));

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->getStatsProductsOutbound($from, $to));

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->getStatsPiecesOutbound($from, $to));

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->getStatsPalettsOutbound($from, $to));

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->getStatsOrdersWeb($from, $to));

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->getStatsProductsWeb($from, $to));

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->getStatsPcsWeb($from, $to));

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, 0);

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->getStatsControlledOrders($from, $to));

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->getStatsControlledProducts($from, $to));

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->getStatsControlledPcs($from, $to));

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->getStatsWebFill($from, $to));


            $row++;
        }


        /** SIXHT SHEET END */

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Dnevni_izvestaj_'.date('Ymd_his').'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');

        $filepath = sys_get_temp_dir() . '/' . 'Dnevni_izvestaj_' . date('Ymd_his') . '.xlsx';

        $writer->save($filepath);

        return $filepath;
    }

    public function actionTest()
    {

        $from = date('Y-m-d 00:00:00');
        $from = date('2022-06-14 00:00:00');

        $to = date('Y-m-d H:i:s');
        $to = date('2022-06-14 22:10:00');

        $sql = 'SELECT SUM(quantity) FROM pick_web JOIN web_order ON pick_web.web_order_id = web_order.id WHERE web_order.status=0 AND pick_web.created_dt BETWEEN "' . $from . '" AND "' . $to . '"';

        $web_pcs = Yii::app()->db->createCommand($sql)->queryScalar();
        if (!$web_pcs) {
            $web_pcs = 0;
        }
        echo $web_pcs;
        die();
    }

}