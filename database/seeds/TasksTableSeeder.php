<?php

use Illuminate\Database\Seeder;

use App\Projects;
use App\Tasks;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $project1 = Projects::where('name', 'SIA')->first();
        $project2 = Projects::where('name', 'Cafe')->first();

        Tasks::insert([
        	[
        		'project_id' => $project1->_id,
        		'keterangan' => 'Kurikulum',
        		'finished' => false,
        		'develop_by' => null,
        		'created_by' => 'pm1',
        	],
        	[
        		'project_id' => $project1->_id,
        		'keterangan' => 'Jadwal',
        		'finished' => false,
        		'develop_by' => 'programmer1',
        		'created_by' => 'pm1',
        	],
        	[
        		'project_id' => $project2->_id,
        		'keterangan' => 'Menu',
        		'finished' => false,
        		'develop_by' => null,
        		'created_by' => 'pm2',
        	],
        	[
        		'project_id' => $project2->_id,
        		'keterangan' => 'Pesanan',
        		'finished' => false,
        		'develop_by' => 'programmer2',
        		'created_by' => 'pm2',
        	],
        ]);
    }
}
