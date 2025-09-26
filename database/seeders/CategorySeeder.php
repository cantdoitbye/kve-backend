<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Segment;
use App\Models\SubSegment;
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
  public function run(): void
    {
        // Create Welding category from your image
        $welding = Category::create([
            'title' => 'Welding, Cutting & Air Cleaning Equipment',
            'status' => true
        ]);

        $weldingEquipment = SubCategory::create([
            'title' => 'Welding Equipment',
            'category_id' => $welding->id,
            'status' => true
        ]);

        $manualWelding = Segment::create([
            'title' => 'Manual Welding Equipments',
            'sub_category_id' => $weldingEquipment->id,
            'status' => true
        ]);

        $arcWelding = Segment::create([
            'title' => 'Arc Welding Equipment',
            'sub_category_id' => $weldingEquipment->id,
            'status' => true
        ]);

        SubSegment::create([
            'title' => 'Minarc Evo 180',
            'segment_id' => $manualWelding->id,
            'status' => true
        ]);

        SubSegment::create([
            'title' => 'Master MLS 3500',
            'segment_id' => $manualWelding->id,
            'status' => true
        ]);

        SubSegment::create([
            'title' => 'Jasic Arc 250 Z276',
            'segment_id' => $arcWelding->id,
            'status' => true
        ]);
    }
}
