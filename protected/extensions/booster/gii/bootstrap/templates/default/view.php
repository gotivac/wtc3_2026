<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>
<?php
echo "<?php\n";
$nameColumn = $this->guessNameColumn($this->tableSchema->columns);
$label = $this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	Yii::t('app','$label')=>array('index'),
	\$model->{$nameColumn},
);\n";
?>

$this->menu=array(
array('label' => Yii::t('app', 'List'), 'url' => array('index')),
array('label' => Yii::t('app', 'Update'), 'url' => array('update', 'id' => $model->id)),
);
?>

<div class="alert-placeholder"></div>
<div class="col-md-6">

<?php echo "<?php"; ?> $this->widget('booster.widgets.TbDetailView',array(
'data'=>$model,
'attributes'=>array(
<?php
foreach ($this->tableSchema->columns as $column) {
	echo "\t\t'" . $column->name . "',\n";
}
?>
),
)); ?>
</div>