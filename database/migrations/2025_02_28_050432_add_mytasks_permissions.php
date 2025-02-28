<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        DB::table('permissions')->insert([
            ['name' => 'read mytasks', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'write mytasks', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'delete mytasks', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        DB::table('permissions')->whereIn('name', [
            'read mytasks', 'write mytasks', 'delete mytasks'
        ])->delete();
    }
};
