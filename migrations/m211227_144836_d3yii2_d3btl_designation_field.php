<?php

use yii\db\Migration;

class m211227_144836_d3yii2_d3btl_designation_field extends Migration
{

    public function safeUp()
    {
        $this->execute('
            ALTER TABLE `btl_process`   
              ADD COLUMN `designation` CHAR(20) NULL AFTER `key`;
                    
        ');
    }

    public function safeDown()
    {
        echo "m211227_144836_d3yii2_d3btl_designation field cannot be reverted.\n";
        return false;
    }
}
