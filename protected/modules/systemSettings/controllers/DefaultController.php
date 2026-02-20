<?php

class DefaultController extends Controller
{
    public $layout = '//layouts/column1';

    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionAjaxGetSections()
    {
        $location_id = $_POST['location_id'];
        if ($location_id != '') {
            $sections = Section::model()->findAllByAttributes(array('location_id' => $location_id));
        } else {
            $sections = Section::model()->findAll();
        }
        $response = "";
        foreach ($sections as $section) {
            $response .= '<option value="' . $section->id . '">' . $section->title . '</option>';
        }
        echo $response;

    }

    public function actionTimeSlotSettings()
    {

        $time_slot_settings = Settings::model()->findByAttributes(array('controller' => 'timeSlot'));

        if (isset($_POST['TimeSlotSettings'])) {

            $disabled_days = array();
            $disabled_dates = array();
            $settings = $_POST['TimeSlotSettings'];
            if (isset($settings['disabledDays'])) {
                foreach ($settings['disabledDays'] as $k=>$v) {
                    array_push($disabled_days,$k);
                }
            }
            if (isset($settings['disabledDates'])) {
                foreach ($settings['disabledDates'] as $disabled_date) {
                    array_push($disabled_dates,date('Y-m-d',strtotime($disabled_date)));
                }
            }
            $settings['disabledDays'] = $disabled_days;
            $settings['disabledDates'] = $disabled_dates;


            $time_slot_settings->content = json_encode($settings);
            $time_slot_settings->save();
            Yii::app()->user->setFlash('success',Yii::t('app','Saved'));

        }


        $model = json_decode($time_slot_settings->content);
        $this->render('time_slot_settings', array('model' => $model));

    }
}