<?php
require Yii::getPathOfAlias('application') . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ClientCommand extends CConsoleCommand
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
        $sheetnames = ['Worksheet 1'];

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
           $client = new Client;
           $client->attributes = array(
               'title' => $row[2],
               'official_title' => $row[2],
               'location_id' => 4,
               'section_id' => 8,
               'postal_code' => $row[4],
               'city' => $row[5],
               'address' => $row[3],
               'country' => $row[6],
               'phone' => $row[7],
               'client_type_id' => 1,

           );
           if ($client->save()) {
               $clientHasSection = new ClientHasSection;
               $clientHasSection->attributes = array(
                   'client_id' => $client->id,
                   'section_id' => 8,
               );
               $clientHasSection->save();
           }

       }

    }

    public function actionAddClientToSupplier()
    {
        $clients = Client::model()->findAll();
        foreach ($clients as $client) {
            if ($client->id <=2816 ) {
                continue;
            }

            $client_has_supplier = new ClientHasSupplier;
            $client_has_supplier->attributes = array(
                'client_id' => $client->id,
                'supplier_id' => 2815,

            );
            if ($client_has_supplier->save()) {
                echo $client->title . "\n";
            }

        }
    }
}