<?php

use purchases\components\statuses\Statuses;
use purchases\models\Purchase;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var purchases\models\Purchase $model */
/** @var purchases\models\searches\PurchaseNomenclatureSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('purchases', 'My purchases'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="purchase-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
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
            'description:ntext',
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
        ],
    ]) ?>

    <h2><?= Yii::t('purchases', 'Nomenclature') ?></h2>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'description',
            'qty',
            'units',
        ],
    ]) ?>

</div>
