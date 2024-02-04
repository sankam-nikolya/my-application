<?php

use purchases\models\Purchase;
use yii\bootstrap5\Html;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\helpers\StringHelper;

/** @var yii\web\View $this */
/** @var purchases\models\searches\PurchaseSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('purchases', 'Purchases');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="purchase-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p></p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => [
            'class' => 'table-responsive',
        ],
        'columns' => [
            ['class' => SerialColumn::class],

            [
                'attribute' => 'name',
                'format' => 'html',
                'value' => static function (Purchase $model) {
                    return Html::a(
                        $model->name,
                        ['view', 'id' => $model->id]
                    );
                }
            ],
            [
                'attribute' => 'description',
                'value' => static function (Purchase $model) {
                    return StringHelper::truncate($model->description, 100);
                },
            ],
            'budget',
            [
                'attribute' => 'created_by',
                'value' => static function (Purchase $model) {
                    return $model->createdBy->fullName ?? null;
                },
            ],
            'created_at:datetime',
        ],
    ]) ?>


</div>
