<?php

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSdr extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create(['username'=>'admin','role'=>'admin']);
        Admin::create(['username'=>'vindicator','role'=>'vindicator']);
    }
}
