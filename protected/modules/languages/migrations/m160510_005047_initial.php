<?php

use yii\db\Migration;

class m160510_005047_initial extends Migration
{
    public function up()
    {
        $this->createTable('languages', array(
            'id' => 'pk',
            'language' => 'varchar(255) NOT NULL',
            'code' => 'varchar(255) NOT NULL',
            'created_at' => 'datetime NOT NULL',
            'updated_at' => 'datetime NOT NULL',
        ), '');

        $this->insert('languages', array('language' => 'English', 'code' => 'en-US', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')));
        $this->insert('languages', array('language' => 'Espanol', 'code' => 'es', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')));
    }

    public function down()
    {
        // echo "m160510_005047_initial cannot be reverted.\n";

        $this->dropTable('languages');

        return true;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
