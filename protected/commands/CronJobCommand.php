<?php

require Yii::getPathOfAlias('application') . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CronJobCommand extends CConsoleCommand
{
    public function actionTest()
    {
        echo "\nTest successful\n";
    }

    private function runYiicTool($command, $action) {
        $commandPath = Yii::app()->getBasePath() . DIRECTORY_SEPARATOR . 'commands';
        $runner = new CConsoleCommandRunner();
        $runner->addCommands($commandPath);
        $commandPath = Yii::getFrameworkPath() . DIRECTORY_SEPARATOR . 'cli' . DIRECTORY_SEPARATOR . 'commands';
        $runner->addCommands($commandPath);
        $args = array('yiic', $command, $action);
        ob_start();
        $runner->run($args);
        echo htmlentities(ob_get_clean(), null, Yii::app()->charset);
    }

    public function actionSetRealQuantities()
    {
        $start = date('Y-m-d H:i:s');
        $products = Product::model()->findAll(array('condition'=>'id IN (SELECT DISTINCT product_id FROM activity_palett_has_product)'));
        $i=1;
        foreach ($products as $product) {
            $quantity = $product->getTotalQuantity();
            $product_stock = ProductStock::model()->findByAttributes(array('product_id'=>$product->id));
            if ($product_stock) {
                if ($product_stock->quantity != $quantity) {
                    $product_stock->quantity = $quantity;
                    $product_stock->save();
                }
            } else {
                $product_stock = new ProductStock;
                $product_stock->attributes = array(
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                );
                $product_stock->save();
            }
        }

        $end = date('Y-m-d H:i:s');



        echo "****************************\n";
        echo "START: " . $start . "\n";
        echo "END: " . $end . "\n";


    }
    
    public function actionEmailNow()
    {
        $email_nows = EmailNow::model()->findAllByAttributes(array('status'=>0));
        foreach ($email_nows as $email_now) {
            $email_schedule = EmailSchedule::model()->findByPk($email_now->email_schedule_id);
            $this->runYiicTool($email_schedule->command, $email_schedule->action);
            $email_now->status = 1;
            $email_now->save();
        }
    }



    public function actionCheckWebStock()
    {
        $slocs_has_product = SlocHasProduct::model()->findAll();
        $alerts = array();

        foreach ($slocs_has_product as $sloc_has_product) {
            if ($sloc_has_product->realQuantity < 50) {
                $activity_paletts_has_product = ActivityPalettHasProduct::model()->findAllByAttributes(array('product_barcode'=>$sloc_has_product->product_barcode));
                $active_palett_has_product = false;
                foreach ($activity_paletts_has_product as $activity_palett_has_product) {

                    if ($activity_palett_has_product->stockQuantity  > 0 && $activity_palett_has_product->activityPalett->isLocated()) {
                        $active_palett_has_product = $activity_palett_has_product;
                        break;
                    }
                }
                $alerts[] = array(
                    'sloc_code' => $sloc_has_product->sloc_code,
                    'product_code' => $sloc_has_product->product->internal_product_number,
                    'product_barcode' => $sloc_has_product->product->product_barcode,
                    'product_title' => $sloc_has_product->product->title,
                    'quantity' => $sloc_has_product->realQuantity,
                    'available_quantity' => $sloc_has_product->product->getStockQuantity(true),
                    'available_sloc' => $active_palett_has_product ?  $active_palett_has_product->activityPalett->inSloc->sloc_code.'<br>'.$active_palett_has_product->activityPalett->inSloc->sscc : "",

                );
            }
        }

        if (!empty($alerts)) {

            // $email = array('rs.dl.beg.warehouse@dbschenker.com','Nazar.Hamadto@dbschenker.com','milica.vasic@dbschenker.com');

            $email_schedule = EmailSchedule::model()->findByAttributes(array('command'=>'cronjob','action'=>'checkwebstock'));
            $email = explode(',',$email_schedule->recipients);



            $subject = 'Dopuna internet prodaje';
            $body = '<h1>Potrebna dopuna</h1>';
            $body .= '<table border="1" cellpadding="10">';
            $body .= '<tr><th>SLOC</th><th>Kod proizvoda</th><th>Barkod proizvoda</th><th>Naziv proizvoda</th><th>Količina</th><th>Na zalihama</th><th>Dopuni sa</th></tr>';
            foreach ($alerts as $alert) {
                $body .= '<tr><td>'.$alert['sloc_code'].'</td><td>'.$alert['product_code'].'</td><td>'.$alert['product_barcode'].'</td><td>'.$alert['product_title'].'</td><td style="text-align: right">'.$alert['quantity'].'</td><td style="text-align: right">'.$alert['available_quantity'].'</td><td>'.$alert['available_sloc'].'</td></tr>';
            }
            $body .= '</table>';

            $header = array(
                'SLOC',
                'Kod proizvoda',
                'Barkod proizvoda',
                'Naziv proizvoda',
                'Količina',
                'Na zalihama',
                'Dopuni sa',
            );

            $filename = 'Dopuna_web_lokacija_'. date('Ymd_His').'.xlsx';
            $attachment = self::createExcel($header, $alerts, $filename);


            Email::send($subject,$body,$email,$attachment);
        }

    }

    public function actionCheckStock()
    {

        $slocs = Sloc::model()->findAll(array('condition' => 'reserved_product_id IS NOT NULL'));

        $alerts = array();
        foreach ($slocs as $sloc) {
            $product = Product::model()->findByPk($sloc->reserved_product_id);

            if ($product && ($product->stock_minimum != null)) {

                $content_quantity = 0;
                $stock_quantity = 0;
                foreach ($sloc->hasActivityPaletts as $sloc_has_activity_palett) {
                    $activity_palett_has_products = ActivityPalettHasProduct::model()->findAllByAttributes(array('activity_palett_id'=>$sloc_has_activity_palett->activity_palett_id));
                    foreach ($activity_palett_has_products as $activity_palett_has_product) {
                        if ($activity_palett_has_product->product_id == $product->id) {
                            $content_quantity += $activity_palett_has_product->content['quantity'];
                            $stock_quantity += $activity_palett_has_product->stockQuantity;
                        }
                    }
                }

                if ($content_quantity <= $product->stock_minimum) {
                    $activity_paletts_has_product = ActivityPalettHasProduct::model()->findAll(array('condition'=>'product_id = ' . $product->id . ' AND activity_palett_id  NOT IN (SELECT activity_palett_id FROM sloc_has_activity_palett WHERE sloc_id=' . $sloc->id.')'));
                    $active_palett_has_product = false;
                    foreach ($activity_paletts_has_product as $activity_palett_has_product) {

                        if ($activity_palett_has_product->stockQuantity > 0 && $activity_palett_has_product->activityPalett->isLocated()) {
                            $active_palett_has_product = $activity_palett_has_product;
                            break;
                        }
                    }
                    $alerts[] = array(
                        'sloc_code' => $sloc->sloc_code,
                        'product_code' => $product->internal_product_number,
                        'product_barcode' => $product->product_barcode,
                        'product_title' => $product->title,
                        'quantity' => $content_quantity,
                        'available_quantity' => $product->getStockQuantity(true),
                        'available_sloc' => $active_palett_has_product ?  $active_palett_has_product->activityPalett->inSloc->sloc_code.'<br>'.$active_palett_has_product->activityPalett->inSloc->sscc : "",

                    );
                }

            }
        }
        $header = array(
            'SLOC',
            'Kod proizvoda',
            'Barkod proizvoda',
            'Naziv proizvoda',
            'Stvarna Količina',
            'Na zalihama',
            'Dopuni sa',
        );

        // $email = array('rs.dl.beg.warehouse@dbschenker.com','Nazar.Hamadto@dbschenker.com','milica.vasic@dbschenker.com');
        $email_schedule = EmailSchedule::model()->findByAttributes(array('command'=>'cronjob','action'=>'checkstock'));
        $email = explode(',',$email_schedule->recipients);


        $subject = 'Dopuna piking lokacija';
        $body = '<h1>Potrebna dopuna</h1>';
        $body .= '<table border="1" cellpadding="10">';
        $body .= '<tr><th>SLOC</th><th>Kod proizvoda</th><th>Barkod proizvoda</th><th>Naziv proizvoda</th><th>Količina</th><th>Na zalihama</th><th>Dopuni sa</th></tr>';
        foreach ($alerts as $alert) {
            $body .= '<tr><td>'.$alert['sloc_code'].'</td><td>'.$alert['product_code'].'</td><td>'.$alert['product_barcode'].'</td><td>'.$alert['product_title'].'</td><td style="text-align: right">'.$alert['quantity'].'</td><td style="text-align: right">'.$alert['available_quantity'].'</td><td>'.$alert['available_sloc'].'</td></tr>';
        }
        $body .= '</table>';

        $filename = 'Dopuna_piking_lokacija_'. date('Ymd_His').'.xlsx';

        $attachment = self::createExcel($header, $alerts, $filename);


        Email::send($subject,$body,$email,$attachment);
    }

    private static function createExcel($header,$model, $filename)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $heading = $header;

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
            $sheet->setCellValue($cell, $data['sloc_code']);

            $i++;
            $cell = $letters[$i] . $row;
            $spreadsheet->getActiveSheet()
                ->getCell($cell)
                ->setValueExplicit(
                    $data['product_code'],
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2
                );

            $i++;
            $cell = $letters[$i] . $row;
            $spreadsheet->getActiveSheet()
                ->getCell($cell)
                ->setValueExplicit(
                    $data['product_barcode'],
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2
                );

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data['product_title']);

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data['quantity']);

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data['available_quantity']);

            $i++;
            $cell = $letters[$i] . $row;
            $spreadsheet->getActiveSheet()
                ->getCell($cell)
                ->setValueExplicit(
                    str_replace('<br>',' - ' , $data['available_sloc']),
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2
                );

            $row++;

        }


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Dopuna.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');

        $filepath = sys_get_temp_dir().'/'. $filename;

        $writer->save($filepath);

        return $filepath;
    }
}