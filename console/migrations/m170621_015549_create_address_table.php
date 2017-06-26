<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170621_015549_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'consignee'=>$this->string(50)->notNull()->comment('收货人'),
            'province'=>$this->string(30)->notNull()->comment('省份'),
            'city'=>$this->string(30)->notNull()->comment('城市'),
            'area'=>$this->string(30)->notNull()->comment('区县'),
            'detail_address'=>$this->string(100)->notNull()->comment('详细地址'),
            'tel'=>$this->char(11)->notNull()->comment('手机号码'),
            'is_default'=>$this->smallInteger(1)->defaultValue(0)->comment('是否为默认地址'),
            'member_id'=>$this->integer()->notNull()->comment('用户id')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
