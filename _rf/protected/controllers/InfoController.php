<?php

class InfoController extends Controller
{

    public function actionIndex()
    {

        if (isset($_POST['sloc_barcode']) && $_POST['sloc_barcode'] != '')
        {
            $sloc_barcode = trim($_POST['sloc_barcode']);
            $sloc = Sloc::model()->findByAttributes(array('sloc_code' => $sloc_barcode));
            if ($sloc === null) {
                throw new CHttpException('404','SLOC ne postoji.');
            }
            if (substr($sloc_barcode,0,1) == 'W') {
                $sloc_has_product = new SlocHasProduct('search');
                $sloc_has_product->unsetAttributes();
                $sloc_has_product->sloc_code = $sloc_barcode;

                $sloc_has_activity_palett = false;
            } else {

                $sloc_has_activity_palett = new SlocHasActivityPalett('search');
                $sloc_has_activity_palett->unsetAttributes();
                $sloc_has_activity_palett->sloc_code = $sloc_barcode;

                $sloc_has_product = false;
            }

            $this->render('sloc_barcode',array(
                'sloc' => $sloc,
                'sloc_has_product' => $sloc_has_product,
                'sloc_has_activity_palett'=>$sloc_has_activity_palett,
            ));
            Yii::app()->end();

        }

        if (isset($_POST['sscc_barcode']) && $_POST['sscc_barcode'] != '')
        {
            $sscc_barcode = trim($_POST['sscc_barcode']);
            $activity_palett = ActivityPalett::model()->findByAttributes(array('sscc' => $sscc_barcode,'direction'=>'in'));
            if ($activity_palett === null) {
                throw new CHttpException('404','Paleta ne postoji.');
            }

            $activity_palett_has_product = new ActivityPalettHasProduct('search');
            $activity_palett_has_product->unsetAttributes();
            $activity_palett_has_product->activity_palett_id = $activity_palett->id;

            $this->render('sscc_barcode',array(
                'activity_palett' => $activity_palett,
                'activity_palett_has_product' => $activity_palett_has_product

            ));
            Yii::app()->end();

        }

        if (isset($_POST['product_barcode']) && $_POST['product_barcode'] != '')
        {
            $product_barcode = trim($_POST['product_barcode']);
            $product = Product::model()->findByAttributes(array('product_barcode' => $product_barcode));
            if ($product === null) {
                throw new CHttpException('404','Proizvod ne postoji.');
            }

            $activity_palett_has_product = new ActivityPalettHasProduct('searchPresent');
            $activity_palett_has_product->unsetAttributes();
            $activity_palett_has_product->product_id = $product->id;


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





            $sloc_has_product = new SlocHasProduct('search');
            $sloc_has_product->unsetAttributes();
            $sloc_has_product->product_id = $product->id;



            $this->render('product_barcode',array(
                'product' => $product,
                'activity_palett_has_product' => $activity_palett_has_product,
                'sloc_has_product' => $sloc_has_product,

            ));
            Yii::app()->end();

        }
        $this->render('index');
    }




}