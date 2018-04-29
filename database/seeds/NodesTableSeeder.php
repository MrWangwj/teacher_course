<?php

use Illuminate\Database\Seeder;

class NodesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \Illuminate\Support\Facades\DB::table('nodes')->insert([
            [
                'name' => '教师管理',
                'code' => '001',
                'pid' => '0',
                'depth' => '1',
                'path' => '/teacher',
                'type' => '0',
                'icon' => 'el-icon-tickets',
                'sort_factor' => '1',
                'status' => '0',
            ],
            [
                'name' => '教师信息',
                'code' => '001001',
                'pid' => '1',
                'depth' => '2',
                'path' => '/teacher/info',
                'type' => '0',
                'icon' => 'el-icon-tickets',
                'sort_factor' => '1',
                'status' => '0',
            ],
            [
                'name' => '课表管理',
                'code' => '002',
                'pid' => '0',
                'depth' => '1',
                'path' => '/course',
                'type' => '0',
                'icon' => 'el-icon-tickets',
                'sort_factor' => '2',
                'status' => '0',
            ],
            [
                'name' => '课表信息',
                'code' => '002001',
                'pid' => '3',
                'depth' => '2',
                'path' => '/course/info',
                'type' => '0',
                'icon' => 'el-icon-tickets',
                'sort_factor' => '2',
                'status' => '0',
            ],
            [
                'name' => '系统管理',
                'code' => '003',
                'pid' => '0',
                'depth' => '1',
                'path' => '/setting',
                'type' => '0',
                'icon' => 'el-icon-tickets',
                'sort_factor' => '3',
                'status' => '0',
            ],
            [
                'name' => '教研室管理',
                'code' => '003001',
                'pid' => '5',
                'depth' => '2',
                'path' => '/setting/staffroom',
                'type' => '0',
                'icon' => 'el-icon-tickets',
                'sort_factor' => '3',
                'status' => '0',
            ],
            [
                'name' => '职务级别管理',
                'code' => '003002',
                'pid' => '5',
                'depth' => '2',
                'path' => '/setting/joblevel',
                'type' => '0',
                'icon' => 'el-icon-tickets',
                'sort_factor' => '3',
                'status' => '0',
            ],

            [
                'name' => '职称管理',
                'code' => '003003',
                'pid' => '5',
                'depth' => '2',
                'path' => '/setting/title',
                'type' => '0',
                'icon' => 'el-icon-tickets',
                'sort_factor' => '3',
                'status' => '0',
            ],
            [
                'name' => '职务类型管理',
                'code' => '003004',
                'pid' => '5',
                'depth' => '2',
                'path' => '/setting/jobtype',
                'type' => '0',
                'icon' => 'el-icon-tickets',
                'sort_factor' => '3',
                'status' => '0',
            ],

            [
                'name' => '学期管理',
                'code' => '003005',
                'pid' => '5',
                'depth' => '2',
                'path' => '/setting/term',
                'type' => '0',
                'icon' => 'el-icon-tickets',
                'sort_factor' => '3',
                'status' => '0',
            ],
        ]);
    }
}
