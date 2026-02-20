<?php

require Yii::getPathOfAlias('application') . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ProductCommand extends CConsoleCommand
{

    public $user;

    public function __construct()
    {
        $this->user = User::model()->findByPk(1);
    }

    public function actionCheck($filename)
    {
        $start = time();
        $inputFileName = Yii::getPathOfAlias('application') . '/../import/' . $filename;
        $sheetnames = ['Tabelle2'];

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setLoadSheetsOnly($sheetnames);
        $spreadsheets = $reader->load($inputFileName);

        $worksheets = array();
        foreach ($spreadsheets->getWorksheetIterator() as $worksheet) {
            $worksheet_array = $worksheet->toArray();
            array_shift($worksheet_array);
            $worksheets[] = $worksheet_array;
        }

        $rows = array_slice($worksheets[0], 4);

        $i = 1;
        $s = 1;
        $fh = fopen('neslaganja.csv', 'w+');
        foreach ($rows as $row) {

            if ($row[2] == '' || $row[19] == '') {
                echo $i . ". No barcode or code: " . $row[2] . "\n";
                $i++;
                continue;
            }

            $product = Product::model()->find(array('condition' => 'product_barcode = "' . $row[19] . '"'));

            if ($product !== null) {

                if ($product->internal_product_number != trim($row[2])) {

                    $product->internal_product_number = trim($row[2]);
                    $product->external_product_number = trim($row[2]);
                    $product->title = trim($row[0]);
                    if ($product->save()) {
                        fputs($fh, $i . ',' . $row[19] . ',' . $product->internal_product_number . ',' . str_replace(',', ' ', $product->title) . ',' . $row[2] . ',' . str_replace(',', ' ', $row[0]) . "\n");
                        $i++;
                    } else {
                        var_dump($product->getErrors());
                        die();
                    }

                }

            }
        }
        fclose($fh);


    }


    public function actionImport($filename)
    {
        $start = time();
        $inputFileName = Yii::getPathOfAlias('application') . '/../import/' . $filename;
        $sheetnames = ['Tabelle2'];

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setLoadSheetsOnly($sheetnames);
        $spreadsheets = $reader->load($inputFileName);

        $worksheets = array();
        foreach ($spreadsheets->getWorksheetIterator() as $worksheet) {
            $worksheet_array = $worksheet->toArray();
            array_shift($worksheet_array);
            $worksheets[] = $worksheet_array;
        }

        $rows = array_slice($worksheets[0], 4);

        $i = 1;
        $s = 1;
        foreach ($rows as $row) {

            if ($row[0] == '' || $row[19] == '') {
                echo $i . ". No barcode or code: " . $row[2] . "\n";
                $i++;
                continue;
            }

            $product = Product::model()->find(array('condition' => 'internal_product_number = "' . $row[2] . '" OR product_barcode = "' . $row[19] . '"'));

            if ($product !== null) {
                echo $i . ". Duplicate: " . $product->title . "\n";
                $i++;
                continue;
            }


            $package = Package::model()->findByAttributes(array(
                'width' => $row[11],
                'length' => $row[12],
                'height' => $row[13],
                'gross_weight' => $row[16] == null ? $row[14] : $row[14] * $row[16],
                'product_count' => $row[16],
                'load_carrier_count' => $row[18],
            ));

            if ($package === null) {
                $package = new Package;
                $package->attributes = array(
                    'title' => 'KLETT_' . ($row[16] == null ? '' : $row[16]) . '_x_' . ($row[18] == null ? '' : $row[18]) . '_' . ($row[14] * $row[16]),
                    'width' => $row[11],
                    'length' => $row[12],
                    'height' => $row[13],
                    'gross_weight' => $row[16] == null ? $row[14] : $row[14] * $row[16],
                    'product_count' => $row[16] == null ? 1 : $row[16],
                    'load_carrier_count' => $row[18] == null ? 1 : $row[18],
                );

                if (!$package->save()) {
                    var_dump($package->attributes, $package->getErrors());
                    die();
                }

            }


            $product = new Product;
            $product->attributes = array(
                'client_id' => 3,
                'product_type_id' => 4,
                'load_carrier_id' => 1,
                'package_id' => $package->id,
                'external_product_number' => $row[2],
                'internal_product_number' => $row[2],
                'product_barcode' => $row[19],
                'title' => $row[0],
                'description' => $row[4],
                'weight' => $row[14],
            );

            if (!$product->save()) {
                var_dump($product->attributes, $product->getErrors());
                die();
            }

            $product_has_package = new ProductHasPackage;
            $product_has_package->attributes = array(
                'product_id' => $product->id,
                'package_id' => $package->id,
            );

            if (!$product_has_package->save()) {
                var_dump($product_has_package->attributes, $product_has_package->getErrors());
                die();
            }

            echo $s . ". SAVED: " . $product->title . "\n";
            $s++;


        }


    }

    public function actionImportTijana($filename)
    {
        $start = time();
        $inputFileName = Yii::getPathOfAlias('application') . '/../import/' . $filename;
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


        $s = 1;
        $doubled = fopen('dupli.csv', 'w+');
        $errors = fopen('greske.csv', 'w+');
        foreach ($rows as $row) {

            if ($row[1] == '' || $row[2] == '') {
               // echo $s . ". No barcode or code: " . $row[3] . "\n";
                fputs($errors,$s . "., No barcode or code: ," . str_replace(',',' ',$row[3]) . "\n");
                $s++;
                continue;
            }

            $product = Product::model()->find(array('condition' => 'internal_product_number = "' . $row[1] . '" OR product_barcode = "' . $row[2] . '"'));

            if ($product !== null) {
                // echo $s . ". Duplicate: " . $product->title . "\n";
                fputs($doubled,$s . "., Duplicate: ," . str_replace(',',' ',$product->title) . "\n");
                $s++;
                continue;
            }


            $package = Package::model()->findByPk(21);


            $product = new Product;
            $product->attributes = array(
                'client_id' => 3,
                'product_type_id' => 4,
                'load_carrier_id' => 1,
                'package_id' => $package->id,
                'external_product_number' => $row[1],
                'internal_product_number' => $row[1],
                'product_barcode' => $row[2],
                'title' => $row[3],
                'description' => '',
                'weight' => 0,
            );

            if (!$product->save()) {
                var_dump($product->attributes, $product->getErrors());
                die();
            }

                        $product_has_package = new ProductHasPackage;
                        $product_has_package->attributes = array(
                            'product_id' => $product->id,
                            'package_id' => $package->id,
                        );

                        if (!$product_has_package->save()) {
                            var_dump($product_has_package->attributes, $product_has_package->getErrors());
                            die();
                        }
           
            echo $s . ". SAVED: " . $product->title . "\n";


            $s++;
        }


    }

    public function actionImportVelux($filename)
    {
        $start = time();
        $inputFileName = Yii::getPathOfAlias('application') . '/../import/' . $filename;
        $sheetnames = ['Template'];
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setLoadSheetsOnly($sheetnames);
        $spreadsheets = $reader->load($inputFileName);
        $worksheets = array();
        foreach ($spreadsheets->getWorksheetIterator() as $worksheet) {
            $worksheet_array = $worksheet->toArray();
            array_shift($worksheet_array);
            $worksheets[] = $worksheet_array;
        }
        foreach ($worksheets[0] as $row) {
            $product = new Product;
            $product->attributes = array(
                'client_id' => 2815,
                'product_type_id' => 7,
                'load_carrier_id' => 1,
                'package_id' => 2502,
                'external_product_number' => $row[0],
                'internal_product_number' => $row[0],
                'product_barcode' => $row[4],
                'title' => $row[1],
                'description' => $row[2],
                'weight' => $row[10],
                'length' => $row[12],
                'width' => $row[13],
                'height' => $row[14],
            );
            if ($product->save()) {
                $product_has_package = new ProductHasPackage;
                $product_has_package->attributes = array(
                    'product_id' => $product->id,
                    'package_id' => $product->package_id,
                );
                $product_has_package->save();
            } else {
                echo "\n Product not saved: {$product->product_barcode} \n";
                var_dump($product->getErrors());
            }

        }
    }

    public function actionCheckDuplicates($filename) {
        $start = time();
        $inputFileName = Yii::getPathOfAlias('application') . '/../import/' . $filename;
        $sheetnames = ['Template'];
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setLoadSheetsOnly($sheetnames);
        $spreadsheets = $reader->load($inputFileName);
        $worksheets = array();
        foreach ($spreadsheets->getWorksheetIterator() as $worksheet) {
            $worksheet_array = $worksheet->toArray();
            array_shift($worksheet_array);
            $worksheets[] = $worksheet_array;
        }

        $list = array();
        $duplicates = array();

        foreach ($worksheets[0] as $row) {
            if (in_array($row[4], $list)) {
                $duplicates[] = $row[4].' - '.$row[0];
            } else {
                $list[] = $row[4];
            }
        }

        foreach ($duplicates as $duplicate) {
            echo $duplicate . "\n";
        }
    }

}