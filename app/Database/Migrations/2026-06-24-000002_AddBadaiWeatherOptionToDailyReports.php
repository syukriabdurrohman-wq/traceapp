<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBadaiWeatherOptionToDailyReports extends Migration
{
    public function up(): void
    {
        if (! $this->db->tableExists('DailyReports')) {
            return;
        }

        $this->db->query("ALTER TABLE `DailyReports` MODIFY `weather_code` enum('Cerah','Mendung','Hujan','Badai') COLLATE utf8mb4_unicode_ci NOT NULL");
    }

    public function down(): void
    {
        if (! $this->db->tableExists('DailyReports')) {
            return;
        }

        $this->db->query("ALTER TABLE `DailyReports` MODIFY `weather_code` enum('Cerah','Mendung','Hujan') COLLATE utf8mb4_unicode_ci NOT NULL");
    }
}
