<?php

require Yii::getPathOfAlias('application') . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SlocCommand extends CConsoleCommand
{

    public $user;

    public function __construct()
    {
        $this->user = User::model()->findByPk(1);
    }

    public function actionImport($filename)
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

        $rows = array_slice($worksheets[0], 3);

        $i=1;
        foreach ($rows as $row) {

            $location = Location::model()->findByAttributes(array('title' => $row[9]));
            $section = Section::model()->findByAttributes(array('title' => $row[10]));

            for ($c = 12; $c <= 17; $c++) {
                $model = new Sloc;
                $model->attributes = array(
                    'location_id' => $location->id,
                    'sloc_type_id' => 5,
                    'section_id' => $section->id,
                    'sloc_code' => $row[$c],
                    'sloc_street' => $row[0],
                    'sloc_field' => $row[1],
                    'sloc_position' => $row[2],
                    'sloc_vertical' => ($c - 12) . '0',
                );

                if (!$model->save()) {
                    var_dump($model->getErrors());
                    die();
                }


            }

            if (!$location || !$section) {
                die($row[9]);
            }

        }


    }

    public function actionVelux($filename)
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


        // $rows = array_slice($worksheets[0], 3);
        $rows = $worksheets[0];


       foreach ($rows as $row) {
           $code = $row[0];
           if (substr($code,0,1) != 'A') {
               continue;
           }
           if ($code == 'A01011010' || $code == 'A01011000' )
           {
               continue;
           }

           $sloc = new Sloc;
           $sloc->setAttributes([
               'sloc_type_id' => 1,
               'section_id' => 8,
               'location_id' => 4,
               'sloc_code' => $code,

           ]);
           $sloc->save();

       }

    }

    public function actionVeluxBlock($filename) {
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


        // $rows = array_slice($worksheets[0], 3);
        $rows = $worksheets[0];

        foreach($rows as $row) {
            $code = $row[2];
            if (substr($code,0,3) != 'BL1') {
                continue;
            }

            $sloc = new Sloc;
            $sloc->setAttributes([
                'sloc_type_id' => 3,
                'section_id' => 8,
                'location_id' => 4,
                'sloc_code' => $code,

            ]);
            $sloc->save();
        }
    }

}