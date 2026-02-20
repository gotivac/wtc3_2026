<?php
/**************************************************************************************************************
 *                                  Remember search parameters for CGridView
 * 
 * Don't forget to attach behavior to model:
 * 
 * public function behaviors() {

        return array(
            'MySearch' => array(
                'class' => 'application.components.MySearch',
            ),
        );
    }
 * 
 * Call $model->readSearchValues() after $model = new Model('search') if no search parameters in $_GET variable
 * Call $model->saveSearchValues() after reading search parameters from $_GET variable
 * 
 **************************************************************************************** Gotivac 2020-10-08 **/


class MySearch extends CActiveRecordBehavior {

    public function readSearchValues($destroy_values = false) {

        $modelName = get_class($this->owner);

        $attributes = $this->owner->getSafeAttributeNames();


        foreach ($attributes as $attribute) {

            if (null != ($value = Yii::app()->user->getState($modelName . $attribute, null))) {
                
                $this->owner->$attribute = $value;

                if ($destroy_values) {
                    Yii::app()->user->setState($modelName . $attribute, 1, 1);
                }
            }
        }
       
    }
    
    public function readSearchValuesToArray()
    {
        $modelName = get_class($this->owner);

        $attributes = $this->owner->getSafeAttributeNames();


        $result = array();
        
        foreach ($attributes as $attribute) {

            if (null != ($value = Yii::app()->user->getState($modelName . $attribute, null))) {
                
                $result[$attribute] = $value;

                
            }
        }
        
        return $result;
    }

    public function saveSearchValues() {

        $modelName = get_class($this->owner);

        $attributes = $this->owner->getSafeAttributeNames();

        foreach ($attributes as $attribute)
            Yii::app()->user->setState($modelName . $attribute, $this->owner->$attribute);
    }

    public function resetSearchValues()
    {


        $modelName = get_class($this->owner);

        $attributes = $this->owner->getSafeAttributeNames();

        foreach ($attributes as $attribute)
            Yii::app()->user->setState($modelName . $attribute, NULL);

    }

}
