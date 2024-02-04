<?php

use purchases\components\statuses\DraftStatus;
use purchases\models\Purchase;
use users\models\User;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%purchase}}`.
 */
class m240203_165300_create_purchase_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(Purchase::tableName(), [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'budget' => $this->decimal(9, 2)->unsigned()->defaultValue(0),
            'status' => $this->smallInteger()->notNull()->defaultValue(DraftStatus::VALUE),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => 'DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT "Create date time"',
            'updated_at' => 'DATETIME ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT "Update date time"',
        ]);

        $this->createIndex(
            'idx-purchase-status',
            Purchase::tableName(),
            'status'
        );

        $this->addForeignKey(
            'fk-purchase-created_by',
            Purchase::tableName(),
            'created_by',
            User::tableName(),
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-purchase-updated_by',
            Purchase::tableName(),
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
        $this->dropIndex(
            'idx-purchase-status',
            Purchase::tableName()
        );
        $this->dropForeignKey(
            'fk-purchase-created_by',
            Purchase::tableName()
        );
        $this->dropForeignKey(
            'fk-purchase-updated_by',
            Purchase::tableName()
        );

        $this->dropTable(Purchase::tableName());
    }
}
