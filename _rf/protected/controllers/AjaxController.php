<?php

class AjaxController extends Controller {

    public function actionAddToDate()
    {
        $date = $_POST['date'];
        $days = $_POST['days'];

        $new_date = date('Y-m-d',strtotime($date.'+'.$days.' days'));
        echo $new_date;
    }
}
