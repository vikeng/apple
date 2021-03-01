<?php

use yii\db\Migration;

/**
 * Class m210301_072300_apple
 */
class m210301_072300_apple extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $options = $this->db->getDriverName() == 'mysql' ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;
        $this->createTable('{{%apple}}', [
            'id' => $this->primaryKey(),
            'color' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'status' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'eaten' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'dateAppearance' => $this->timestamp()->notNull(),
            'dateFail' => $this->timestamp()->null()->defaultValue(null),
        ], $options);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%apple}}');
    }
}
