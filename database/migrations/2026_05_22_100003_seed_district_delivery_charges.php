<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $defaults = [
            'Dhaka' => ['division' => 'Dhaka', 'base' => 60],
            'Rajshahi' => ['division' => 'Rajshahi', 'base' => 120],
            'Chattogram' => ['division' => 'Chattogram', 'base' => 130],
            'Comilla' => ['division' => 'Chattogram', 'base' => 130],
            'Sylhet' => ['division' => 'Sylhet', 'base' => 130],
            'Khulna' => ['division' => 'Khulna', 'base' => 120],
            'Barisal' => ['division' => 'Barisal', 'base' => 120],
            'Rangpur' => ['division' => 'Rangpur', 'base' => 120],
            'Mymensingh' => ['division' => 'Mymensingh', 'base' => 120],
        ];

        $allDistricts = [
            'Dhaka' => 'Dhaka',
            'Faridpur' => 'Dhaka', 'Gazipur' => 'Dhaka', 'Gopalganj' => 'Dhaka', 'Kishoreganj' => 'Dhaka',
            'Madaripur' => 'Dhaka', 'Manikganj' => 'Dhaka', 'Munshiganj' => 'Dhaka', 'Narayanganj' => 'Dhaka',
            'Narsingdi' => 'Dhaka', 'Rajbari' => 'Dhaka', 'Shariatpur' => 'Dhaka', 'Tangail' => 'Dhaka',
            'Bandarban' => 'Chattogram', 'Brahmanbaria' => 'Chattogram', 'Chandpur' => 'Chattogram',
            'Chattogram' => 'Chattogram', 'Comilla' => 'Chattogram', "Cox's Bazar" => 'Chattogram',
            'Feni' => 'Chattogram', 'Khagrachari' => 'Chattogram', 'Lakshmipur' => 'Chattogram',
            'Noakhali' => 'Chattogram', 'Rangamati' => 'Chattogram',
            'Bagerhat' => 'Khulna', 'Chuadanga' => 'Khulna', 'Jessore' => 'Khulna', 'Jhenaidah' => 'Khulna',
            'Khulna' => 'Khulna', 'Kushtia' => 'Khulna', 'Magura' => 'Khulna', 'Meherpur' => 'Khulna',
            'Narail' => 'Khulna', 'Satkhira' => 'Khulna',
            'Bogra' => 'Rajshahi', 'Chapainawabganj' => 'Rajshahi', 'Joypurhat' => 'Rajshahi',
            'Naogaon' => 'Rajshahi', 'Natore' => 'Rajshahi', 'Pabna' => 'Rajshahi', 'Rajshahi' => 'Rajshahi',
            'Sirajganj' => 'Rajshahi',
            'Barguna' => 'Barisal', 'Barisal' => 'Barisal', 'Bhola' => 'Barisal', 'Jhalokathi' => 'Barisal',
            'Patuakhali' => 'Barisal', 'Pirojpur' => 'Barisal',
            'Habiganj' => 'Sylhet', 'Moulvibazar' => 'Sylhet', 'Sunamganj' => 'Sylhet', 'Sylhet' => 'Sylhet',
            'Dinajpur' => 'Rangpur', 'Gaibandha' => 'Rangpur', 'Kurigram' => 'Rangpur',
            'Lalmonirhat' => 'Rangpur', 'Nilphamari' => 'Rangpur', 'Panchagarh' => 'Rangpur',
            'Rangpur' => 'Rangpur', 'Thakurgaon' => 'Rangpur',
            'Jamalpur' => 'Mymensingh', 'Mymensingh' => 'Mymensingh', 'Netrokona' => 'Mymensingh',
            'Sherpur' => 'Mymensingh',
        ];

        $now = now();
        foreach ($allDistricts as $district => $division) {
            $base = $defaults[$district]['base'] ?? ($defaults[$division]['base'] ?? 120);

            DB::table('district_delivery_charges')->updateOrInsert(
                ['district' => $district],
                [
                    'division' => $division,
                    'base_charge' => $base,
                    'is_active' => true,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }
    }

    public function down(): void
    {
        // District rows may be edited in production; leave data on rollback of this migration only if full down is run.
    }
};
