<?php

class SplitController extends Controller
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

        $picks = Pick::model()->findAllByAttributes(array('status'=>0,'pick_type'=>'move'));
        $sort = array();
        $model = new CArrayDataProvider($picks, array(
            'id' => 'split_picks',
            'keyField' => 'id',
            'sort' => $sort,
            'pagination' => array(
                'pageSize' => 9999,
                'pageVar' => 'page',
            ),
        ));

        $this->render('index', array('model' => $model));

    }

    public function actionLocate($id)
    {
        $pick = Pick::model()->findByPk($id);
        if ($pick === null) {
            throw new CHttpException('500','Sistemska greška. Pick ne postoji.');
        }
        $activity_palett = ActivityPalett::model()->findByAttributes(array('sscc'=>$pick->sscc_destination));
        if ($activity_palett === null) {
            throw new CHttpException('404','Paleta ne postoji.');
        }

        $model = new SlocHasActivityPalett;

        if (isset($_POST['SlocHasActivityPalett'])) {
            $model->attributes = $_POST['SlocHasActivityPalett'];



            if ($model->sscc != $activity_palett->sscc) {
                $model->addError('sscc', 'Pogrešna paleta. Skeniraj SSCC: ' . $activity_palett->sscc);
            } else {
                if ($activity_palett->isLocated()) {
                    $model->addError('sscc', 'Paleta je već locirana na ' . $activity_palett->inSloc->sloc->sloc_code);
                } else {
                    if (count($activity_palett->hasProducts) == 0) {
                        $model->addError('sscc', 'Paleta je prazna.');
                    } else {
                        $sloc = Sloc::model()->findByAttributes(array('sloc_code' => $model->sloc_code));
                        if ($sloc === null) {
                            $model->addError('sloc_code', 'SLOC ne postoji.');
                        } else {

                            $model->activity_palett_id = $activity_palett->id;
                            $model->sloc_id = $sloc->id;
                            if ($model->save()) {
                                $pick->status = 1;
                                $pick->save();

                                Yii::app()->user->setFlash('success', '<b>LOCIRANO</b><br>SSCC: ' . $model->sscc . '<br>' . 'SLOC: ' . $model->sloc_code);


                                $this->redirect(array('located', 'id' => $activity_palett->id));

                            } else {

                                    Yii::app()->user->setFlash('error',CHtml::errorSummary($model));

                            }

                        }
                    }
                }

            }
        }

        $this->render('locate', array(
            'activity_palett' => $activity_palett,
            'model' => $model,
        ));
    }

    public function actionLocated($id)
    {
        $model = ActivityPalett::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException('404', 'Paleta ne postoji.');
        }
        $this->render('located', array('model' => $model));
    }

}