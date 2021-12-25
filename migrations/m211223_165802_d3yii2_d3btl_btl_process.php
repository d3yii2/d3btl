<?php

use yii\db\Migration;

class m211223_165802_d3yii2_d3btl_btl_process  extends Migration {

    public function safeUp() { 
        $this->execute('
            CREATE TABLE `btl_process`(  
              `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
              `part_id` INT(10) UNSIGNED NOT NULL,
              `key` CHAR(20),
              `parameters` TEXT,
              `ident` SMALLINT,
              `quality` ENUM(\'automatic\',\'visible\',\'fast\'),
              `recess` ENUM(\'automatic\',\'manual\'),
              `comment` TEXT,
              PRIMARY KEY (`id`),
              CONSTRAINT `fk_part` FOREIGN KEY (`part_id`) REFERENCES `btl_part`(`id`)
            );        
        ');

        $this->execute( '
               ALTER TABLE `btl_part`   
                  ADD COLUMN `parsed_data` TEXT NULL AFTER `uid`;
        ');
    }

    public function safeDown() {
        echo "m211223_165802_d3yii2_d3btl_btl_process cannot be reverted.\n";
        return false;
    }
}
