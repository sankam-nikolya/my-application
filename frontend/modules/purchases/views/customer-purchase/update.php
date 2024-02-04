<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var purchases\models\Purchase $model */
/** @var purchases\models\PurchaseNomenclature[] $nomenclatures */

$this->title = $model->isNewRecord
    ? Yii::t('purchases', 'Add new purchase')
    : Yii::t('purchases', 'Update purchase: {NAME}', [
        'NAME' => $model->name,
    ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('purchases', 'My purchases'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->isNewRecord
    ? Yii::t('purchases', 'Add new purchase')
    : $model->name;

?>
<div class="purchase-update">

    <h1><?= Html::encode($model->name) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'nomenclatures' => $nomenclatures,
    ]) ?>

</div>
