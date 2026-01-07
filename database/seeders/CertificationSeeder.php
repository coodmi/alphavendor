<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Certification;
use App\Models\User;
use Carbon\Carbon;

class CertificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all exporters
        $exporters = User::where('role', 'exporter')->get();

        if ($exporters->isEmpty()) {
            $this->command->warn('No exporters found! Please run VendorSeeder first.');
            return;
        }

        // Sample certification templates
        $certificationTemplates = [
            [
                'name' => 'ISO 9001:2015 Quality Management',
                'code' => 'ISO-9001',
                'description' => 'International standard for quality management systems',
                'issuing_authority' => 'International Organization for Standardization',
            ],
            [
                'name' => 'CE Marking Certification',
                'code' => 'CE-2024',
                'description' => 'Conformity with European health, safety, and environmental protection standards',
                'issuing_authority' => 'European Commission',
            ],
            [
                'name' => 'FDA Food Facility Registration',
                'code' => 'FDA-FFR',
                'description' => 'US Food and Drug Administration facility registration',
                'issuing_authority' => 'U.S. Food and Drug Administration',
            ],
            [
                'name' => 'HACCP Food Safety Certification',
                'code' => 'HACCP-2024',
                'description' => 'Hazard Analysis and Critical Control Points certification',
                'issuing_authority' => 'International HACCP Alliance',
            ],
            [
                'name' => 'GMP Good Manufacturing Practice',
                'code' => 'GMP-CERT',
                'description' => 'Good Manufacturing Practice certification for quality control',
                'issuing_authority' => 'World Health Organization',
            ],
            [
                'name' => 'Halal Certification',
                'code' => 'HALAL-2024',
                'description' => 'Islamic dietary standards certification',
                'issuing_authority' => 'Islamic Food and Nutrition Council',
            ],
            [
                'name' => 'Kosher Certification',
                'code' => 'KOSHER-K',
                'description' => 'Jewish dietary laws compliance certification',
                'issuing_authority' => 'Orthodox Union',
            ],
            [
                'name' => 'USDA Organic Certification',
                'code' => 'USDA-ORG',
                'description' => 'United States Department of Agriculture organic certification',
                'issuing_authority' => 'USDA National Organic Program',
            ],
            [
                'name' => 'Fair Trade Certified',
                'code' => 'FT-2024',
                'description' => 'Fair trade practices and sustainable sourcing certification',
                'issuing_authority' => 'Fair Trade International',
            ],
            [
                'name' => 'ISO 14001 Environmental Management',
                'code' => 'ISO-14001',
                'description' => 'Environmental management systems standard',
                'issuing_authority' => 'International Organization for Standardization',
            ],
            [
                'name' => 'BSCI Code of Conduct',
                'code' => 'BSCI-2024',
                'description' => 'Business Social Compliance Initiative certification',
                'issuing_authority' => 'Foreign Trade Association',
            ],
            [
                'name' => 'Export License',
                'code' => 'EXP-LIC',
                'description' => 'Government-issued export authorization',
                'issuing_authority' => 'Ministry of Commerce',
            ],
        ];

        $now = now();
        $allCertifications = [];

        foreach ($exporters as $exporter) {
            // Give each exporter ALL certifications
            $selectedTemplates = array_keys($certificationTemplates);

            // Ensure it's always an array
            if (!is_array($selectedTemplates)) {
                $selectedTemplates = [$selectedTemplates];
            }

            foreach ($selectedTemplates as $index) {
                $template = $certificationTemplates[$index];

                // Generate random issue date (between 1-3 years ago)
                $issueDate = Carbon::now()->subMonths(rand(12, 36));

                // Generate random expiry date (1-3 years from issue date)
                $expiryDate = $issueDate->copy()->addYears(rand(1, 3));

                $allCertifications[] = [
                    'vendor_id' => $exporter->id,
                    'name' => $template['name'],
                    'code' => $template['code'],
                    'description' => $template['description'],
                    'issuing_authority' => $template['issuing_authority'],
                    'issue_date' => $issueDate->format('Y-m-d'),
                    'expiry_date' => $expiryDate->format('Y-m-d'),
                    'certificate_number' => strtoupper($template['code']) . '-' . $exporter->id . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                    'document' => null,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            $this->command->info("✓ Created " . count($selectedTemplates) . " certifications for exporter: {$exporter->name}");
        }

        if (!empty($allCertifications)) {
            Certification::insert($allCertifications);
        }

        $this->command->info('');
        $this->command->info('Certifications seeded successfully!');
        $this->command->info('Total certifications created: ' . count($allCertifications));
    }
}
