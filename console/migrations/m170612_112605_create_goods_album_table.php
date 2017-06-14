<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_album`.
 */
class m170612_112605_create_goods_album_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_album', [
            'id' => $this->primaryKey(),
            'album_path'=>$this->string(100)->notNull()->comment('图片路径'),
            'goods_id'=>$this->integer()->notNull()->comment('商品id')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_album');
    }
}
