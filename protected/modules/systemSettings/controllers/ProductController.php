<?php

require Yii::getPathOfAlias('application') . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ProductController extends Controller
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

    public function actionAjaxRemoveChild($id)
    {

        if (Yii::app()->request->isPostRequest) {

            $model = ProductHasChild::model()->findByPk($id);
            if ($model) {
                $model->delete();
            }

            if (!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }

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
        $model = Product::model()->findByPk($id);
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
        $model = new Product;

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);


        $package_ids = array();

        if (isset($_POST['Product'])) {

            $model->attributes = $_POST['Product'];

            if (isset($_POST['ProductHasPackage'])) {
                $package_ids = $_POST['ProductHasPackage']['package_id'];

            }
            if ($model->save()) {
                    Yii::app()->db->createCommand('DELETE FROM product_has_package WHERE product_id = ' . $model->id)->execute();
                    foreach ($package_ids as $package_id) {
                        $product_has_package = new ProductHasPackage;
                        $product_has_package->product_id = $model->id;
                        $product_has_package->package_id = $package_id;

                        $product_has_package->save();

                }
                Yii::app()->user->setFlash('success', Yii::t('app', 'Created'));
                $this->redirect(array('update', 'id' => $model->id));
            }
        }

        $model->package_id = $package_ids;

        $this->render('create', array(
            'model' => $model,

            'package_ids' => $package_ids
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

        $package_ids = Yii::app()->db->createCommand('SELECT package_id FROM product_has_package WHERE product_id = ' . $id)->queryColumn();

        if (isset($_POST['Product'])) {
            $model->attributes = $_POST['Product'];
            if (isset($_POST['ProductHasPackage'])) {
                $package_ids = $_POST['ProductHasPackage']['package_id'];

            } else {
                $package_ids = array();

            }
            if ($model->save()) {
                Yii::app()->db->createCommand('DELETE FROM product_has_package WHERE product_id = ' . $model->id)->execute();
                foreach ($package_ids as $package_id) {
                    $product_has_package = new ProductHasPackage;
                    $product_has_package->product_id = $model->id;
                    $product_has_package->package_id = $package_id;

                    $product_has_package->save();

                }
                Yii::app()->user->setFlash('success', Yii::t('app', 'Saved'));
            }
        }
        $product_has_child = new ProductHasChild;
        if (isset($_POST['ProductHasChild'])) {
            $product_has_child->attributes = $_POST['ProductHasChild'];
            if ($product_has_child->save()) {
                $this->redirect(array('update', 'id' => $model->id, 'tab' => 1));
            }
        }

        $product_has_children = new ProductHasChild('search');
        $product_has_children->unsetAttributes();
        $product_has_children->product_id = $id;

        $package_ids = Yii::app()->db->createCommand('SELECT package_id FROM product_has_package WHERE product_id = ' . $model->id)->queryColumn();


        $this->render('update', array(
            'model' => $model,
            'product_has_children' => $product_has_children,
            'product_has_child' => $product_has_child,
            'package_ids' => $package_ids,

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
        $model = new Product('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Product']))
            $model->attributes = $_GET['Product'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'product-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionExcel() {
        $model = Product::model()->findAll();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $heading = array('Naziv', 'External Product Number', 'Internal Product Number', 'Barkod proizvoda', 'Opis', 'Težina', 'Klijent', 'Tip proizvoda', 'Osnovno pakovanje');

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
            $sheet->setCellValue($cell, $data->title);



            $i++;
            $cell = $letters[$i] . $row;
            $spreadsheet->getActiveSheet()
                ->getCell($cell)
                ->setValueExplicit(
                    $data->internal_product_number,
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2
                );

            $i++;
            $cell = $letters[$i] . $row;
            $spreadsheet->getActiveSheet()
                ->getCell($cell)
                ->setValueExplicit(
                    $data->external_product_number,
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2
                );



            $i++;
            $cell = $letters[$i] . $row;


            $spreadsheet->getActiveSheet()
                ->getCell($cell)
                ->setValueExplicit(
                    $data->product_barcode,
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2
                );

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->description);
            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->weight);

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->client->title);

            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->productType ? $data->productType->title : "");
            $i++;
            $cell = $letters[$i] . $row;
            $sheet->setCellValue($cell, $data->defaultPackage ? $data->defaultPackage->title : "");

            $row++;
        }


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="SLOC.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');

    }
}
