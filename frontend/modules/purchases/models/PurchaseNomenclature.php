<?php

namespace purchases\models;

use purchases\models\queries\PurchaseNomenclatureQuery;
use purchases\models\queries\PurchaseQuery;
use purchases\models\queries\UserQuery;
use users\models\User;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%purchase_nomenclature}}".
 *
 * @property int $id
 * @property int|null $purchase_id
 * @property string $description
 * @property int $qty
 * @property int $units
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string $created_at Create date time
 * @property string $updated_at Update date time
 *
 * @property array $nomenclature
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property Purchase $purchase
 */
class PurchaseNomenclature extends ActiveRecord
{
    public array $nomenclature = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%purchase_nomenclature}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'blameable' => [
                'class' => BlameableBehavior::class,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['purchase_id', 'created_by', 'updated_by'], 'integer'],
            [['description', 'qty', 'units'], 'required'],
            ['qty', 'integer', 'min' => 1],
            [['units'], 'string', 'max' => 10],
            [['created_at', 'updated_at'], 'safe'],
            [['description'], 'string', 'max' => 255],
            [
                'purchase_id',
                'exist',
                'skipOnError' => true,
                'targetClass' => Purchase::class,
                'targetAttribute' => [
                    'purchase_id' => 'id',
                ],
            ],
            [
                'created_by',
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['created_by' => 'id'],
            ],
            [
                'updated_by',
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['updated_by' => 'id'],
            ],
            ['nomenclature', 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('purchases', 'ID'),
            'purchase_id' => Yii::t('purchases', 'Purchase'),
            'description' => Yii::t('purchases', 'Description'),
            'qty' => Yii::t('purchases', 'Quantity'),
            'units' => Yii::t('purchases', 'Units'),
            'created_by' => Yii::t('purchases', 'Created By'),
            'updated_by' => Yii::t('purchases', 'Updated By'),
            'created_at' => Yii::t('purchases', 'Created At'),
            'updated_at' => Yii::t('purchases', 'Updated At'),
            'nomenclature' => Yii::t('purchases', 'Nomenclature'),
        ];
    }

    /**
     * Gets query for [[Purchase]].
     * @return PurchaseQuery
     */
    public function getPurchase(): PurchaseQuery
    {
        return $this->hasOne(Purchase::class, ['id' => 'purchase_id']);
    }

    /**
     * Gets query for [[CreatedBy]].
     * @return UserQuery
     */
    public function getCreatedBy(): UserQuery
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     * @return UserQuery
     */
    public function getUpdatedBy(): UserQuery
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    /**
     * {@inheritdoc}
     * @return PurchaseNomenclatureQuery the active query used by this AR class.
     */
    public static function find(): PurchaseNomenclatureQuery
    {
        return new PurchaseNomenclatureQuery(static::class);
    }
}
