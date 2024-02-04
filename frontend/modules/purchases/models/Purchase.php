<?php

namespace purchases\models;


use purchases\components\statuses\Statuses;
use purchases\models\queries\PurchaseNomenclatureQuery;
use purchases\models\queries\PurchaseQuery;
use users\models\queries\UserQuery;
use users\models\User;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%purchase}}".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property integer $budget
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string $created_at Create date time
 * @property string $updated_at Update date time
 *
 * @property PurchaseNomenclature[] $nomenclatures
 * @property User $createdBy
 * @property User $updatedBy
 */
class Purchase extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%purchase}}';
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
            [['name'], 'required'],
            [['description'], 'string'],
            [['status', 'created_by', 'updated_by'], 'integer'],
            ['budget', 'number', 'min' => 0.01],
            [['created_at', 'updated_at'], 'safe'],
            ['name', 'string', 'max' => 255],
            ['status', 'default', 'value' => (Statuses::getDefaultStatus())::VALUE],
            ['status', 'in', 'range' => array_keys(Statuses::getStatusesList())],
            ['status', 'validateStatus'],
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
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('purchases', 'ID'),
            'name' => Yii::t('purchases', 'Name'),
            'description' => Yii::t('purchases', 'Description'),
            'budget' => Yii::t('purchases', 'Budget'),
            'status' => Yii::t('purchases', 'Status'),
            'created_by' => Yii::t('purchases', 'Created By'),
            'updated_by' => Yii::t('purchases', 'Updated By'),
            'created_at' => Yii::t('purchases', 'Created At'),
            'updated_at' => Yii::t('purchases', 'Updated At'),
        ];
    }

    /**
     * Status validation
     * @param $attribute
     * @param $params
     * @return void
     */
    public function validateStatus($attribute, $params): void
    {
        $oldValue = $this->oldAttributes[$attribute] ?? (Statuses::getDefaultStatus())::VALUE;
        if (!Statuses::isValid((int)$this->status, (int)$oldValue)) {
            $this->addError(
                $attribute,
                Yii::t('purchases', 'Status can\'t be set to "{STATUS_NAME}".', [
                    'STATUS_NAME' => Statuses::getStatusName((int)$this->status),
                ])
            );
        }
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
     * Gets query for [[PurchaseNomenclatures]].
     * @return PurchaseNomenclatureQuery
     */
    public function getNomenclatures(): PurchaseNomenclatureQuery
    {
        return $this->hasMany(PurchaseNomenclature::class, ['purchase_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return PurchaseQuery the active query used by this AR class.
     */
    public static function find(): PurchaseQuery
    {
        return new PurchaseQuery(static::class);
    }
}
