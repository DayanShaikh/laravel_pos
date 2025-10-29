<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ConfigType;
use App\Models\ConfigVariable;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(AdminSeeder::class);   
        $this->call(MenuSeeder::class);
        $config = ConfigType::create([
            'title' => 'General Settings'
        ]);

        ConfigVariable::create([
            'config_type_id' => $config->id,
            'title' => 'Site Url',
            'type' => 'text',
            'key' => 'site_url'
        ]);

        ConfigVariable::create([
            'config_type_id' => $config->id,
            'title' => 'Site Title',
            'type' => 'text',
            'key' => 'site_title'
        ]);

        ConfigVariable::create([
            'config_type_id' => $config->id,
            'title' => 'Admin Logo',
            'type' => 'file',
            'key' => 'admin_logo'
        ]);

        ConfigVariable::create([
            'config_type_id' => $config->id,
            'title' => 'Admin Email',
            'type' => 'text',
            'key' => 'admin_email'
        ]);
        
        ConfigVariable::create([
            'config_type_id' => $config->id,
            'title' => 'Login Logo',
            'type' => 'file',
            'key' => 'login_logo'
        ]);
        ConfigVariable::create([
            'config_type_id' => $config->id,
            'title' => 'Recipt Logo',
            'type' => 'file',
            'key' => 'recipt_logo'
        ]);
        ConfigVariable::create([
            'config_type_id' => $config->id,
            'title' => 'Fav icon',
            'type' => 'file',
            'key' => 'fav_icon'
        ]);
        ConfigVariable::create([
            'config_type_id' => $config->id,
            'title' => 'Address',
            'type' => 'textarea',
            'key' => 'address'
        ]);
    }
}
