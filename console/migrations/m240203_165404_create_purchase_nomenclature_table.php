<?php

use purchases\models\Purchase;
use purchases\models\PurchaseNomenclature;
use users\models\User;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%purchase_nomenclature}}`.
 */
class m240203_165404_create_purchase_nomenclature_table extends Migration
{
    public static $tableName = '{{%purchase_nomenclature}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(PurchaseNomenclature::tableName(), [
            'id' => $this->primaryKey(),
            'purchase_id' => $this->integer(),
            'description' => $this->string(255)->notNull(),
            'qty' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'units' => $this->string(10)->notNull(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => 'DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT "Create date time"',
            'updated_at' => 'DATETIME ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT "Update date time"',
        ]);

        $this->addForeignKey(
            'fk-purchase_nomenclature-purchase_id',
            PurchaseNomenclature::tableName(),
            'purchase_id',
            Purchase::tableName(),
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-purchase_nomenclature-created_by',
            PurchaseNomenclature::tableName(),
            'created_by',
            User::tableName(),
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-purchase_nomenclature-updated_by',
            PurchaseNomenclature::tableName(),
            'updated_by',
            User::tableName(),
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-purchase_nomenclature-purchase_id',
            PurchaseNomenclature::tableName()
        );
        $this->dropForeignKey(
            'fk-purchase_nomenclature-created_by',
            PurchaseNomenclature::tableName()
        );
        $this->dropForeignKey(
            'fk-purchase_nomenclature-updated_by',
            PurchaseNomenclature::tableName()
        );


        $this->dropTable(PurchaseNomenclature::tableName());
    }
}
