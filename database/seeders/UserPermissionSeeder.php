<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dictionary\UserPermission;

class UserPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserPermission::factory()->createMany([
            [
                'id' => 1,
                'name' => 'Guest',
                'description' => 'Default access level can leave tributes, share media and stories'
            ],
            [
                'id' => 2,
                'name' => 'Administrator',
                'description' => 'Can control all aspects of the website, including moderating content posted by others and changing website settings'
            ],
        ]);
    }
}
