<?php

use yii\db\Migration;

/**
 * Class m190225_121944_create_table_feed
 */
class m190225_121944_create_table_feed extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('feed',[
            'id' =>$this->primaryKey(),
            'user_id'=> $this->integer(),
            'author_id'=>$this->integer(),
            'author_name'=> $this->string(),
            'author_nickname'=> $this->string(),
            'author_picture'=>$this->string(),
            'post_id'=>$this->integer(),
            'post_filename' => $this->string()->notNull(),
            'post_description'=> $this->text(),
            'post_created_at' =>$this->integer()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropTable('feed');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190225_121944_create_table_feed cannot be reverted.\n";

        return false;
    }
    */
}
