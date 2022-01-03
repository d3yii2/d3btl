<?php

use yii\db\Migration;

class m220103_174053_d3yii2_d3btl_name_change_parse_data  extends Migration {

    public function safeUp() { 
        $this->execute('
            ALTER TABLE `btl_part`   
              CHANGE `parsed_data` `raw_data` TEXT CHARSET utf8mb4 COLLATE utf8mb4_general_ci NULL;
                    
        ');
    }

    public function safeDown() {
        echo "m220103_174053_d3yii2_d3btl_name_change_parse_data cannot be reverted.\n";
        return false;
    }
}
