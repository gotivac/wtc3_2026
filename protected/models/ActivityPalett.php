<?php

/**
 * This is the model class for table "activity_palett".
 *
 * The followings are the available columns in table 'activity_palett':
 * @property integer $id
 * @property integer $activity_id
 * @property integer $activity_order_id
 * @property string $sscc
 * @property string $weight
 * @property string $total_weight
 * @property string $direction
 * @property integer $deleted
 * @property integer $created_user_id
 * @property string $created_dt
 * @property integer $updated_user_id
 * @property string $updated_dt
 *
 * The followings are the available model relations:
 * @property Activity $activity
 * @property ActivityOrder $activityOrder
 */
class ActivityPalett extends CActiveRecord
{
    public $location = false;
    public $ids_containing_product = false;

    public static function newSSCC()
    {

        $prefix = '04066397';
        $sql = 'SELECT MAX(SUBSTR(sscc, 9,9)) FROM activity_palett';
        $current = Yii::app()->db->createCommand($sql)->queryScalar();
        if ($current === null) {
            $serial = 100000000;
        } else {
            $serial = $current + 1;
        }

        $sscc = $prefix . $serial;
        $sscc_array = str_split($sscc);
        $odd = true;
        $sum = 0;
        foreach ($sscc_array as $n) {
            if ($odd) {
                $sum += $n * 3;
            } else {
                $sum += $n;
            }
            $odd = !$odd;
        }

        $multiple = $sum;
        for ($i = 0; $i <= 9; $i++) {
            if (($multiple + $i) % 10 == 0) {
                $multiple = $multiple + $i;
                break;
            }
        }
        $control_digit = $multiple - $sum;

        $sscc = $prefix . $serial . $control_digit;
        return $sscc;
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'activity_palett';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('activity_id, activity_order_id', 'required'),
            array('activity_id, activity_order_id, deleted, created_user_id, updated_user_id', 'numerical', 'integerOnly' => true),
            array('weight, total_weight, direction', 'length', 'max' => 10),
            array('sscc', 'length', 'max' => 18),
            array('created_dt, updated_dt', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, activity_id, activity_order_id, sscc,  weight, total_weight, direction, deleted, created_user_id, created_dt, updated_user_id, updated_dt', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'activity' => array(self::BELONGS_TO, 'Activity', 'activity_id'),
            'activityOrder' => array(self::BELONGS_TO, 'ActivityOrder', 'activity_order_id'),
            'hasProducts' => array(self::HAS_MANY, 'ActivityPalettHasProduct', 'activity_palett_id'),
            'inSloc' => array(self::HAS_ONE, 'SlocHasActivityPalett', 'activity_palett_id'),


        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('app', 'ID'),
            'activity_id' => Yii::t('app', 'Activity'),
            'activity_order_id' => Yii::t('app', 'Activity Order'),

            'sscc' => Yii::t('app', 'SSCC'),

            'weight' => Yii::t('app', 'Weight'),
            'total_weight' => Yii::t('app', 'Total Weight'),

            'created_user_id' => Yii::t('app', 'Created User'),
            'created_dt' => Yii::t('app', 'Created Dt'),
            'updated_user_id' => Yii::t('app', 'Updated User'),
            'updated_dt' => Yii::t('app', 'Updated Dt'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('activity_id', $this->activity_id);
        $criteria->compare('activity_order_id', $this->activity_order_id);

        $criteria->compare('sscc', $this->sscc, true);
        $criteria->compare('weight', $this->weight, true);
        $criteria->compare('total_weight', $this->total_weight, true);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('created_dt', $this->created_dt, true);
        $criteria->compare('updated_user_id', $this->updated_user_id);
        $criteria->compare('updated_dt', $this->updated_dt, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 999,
            ),
            'sort' => array(
                'defaultOrder' => 'activity_order_id, sscc',
            )
        ));
    }

    public function gateIn()
    {

        $criteria = new CDbCriteria;
        $sql = 'SELECT id FROM activity WHERE direction="in"';
        if ($this->location) {
            $sql .= ' AND location_id = ' . $this->location->id;
        }
        $activity_ids = Yii::app()->db->createCommand($sql)->queryColumn();
        /*
        if (count($activity_ids) > 0) {
            $criteria->addInCondition('activity_id', $activity_ids);
        } else {
            $criteria->compare('activity_id', 0);
        }
*/
//        $sql = 'SELECT id FROM activity_palett WHERE id NOT IN (SELECT activity_palett_id FROM sloc_has_activity_palett) AND id IN (SELECT activity_palett_id FROM activity_palett_has_product)';

        $sql = 'SELECT activity_palett.id FROM activity_palett JOIN activity ON activity_palett.activity_id = activity.id WHERE activity_palett.id NOT IN (SELECT activity_palett_id FROM sloc_has_activity_palett) AND activity_palett.id IN (SELECT activity_palett_id FROM activity_palett_has_product) AND activity_palett.sscc NOT IN (SELECT sscc_destination FROM pick WHERE sscc_destination IS NOT NULL AND pick_type<>"move" AND activity_order_id IS NOT NULL) AND activity.direction = "in"';
        $gatein = Yii::app()->db->createCommand($sql)->queryColumn();



        if (!empty($gatein)) {
            if ($this->ids_containing_product) {
                $activity_palett_ids = array_intersect($gatein, $this->ids_containing_product);
                $criteria->addInCondition('id', $activity_palett_ids);

            } else {
                $criteria->addInCondition('id', $gatein);
            }


        } else {

            $criteria->compare('id', 0);
        }


        $criteria->compare('activity_order_id', $this->activity_order_id);

        $criteria->compare('sscc', $this->sscc, true);
        $criteria->compare('weight', $this->weight, true);
        $criteria->compare('total_weight', $this->total_weight, true);

        $criteria->compare('DATE_FORMAT(created_dt,"%d.%m.%Y")', $this->created_dt, true);


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 999,
            ),
            'sort' => array(
                'defaultOrder' => 'activity_order_id, sscc',
            )
        ));
    }

    public function accepted()
    {
        return Accept::model()->findAllByAttributes(array('status' => 0));
        /*
        $accepted_ids = Accept::model()->findAllByAttributes(array('status'=>0));

        if (count($accepted_ids) > 0) {
            return $this::model()->findAll(array('condition' => 'id IN (' . implode(',',$accepted_ids) . ')'));
        }
        return null;
        */
    }

    public function gateOut()
    {

        $criteria = new CDbCriteria;
        $sql = 'SELECT id FROM activity WHERE direction="out"';
        if ($this->location) {
            $sql .= ' AND location_id = ' . $this->location->id;
        }
        $activity_ids = Yii::app()->db->createCommand($sql)->queryColumn();
/*
        if (count($activity_ids) > 0) {
            $criteria->addInCondition('activity_id', $activity_ids);
        } else {
            $criteria->compare('activity_id', 0);
        }
*/
        $picked_ids = $this->pickedOnIds();

        if (!empty($picked_ids)) {
            if ($this->ids_containing_product) {
                $picked_ids = array_intersect($picked_ids, $this->ids_containing_product);
            }

            $criteria->addInCondition('id', $picked_ids);
        } else {
            $criteria->compare('id', 0);
        }


        $criteria->compare('activity_order_id', $this->activity_order_id);

        $criteria->compare('sscc', $this->sscc, true);
        $criteria->compare('weight', $this->weight, true);
        $criteria->compare('total_weight', $this->total_weight, true);

        $criteria->compare('DATE_FORMAT(created_dt,"%d.%m.%Y")', $this->created_dt, true);


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 999,
            ),
            'sort' => array(
                'defaultOrder' => 'activity_order_id, sscc',
            )
        ));
    }

    public function picked()
    {
        $picked_ids = Yii::app()->db->createCommand('SELECT activity_palett_id FROM pick WHERE status=0 AND pick_type <> "move" AND sscc_destination IS NOT NULL')->queryColumn();
        if (count($picked_ids) > 0) {

            return $this::model()->findAll(array('condition' => 'id IN (' . implode(',', $picked_ids) . ')'));
        }
        return null;
    }

    public function pickedOn()
    {

        $picked_ssccs = Yii::app()->db->createCommand('SELECT DISTINCT sscc_destination FROM pick WHERE status=0 AND pick_type <> "move" AND sscc_destination IS NOT NULL')->queryColumn();

        if (count($picked_ssccs) > 0) {
            $result = $this->findAll(array('condition' => 'sscc IN (SELECT DISTINCT sscc_destination FROM pick WHERE status=0 AND pick_type <> "move" AND sscc_destination IS NOT NULL) AND activity_id IN (SELECT id FROM activity WHERE direction="out")'));
            return $result;
        }
        return null;
    }

    public function pickedOnIds()
    {




        $sql = 'SELECT activity_palett.id, activity_palett.activity_order_id, activity_palett.sscc FROM activity_palett JOIN activity ON activity_palett.activity_id = activity.id WHERE activity.direction="out"';
        $activity_paletts = Yii::app()->db->createCommand($sql)->queryAll();
        $ids = array();
        foreach ($activity_paletts as $activity_palett) {
            $sql = 'SELECT DISTINCT sscc_destination FROM pick WHERE activity_order_id = ' . $activity_palett['activity_order_id'] . ' AND sscc_destination = "' . $activity_palett['sscc'] . '" AND status=0 AND pick_type<>"move"';
            $picks = Yii::app()->db->createCommand($sql)->queryAll();
            if (count($picks) > 0) {
                $ids[] = $activity_palett['id'];
            }
        }

        return $ids;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ActivityPalett the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function beforeSave()
    {
        if ($this->isNewRecord) {
            $this->created_user_id = Yii::app()->user->id;
            $this->created_dt = date('Y-m-d H:i:s');

            $activity = Activity::model()->findByPk($this->activity_id);
            if ($activity) {
                $this->direction = $activity->direction;
            }
        } else {
            $this->updated_user_id = Yii::app()->user->id;
            $this->updated_dt = date('Y-m-d H:i:s');
        }
        return parent::beforeSave();
    }

    public function afterSave()
    {
        Yii::app()->Helpers->saveLog($this);
        return parent::afterSave();
    }

    public function beforeDelete()
    {

        if ($this->direction == 'in') {
            $sloc_has_activity_palett = SlocHasActivityPalett::model()->findByAttributes(array('activity_palett_id' => $this->id));
            if ($sloc_has_activity_palett) {
                $sloc_has_activity_palett->delete();
            }
            $this->deleted = 1;
            $this->save();
            return false;
        }
        $copy = $this;
        $copy->scenario = 'delete';
        Yii::app()->Helpers->saveLog($copy);

        return parent::beforeDelete();
    }

    public function afterFind()
    {
        $this->location = $this->activity->location;
        return parent::afterFind();
    }

    public function printSSCC()
    {
        /*         * *************************************************************************** */
        $pdf = Yii::createComponent('application.extensions.tcpdf.ETcPdf', 'L', 'cm', 'A5', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor("WTC3");
        $pdf->SetTitle("SSCC");
        $pdf->SetSubject("SSCC");
        $pdf->SetKeywords('');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetMargins(1, 1, 1);
        $x = 2.5;
        $y = 4;

        $logo_path = Yii::getPathOfAlias("webroot") . '/themes/wtc3/img/logo.jpg';
        $pdf->Image($logo_path, 1, 1, 3, 0.6, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
        $barcode_path = Yii::getPathOfAlias("webroot") . '/barcodes/paletts/' . $this->sscc;
        if (!is_file($barcode_path)) {
            $this->createBarcode();
            $barcode_path = Yii::getPathOfAlias("webroot") . '/barcodes/paletts/' . $this->sscc;
        }

        $pdf->SetFont("freesans", "", 84);
        $pdf->MultiCell(8, 3, substr($this->sscc, -4), 0, 'C', 0, 0, 6, 1, true);

        $pdf->SetFont("freesans", "", 8);
        $pdf->MultiCell(15.7, 3, date('Ymd\THis'), 0, 'R', 0, 0, $x, $y + 0.2, true);
        $pdf->Image($barcode_path, $x, $y + 0.6, 16, 6, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
        $pdf->SetFont("freesans", "", 32);
        $pdf->MultiCell(16, 1, $this->sscc, 0, 'C', 0, 0, $x, $y + 6.6, true);

        $pdf->SetFont("freesans", "", 12);
        $pdf->MultiCell(16, 0.5, $this->activity->gate->title . " * " . $this->activityOrder->order_number, 0, 'C', 0, 0, $x, $y + 8, true);


        $pdf->Output("SSCC_" . $this->sscc . '_' . date('Ymd\THis') . ".pdf", "D");

    }

    public function createBarcode()
    {

        $width = 1600;
        $height = 800;
        $quality = Yii::app()->params['barcode']['quality'];
        $text = 0;

        $location = Yii::getPathOfAlias("webroot") . '/barcodes/paletts/' . $this->sscc;
        barcode::Barcode39($this->sscc, $width, $height, $quality, $text, $location);

    }

    public function isScanned()
    {
        return count($this->hasProducts) > 0;
    }

    public function isLocated()
    {
        return $this->inSloc !== null;
    }

    public function isLoaded()
    {

        return Pick::model()->findByAttributes(array('sscc_destination' => $this->sscc, 'status' => 1, 'activity_order_id' => $this->activity_order_id)) != null;
    }

    public function located()
    {
        $located_ids = Yii::app()->db->createCommand('SELECT activity_palett_id FROM sloc_has_activity_palett')->queryColumn();
        if (count($located_ids) > 0) {
            return $this::model()->findAll(array('condition' => 'id IN (' . implode(',', $located_ids) . ')'));
        }
        return null;
    }

    public function unlocated()
    {
        $located_ids = Yii::app()->db->createCommand('SELECT activity_palett_id FROM sloc_has_activity_palett')->queryColumn();
        $picked_ids = Yii::app()->db->createCommand('SELECT activity_palett_id FROM pick WHERE activity_palett_id IS NOT NULL')->queryColumn();

        $located_ids = array_merge($located_ids, $picked_ids);

        if (count($located_ids) > 0) {
            return $this::model()->findAll(array('condition' => 'id NOT IN (' . implode(',', $located_ids) . ')'));
        }
        return $this::model()->findAll();
    }

    public function getNetWeight()
    {
        $weight = 0;
        foreach ($this->hasProducts as $activity_palett_has_product) {
            $weight += $activity_palett_has_product->product->weight * $activity_palett_has_product->quantity;
        }
        return $weight;
    }

    public function getBrutoWeight()
    {
        $weight = 25;
        foreach ($this->hasProducts as $activity_palett_has_product) {
            if ($activity_palett_has_product->product) {
                $weight += $activity_palett_has_product->quantity * $activity_palett_has_product->product->weight;
            }
        }
        return $weight;
    }

    public function getPicks()
    {
        return Pick::model()->findAllByAttributes(array('activity_order_id' => $this->activityOrder->id, 'sscc_destination' => $this->sscc));
    }

    public function isPicked()
    {
        return Pick::model()->findAllByAttributes(array('sscc_destination' => $this->sscc, 'pick_type' => 'palett'));
    }

    public function getTotalRealQuantity()
    {
        $quantity = 0;
        foreach ($this->hasProducts as $activity_palett_has_product) {
            $quantity += $activity_palett_has_product->realQuantity;
        }
        return $quantity;
    }

}
