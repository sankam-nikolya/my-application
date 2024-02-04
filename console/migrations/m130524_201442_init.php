<?php

use users\models\User;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m130524_201442_init extends Migration
{
    /**
     * @inheritDoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(User::tableName(), [
            'id' => $this->primaryKey(),
            'email' => $this->string(100)->notNull()->unique(),
            'name' => $this->string(100),
            'surname' => $this->string(100),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string(50)->unique(),
            'verification_token' => $this->string(50)->defaultValue(null),
            'access_token' => $this->string(32),
            'status' => $this->smallInteger()->notNull()->defaultValue(User::STATUS_ACTIVE),
            'created_at' => 'DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT "Create date time"',
            'updated_at' => 'DATETIME ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT "Update date time"',
        ], $tableOptions);

        $this->createIndex(
            'idx-user-auth_key',
            User::tableName(),
            'auth_key'
        );

        $this->createIndex(
            'idx-user-access_token',
            User::tableName(),
            'access_token'
        );

        $this->createIndex(
            'idx-user-status',
            User::tableName(),
            'status'
        );
    }

    /**
     * @inheritDoc
     */
    public function down()
    {
        $this->dropIndex(
            'idx-user-auth_key',
            User::tableName()
        );
        $this->dropIndex(
            'idx-user-access_token',
            User::tableName()
        );
        $this->dropIndex(
            'idx-user-status',
            User::tableName()
        );

        $this->dropTable(User::tableName());
    }
}
