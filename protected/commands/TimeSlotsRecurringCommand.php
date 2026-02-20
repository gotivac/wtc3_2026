<?php

class TimeSlotsRecurringCommand extends CConsoleCommand {

    public function run($args) {

        for ($i = 1; $i <= 7; $i++) {
            $today = date('Y-m-d', strtotime('+' . $i . ' days'));
            $current_day_of_week = date('w');

            $time_slot_recurring = TimeSlotRecurring::model()->findByAttributes(array(
                'day_of_week' => $current_day_of_week,
            ));

            $days = array('sunday','monday','tuesday','wednesday','thursday','friday','saturday');

            $start_date = date('Y-m-d', strtotime(' next ' . $days[$i-1]));
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
                );
                $time_slot->save();
               
            } 
        }
        echo 'FINISHED!';
    }

}

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

