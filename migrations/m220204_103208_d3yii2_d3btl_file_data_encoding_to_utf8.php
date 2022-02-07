<?php

use yii\db\Migration;

class m220204_103208_d3yii2_d3btl_file_data_encoding_to_utf8  extends Migration {

    public function safeUp() { 
        $this->execute('
            ALTER TABLE `btl_file_data`
              CHANGE `file_data` `file_data` TEXT CHARSET utf8 NULL,
              CHANGE `parsed_data` `parsed_data` TEXT CHARSET utf8 NULL,
              CHANGE `notes` `notes` VARCHAR (255) CHARSET utf8 NULL,
              CHANGE `project_name` `project_name` VARCHAR (200) CHARSET utf8 NULL,
              CHANGE `file_name` `file_name` VARCHAR (255) CHARSET utf8 NULL;
        ');
        $this->execute('
        ALTER TABLE `btl_part`
            CHANGE `raw_data` `raw_data` TEXT CHARSET utf8 NULL,
            ADD COLUMN `master_part_id` INT UNSIGNED NULL COMMENT \'Master part\' AFTER `file_data_id`,
            ADD COLUMN `timbergrade` VARCHAR (256) CHARSET latin1 NULL AFTER `raw_data`;
        ');

        $this->execute('
        ALTER TABLE `btl_part`
            CHANGE `raw_data` `raw_data` TEXT CHARSET utf8 NULL,
            ADD CONSTRAINT `fk_file_part_master` FOREIGN KEY (`master_part_id`) REFERENCES `btl_part` (`id`);        
        ');
    }

    public function safeDown() {
        echo "m220204_103208_d3yii2_d3btl_file_data_encoding_to_utf8 cannot be reverted.\n";
        return false;
    }
}
