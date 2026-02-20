<?php

class RelocateController extends Controller
{

    public function init()
    {
        parent::init();
        if (!in_array('manipulate',$this->user->rf_access)) {
            throw new CHttpException('403','Zabranjen pristup.');
        }


    }
    public function ajaxGetStorageType()
    {
        if (isset($_POST['sscc']) && $_POST['sscc'] != '' && isset($_POST['sloc_code']) && $POST['sloc_code']) {
            $sloc_has_activity_palett = SlocHasActivityPalett::model()->findByAttributes(array('sscc'=>$_POST['sscc'],'sloc_oode'=>$_POST['sloc_code']));
            if ($sloc_has_activity_palett !== null) {
                echo json_encode(array('success'=>1,'storage_type_id'=>$sloc_has_activity_palett->storage_type_id));
                Yii::app()->end();
            }
        }
        echo json_encode(array('success'=>0));

    }
    public function actionIndex()
    {
        $model = new Relocate;

        if (isset($_POST['Relocate'])) {
            $model->attributes = $_POST['Relocate'];

            if ($model->validate()) {
                /*
                $sloc_source = Sloc::model()->findByAttributes(array('sloc_code' => $model->sloc_source));
                if ($sloc_source === null) {

                    $model->addError('sloc_source', 'SLOC ne postoji');
                }
                */
                $sloc_destination = Sloc::model()->findByAttributes(array('sloc_code' => $model->sloc_destination));
                if ($sloc_destination === null) {
                    $model->addError('sloc_destination', 'SLOC ne postoji');
                }
                $activity_palett = ActivityPalett::model()->findByAttributes(array('sscc' => $model->sscc));
                if ($activity_palett === null) {
                    $model->addError('sscc', 'Paleta ne postoji.');
                }

                $sloc_has_activity_palett = SlocHasActivityPalett::model()->findByAttributes(array('activity_palett_id' => $activity_palett->id));
                if ($sloc_has_activity_palett === null) {
                    $model->addError('sscc', 'Paleta nije locirana.');
                }

                if (!$model->hasErrors()) {
                    $sloc_destination_has_activity_palett = new SlocHasActivityPalett;
                    $sloc_destination_has_activity_palett->attributes = $sloc_has_activity_palett->attributes;
                    $sloc_destination_has_activity_palett->sloc_id = $sloc_destination->id;
                    $sloc_destination_has_activity_palett->sloc_code = $sloc_destination->sloc_code;
                    $sloc_destination_has_activity_palett->storage_type_id = $model->storage_type_id;
                    if ($sloc_destination_has_activity_palett->save()) {

                        $sql = 'UPDATE pick SET sloc_id = ' . $sloc_destination->id . ', sloc_code = "' . $sloc_destination->sloc_code . '" WHERE sloc_id=' . $sloc_has_activity_palett->sloc_id . ' AND sloc_code="' . $sloc_has_activity_palett->sloc_code . '" AND quantity=0';
                        Yii::app()->db->createCommand($sql)->execute();

                        $sql = 'UPDATE pick_web SET sloc_id = ' . $sloc_destination->id . ', sloc_code = "' . $sloc_destination->sloc_code . '" WHERE sloc_id=' . $sloc_has_activity_palett->sloc_id . ' AND quantity IS NULL';
                        Yii::app()->db->createCommand($sql)->execute();

                        $sloc_has_activity_palett->delete();

                        Yii::app()->user->setFlash('success','Paleta ' . $model->sscc . ' relocirana sa ' . $model->sloc_source . ' na ' . $model->sloc_destination);
                        $model = new Relocate;
                    } else {
                        Yii::app()->user->setFlash('error',CHtml::errorSummary($sloc_destination_has_activity_palett));
                    }


                }
            }


        }
        $this->render('index', array('model' => $model));

    }

}