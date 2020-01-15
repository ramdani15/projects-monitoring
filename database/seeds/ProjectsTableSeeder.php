<?php

use Illuminate\Database\Seeder;

use App\Projects;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Projects::insert([
        	[
        		'name' => 'SIA',
        		'finished' => false,
        		'created_by' => 'pm1',
        	],
        	[
        		'name' => 'Cafe',
        		'finished' => false,
        		'created_by' => 'pm2',
        	],
        ]);
    }
}
