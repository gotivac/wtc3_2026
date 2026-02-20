<?php

Yii::import('zii.widgets.grid.CGridColumn');

class TotalColumn extends CGridColumn {

    private $_total = 0;
    private $_attr  = null;

    public function getAttribute()
    {
        return $this->_attr;
    }
    public function setAttribute($value)
    {
        $this->_attr = $value;
    }

    public function renderDataCellContent($row, $data) {
        if (is_object($data)) {
            $this->_total += $data->{$this->attribute};
        } else {
            $this->_total += $data[$this->attribute];
        }

        echo $this->_total;
    }
}