<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = new SchoolInfo();
        $admin->name="Yellow Field Fountain Schools";
        $admin->email="schooldrivesng@gmail.com";
        $admin->phone="07053796686";
        $admin->term="First Term";
        $admin->session="2025/2026";
        $admin->status=1;
    }
}
