<?php

/** @var yii\web\View $this */
/** @var purchases\models\Purchase $model */
/** @var purchases\models\PurchaseNomenclature[] $nomenclatures */

/** @var yii\widgets\ActiveForm $form */

use purchases\components\statuses\Statuses;
use purchases\models\PurchaseNomenclature;
use purchases\widgets\multiInput\TabularInput;
use unclead\multipleinput\MultipleInputColumn;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$nomenclatureItem = new PurchaseNomenclature();

?>

<div class="purchase-form">

    <?php $form = ActiveForm::begin([
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'budget')->input('number', ['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(Statuses::getStatusesList(), [
        'prompt' => Yii::t('purchases', 'Select status')
    ]) ?>

    <h2><?= Yii::t('purchases', 'Nomenclature') ?></h2>

    <?= TabularInput::widget([
        'models' => $nomenclatures,
        'modelClass' => PurchaseNomenclature::class,
        'addButtonOptions' => [
            'label' => '+',
        ],
        'removeButtonOptions' => [
            'label' => '-'
        ],
        'min' => 1,
        'enableError' => true,
        'columns' => [
            [
                'name' => 'id',
                'type' => MultipleInputColumn::TYPE_HIDDEN_INPUT,
            ],
            [
                'name' => 'description',
                'title' => $nomenclatureItem->getAttributeLabel('description'),
            ],
            [
                'name' => 'qty',
                'title' => $nomenclatureItem->getAttributeLabel('qty'),
            ],
            [
                'name' => 'units',
                'title' => $nomenclatureItem->getAttributeLabel('units'),
            ],
        ],
    ]) ?>


    <div class="form-group mt-3">
        <?= Html::submitButton(
            $model->isNewRecord
                ? Yii::t('purchases', 'Save')
                : Yii::t('purchases', 'Update'),
            ['class' => 'btn btn-success']
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
