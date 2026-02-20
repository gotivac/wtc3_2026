<?php

class ActivityOrderProductController extends Controller
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
        $model = ActivityOrderProduct::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($id)
    {
        $activity_order = ActivityOrder::model()->findByPk($id);
        if ($activity_order === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        $model = new ActivityOrderProduct;

        $model->activity_order_id = $activity_order->id;
        $model->activity_id = $activity_order->activity->id;

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        if (isset($_POST['ActivityOrderProduct'])) {
            $model->attributes = $_POST['ActivityOrderProduct'];
            if ($model->save()) {
                Yii::app()->user->setFlash('success', Yii::t('app', 'Created'));
                $this->redirect(array('create', 'id' => $id));
            } else {
               // Yii::app()->user->setFlash('error', CHtml::errorSummary($model));
            }
        }

        $products = Product::model()->findAllByAttributes(array('client_id' => $activity_order->client->id));

        $activity_order_products = new ActivityOrderProduct('search');
        $activity_order_products->unsetAttributes();

        $activity_order_products->activity_order_id = $activity_order->id;


        $this->render('create', array(
            'model' => $model,
            'activity_order' => $activity_order,
            'products' => $products,
            'activity_order_products' => $activity_order_products,
        ));
    }

    public function actionAjaxCalcPaletts()
    {
        $product_id = $_POST['product_id'];
        $product = Product::model()->findByPk($product_id);
        if ($product === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        $quantity = $_POST['quantity'] != "" ? $_POST['quantity'] : 0;
        echo json_encode(array('result' => $product->calcPaletts($quantity)));

    }

    public function actionAjaxUpdata()
    {
        $attribute = $_POST['name'];
        $value = $_POST['value'];
        $id = $_POST['pk'];
        $model = $this->loadModel($id);
        $model->$attribute = $value;
        $model->save();
        echo 'ok';

    }

    public function actionResStickers($id)
    {

        $model = $this->loadModel($id);


        if (count($model->activityPaletts) == 0) {
            $model->generatePaletts();
            $model->refresh();
        }

        /*         * *************************************************************************** */
        $pdf = Yii::createComponent('application.extensions.tcpdf.ETcPdf', 'L', 'cm', 'A5', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor("WTC3");
        $pdf->SetTitle("Palettes Stickers");
        $pdf->SetSubject("Palettes");
        $pdf->SetKeywords('');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AliasNbPages();


        $x = 1.5;
        $y = 1;
        $code = 0;
        foreach ($model->activityPaletts as $palett) {
            $pdf->AddPage();
            $pdf->SetFont("freesans", "B", 12);
            $pdf->SetMargins(1, 1, 1);
            $x = 2.5;
            $y = 4;
            $code = 0;
            /*
            $width = Yii::app()->params['barcode']['width'];
            $height = Yii::app()->params['barcode']['height'];
            $quality = Yii::app()->params['barcode']['quality'];
            $text = Yii::app()->params['barcode']['text'];
            */

            $logo_path = Yii::getPathOfAlias("webroot") . '/themes/wtc3/img/logo.jpg';
            $pdf->Image($logo_path, 6, 1, 9, 2, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);


            $barcode_path = Yii::getPathOfAlias("webroot") . '/barcodes/paletts/' . $palett->sscc;

            if (!is_file($barcode_path)) {
                $palett->createBarcode();
                $barcode_path = Yii::getPathOfAlias("webroot") . '/barcodes/paletts/' . $palett->sscc;

            }


            $pdf->Image($barcode_path, $x, $y + 0.6, 16, 5, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
            $product_code = $palett->activityOrderProduct && $palett->activityOrderProduct->product ? $palett->activityOrderProduct->product->internal_product_number : 'N/A';
            $pdf->MultiCell(5, 1, 'Product: ' . $product_code, 0, 'L', 0, 0, $x + 0.5, $y, true);
            $pdf->MultiCell(3, 1, 'Qty: ' . $palett->quantity, 0, 'L', 0, 0, $x + 5.5, $y, true);
            $pdf->MultiCell(3, 1, 'Packs: ' . $palett->packages, 0, 'L', 0, 0, $x + 8.5, $y, true);
            $pdf->MultiCell(5, 1, 'Weight: ' . $palett->total_weight . ' kg', 0, 'R', 0, 0, $x + 10.5, $y, true);
            $pdf->SetFont("freesans", "", 12);
            $pdf->MultiCell(16, 3, $palett->sscc, 0, 'C', 0, 0, $x, $y + 5.6, true);
            $pdf->SetFont("freesans", "B", 12);


        }
        $pdf->Output("Palett_stickers_" . date('Ymd') . ".pdf", "D");
        /*         * *************************************************************************** */


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

        if (isset($_POST['ActivityOrderProduct'])) {
            $model->attributes = $_POST['ActivityOrderProduct'];
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
        $model = new ActivityOrderProduct('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ActivityOrderProduct']))
            $model->attributes = $_GET['ActivityOrderProduct'];

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
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'activity-order-product-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
