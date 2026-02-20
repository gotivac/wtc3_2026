<?php

class ActivityOrderController extends Controller
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
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = ActivityOrder::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function actionResStickers($id)
    {

        $model = $this->loadModel($id);

        if (count($model->activityPaletts) == 0) {
            $this->redirect(array('activity/update', 'id' => $model->activity->id, 'tab' => 2));
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


        foreach ($model->activityPaletts as $activity_palett) {


            $pdf->AddPage();
            $pdf->SetMargins(1, 1, 1);
            $x = 2.5;
            $y = 4;

            $logo_path = Yii::getPathOfAlias("webroot") . '/themes/wtc3/img/logo.jpg';
            $pdf->Image($logo_path, 1, 1, 3, 0.6, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
            $barcode_path = Yii::getPathOfAlias("webroot") . '/barcodes/paletts/' . $activity_palett->sscc;
            if (!is_file($barcode_path)) {
                $activity_palett->createBarcode();
                $barcode_path = Yii::getPathOfAlias("webroot") . '/barcodes/paletts/' . $activity_palett->sscc;
            }

            $pdf->SetFont("freesans", "", 84);
            $pdf->MultiCell(8, 3, substr($activity_palett->sscc, -4), 0, 'C', 0, 0, 6, 1, true);

            $pdf->SetFont("freesans", "", 8);
            $pdf->MultiCell(15.7, 3, date('Ymd\THis'), 0, 'R', 0, 0, $x, $y + 0.2, true);
            $pdf->Image($barcode_path, $x, $y + 0.6, 16, 6, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);

            $pdf->SetFont("freesans", "", 32);
            $pdf->MultiCell(16, 1, $activity_palett->sscc, 0, 'C', 0, 0, $x, $y + 6.6, true);
            $pdf->SetFont("freesans", "", 12);
            $pdf->MultiCell(16, 1, $activity_palett->activity->gate->title . " * " . $activity_palett->activityOrder->order_number, 0, 'C', 0, 0, $x, $y + 8, true);

        }


        $pdf->Output("SSCC_" . $model->order_number . '_' . date('Ymd\THis') . ".pdf", "D");

        /*         * *************************************************************************** */


    }

    public function actionResDeliveryNote($id)
    {
        $model = $this->loadModel($id);
        $pdf = Yii::createComponent('application.extensions.tcpdf.ETcPdf', 'P', 'cm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor("WTC3");
        $pdf->SetTitle("Otpremnica");
        $pdf->SetSubject("Otpremnica");
        $pdf->SetKeywords('');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AliasNbPages();
        $pdf->addPage();
        $pdf->SetMargins(1, 1, 1);
        $pdf = $model->createDeliveryNotice($pdf);

        $pdf->Output("Otpremnica_" . $model->id . '-' . $model->order_number . '-' . date('Y') . ".pdf", "D");
    }

    public function actionResDeliveryNoticeCut($id)
    {
        $model = $this->loadModel($id);
        $loaded_picks = Pick::model()->findAll(array('condition' => 'status = 1 AND activity_order_id = ' . $model->id . ' AND sscc_destination IN (SELECT DISTINCT sscc FROM activity_palett WHERE activity_order_id = ' . $model->id . ')', 'order' => 'updated_dt ASC'));

        $groups = array();
        if (!empty($loaded_picks)) {
            $paletts = array();
            foreach ($loaded_picks as $loaded_pick) {

                if ($loaded_pick->load_group != NULL) {
                    $activity_palett = ActivityPalett::model()->findByAttributes(array('activity_order_id' => $model->id, 'sscc' => $loaded_pick->sscc_destination));
                    if (!in_array($activity_palett->id,$paletts)) {
                        $groups[$loaded_pick->load_group][] = $activity_palett;
                        $paletts[] = $activity_palett->id;
                    }
                }
            }


            /*
            $loading_time = $loaded_picks[0]->updated_dt;

            $sscc = false;

            $i=0;
            foreach ($loaded_picks as $pick) {

                if ($pick->sscc_destination == $sscc) {
                    continue;
                }
                $sscc = $pick->sscc_destination;

                $activity_palett = ActivityPalett::model()->findByAttributes(array('activity_order_id' => $model->id, 'sscc' => $pick->sscc_destination));

                if (empty($groups)) {
                    $groups[$i][] = $activity_palett;
                    continue;
                }
                $to = strtotime($pick->updated_dt);
                $from = strtotime($loading_time);
                $minutes = round(abs($to - $from) / 60, 2);

                if ($minutes > 60) {   // BROJ MINUTA KOJI TREBA DA PRODJE IZMEDJU DVA UTOVARA
                    $i++;


                }
                $groups[$i][] = $activity_palett;
                $loading_time = $pick->updated_dt;

            }*/
        }



        foreach ($groups as $i => $activity_paletts) {
            $cuts[$i] = new CArrayDataProvider($activity_paletts, array(
                'id' => 'provider-'.$i,
                'sort' => array(),
                'pagination' => array(
                    'pageSize' => 9999,
                ),
            ));

        }

        if (isset($_POST['group']) && isset($_POST['group'][0])) {
            $i = $_POST['group'][0];
            $ids = array();
           foreach ($groups[$i] as $activity_palett) {
               $ids[] = $activity_palett->id;
           }

            $pdf = Yii::createComponent('application.extensions.tcpdf.ETcPdf', 'P', 'cm', 'A4', true, 'UTF-8', false);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor("WTC3");
            $pdf->SetTitle("Otpremnica");
            $pdf->SetSubject("Otpremnica");
            $pdf->SetKeywords('');
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->AliasNbPages();
            $pdf->addPage();
            $pdf->SetMargins(1, 1, 1);
            $pdf = $model->createDeliveryNotice($pdf,$ids);

            $pdf->Output("Otpremnica_" . $model->id . '-' . $model->order_number . '-' . date('Y') . ".pdf", "D");
            Yii::app()->end();

        }

        $this->render('delivery_notice_cut', array(
            'model' => $model,
            'cuts' => $cuts ?? array()
        ));
    }


}
