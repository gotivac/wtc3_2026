<?php

class CronJobs extends CApplicationComponent {

    public function check() {
        $cron_jobs = Cron::model()->findAll();
        foreach ($cron_jobs as $job) {
            if ($job->executed_date != date('Y-m-d')) {
                $action = $job->action;
                self::$action();
                $job->executed_date = date('Y-m-d');
                $job->executed_time = date('H:i:s');
                $job->executed_dt = date('Y-m-d H:i:s');
                $job->save();
            }
        }
    }

    public static function TimeSlotsRecurring() {
        
        for ($i = 1; $i <= 7; $i++) {
            $today = date('Y-m-d', strtotime('+' . $i . ' days'));
            $current_day_of_week = date('w',strtotime($today));
            
            $time_slot_recurrings = TimeSlotRecurring::model()->findAllByAttributes(array(
                'day_of_week' => $current_day_of_week,
            ));
             $days = array('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');
            
           

            $start_date = date('Y-m-d', strtotime(' next ' . $days[$current_day_of_week]));

            foreach ($time_slot_recurrings as $time_slot_recurring) {
                if (!TimeSlot::model()->isOverlap($time_slot_recurring->gate_id, $start_date, $time_slot_recurring->start_time, $time_slot_recurring->end_time)) {

                    $time_slot = new TimeSlot;
                    $time_slot->attributes = array(
                        'supplier_id' => $time_slot_recurring->supplier_id,
                        'location_id' => $time_slot_recurring->location_id,
                        'gate_id' => $time_slot_recurring->gate_id,
                        'license_plate' => $time_slot_recurring->license_plate,
                        'direction' => $time_slot_recurring->direction,
                        'start_date' => $start_date,
                        'start_time' => $time_slot_recurring->start_time,
                        'end_date' => $start_date,
                        'end_time' => $time_slot_recurring->end_time,
                        'palletes_announced' => 0, // Sasa potvrdio
                        'request_accepted' => date('Y-m-d H:i:s'),
                        'notes' => 'Rezervisani time slot',
                        'created_user_id' => 30,
                    );
                    $time_slot->save();
                }
            }
        }
       
    }

}
