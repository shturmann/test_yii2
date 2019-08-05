<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%twitter}}`.
 */
class m190804_192958_create_twitter_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%twitter}}', [
            'id' => $this->primaryKey(),
            'user' => $this->string(255),
            'secret' => $this->string(255),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%twitter}}');
    }
}
