<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>
<?php
echo "<?php\n";
$label = $this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	Yii::t('app','$label')=>array('index'),
	Yii::t('app', 'List'),
);\n";
?>

$this->menu=array(
array('label' => Yii::t('app', 'Create'), 'url' => array('create')),
);

?>


<?php echo "<?php"; ?> $this->widget('booster.widgets.TbGridView',array(
'id'=>'<?php echo $this->class2id($this->modelClass); ?>-grid',
'dataProvider'=>$model->search(),
'summaryText' => Yii::t('app', 'Showing {start} - {end} of {count}'),
'pager' => array('class' => 'CLinkPager', 'header' => '', 'nextPageLabel' => Yii::t('app', "Next"), 'prevPageLabel' => Yii::t('app', 'Previous')),
'filter'=>$model,
'columns'=>array(
<?php
$count = 0;
foreach ($this->tableSchema->columns as $column) {
	if (++$count == 7) {
		echo "\t\t/*\n";
	}
	echo "\t\t'" . $column->name . "',\n";
}
if ($count >= 7) {
	echo "\t\t*/\n";
}
?>
array(
'htmlOptions' => array('nowrap'=>'nowrap'),
'template' => '{view} {update} {delete}',
'class'=>'booster.widgets.TbButtonColumn',
'buttons' => array(
'view' => array(
'label' => Yii::t('app','View'),
'options' => array(
'class' => 'btn btn-xs view'
)
),
'update' => array(
'label' => Yii::t('app','Update'),
'options' => array(
'class' => 'btn btn-xs update'
)
),
'delete' => array(
'label' => Yii::t('app','Delete'),
'options' => array(
'class' => 'btn btn-xs delete'
),
'visible' => (Yii::app()->params['adminDelete']),
)
),
),
),
)); ?>
