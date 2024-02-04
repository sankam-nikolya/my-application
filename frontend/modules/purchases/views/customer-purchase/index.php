<?php

use purchases\components\statuses\Statuses;
use purchases\models\Purchase;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var purchases\models\searches\CustomerPurchaseSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('purchases', 'My purchases');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="purchase-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="d-grid gap-1 d-md-flex justify-content-md-end mb-2">
        <?= Html::a(
            Yii::t('purchases', 'Create purchase'),
            ['update'],
            ['class' => 'btn btn-success btn-block']
        ) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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
                        ['update', 'id' => $model->id]
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
                'attribute' => 'status',
                'value' => static function (Purchase $model) {
                    return Statuses::getStatusName($model->status);
                },
                'filter' => Statuses::getStatusesList(),
            ],
            'created_at:datetime',
            'updated_at:datetime',
            [
                'class' => ActionColumn::class,
                'urlCreator' => static function ($action, Purchase $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]) ?>

</div>
