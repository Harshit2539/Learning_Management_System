<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("
            CREATE TABLE IF NOT EXISTS category_mapping (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                webinar_id BIGINT UNSIGNED NOT NULL,
                category_id BIGINT UNSIGNED NOT NULL,
                created_at TIMESTAMP NULL DEFAULT NULL,
                updated_at TIMESTAMP NULL DEFAULT NULL,
                UNIQUE KEY unique_webinar_category (webinar_id, category_id),
                CONSTRAINT fk_category_mapping_webinar
                    FOREIGN KEY (webinar_id) REFERENCES webinars(id) ON DELETE CASCADE,
                CONSTRAINT fk_category_mapping_category
                    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
    }

    public function down()
    {
        
        DB::statement("DROP TABLE IF EXISTS category_mapping");
    }
};