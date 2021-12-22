<?php

use yii\db\Migration;

class m211222_171446_d3yii2_d3btl_file_name_field  extends Migration {

    public function safeUp() { 
        $this->execute('
            ALTER TABLE `btl_file_data`   
              ADD COLUMN `file_name` VARCHAR(255) NULL AFTER `export_datetime`;
                    
        ');
    }

    public function safeDown() {
        echo "m211222_171446_d3yii2_d3btl_file_name_field cannot be reverted.\n";
        return false;
    }
}
