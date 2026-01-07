<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;

class ExporterCertificationSeeder extends Seeder
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

        // Available certifications
        $availableCertifications = [
            'iso_certified',
            'ce_certified',
            'fda_approved',
            'export_license',
            'haccp_certified',
            'gmp_certified',
            'halal_certified',
            'kosher_certified',
            'organic_certified',
            'fair_trade',
        ];

        // Add certifications and ratings to exporters
        foreach ($exporters as $exporter) {
            // Randomly assign 2-5 certifications to each exporter
            $numCertifications = rand(2, 5);
            $selectedCertifications = array_rand(array_flip($availableCertifications), $numCertifications);

            // Ensure it's always an array
            if (!is_array($selectedCertifications)) {
                $selectedCertifications = [$selectedCertifications];
            }

            // Generate a random rating between 3.5 and 5.0
            $rating = rand(35, 50) / 10;

            // Update exporter
            $exporter->certifications = $selectedCertifications;
            $exporter->exporter_rating = $rating;
            $exporter->save();

            $certsList = implode(', ', array_map(function($cert) {
                return ucwords(str_replace('_', ' ', $cert));
            }, $selectedCertifications));

            $this->command->info("✓ Added certifications to exporter: {$exporter->name} - Rating: {$rating}/5.0");
            $this->command->line("  Certifications: {$certsList}");
        }

        // Add certifications to exporter products
        foreach ($exporters as $exporter) {
            $products = Product::where('vendor_id', $exporter->id)->get();

            if ($products->isEmpty()) {
                continue;
            }

            foreach ($products as $product) {
                // Randomly assign 1-4 certifications to each product
                $numCertifications = rand(1, 4);
                $selectedCertifications = array_rand(array_flip($availableCertifications), $numCertifications);

                // Ensure it's always an array
                if (!is_array($selectedCertifications)) {
                    $selectedCertifications = [$selectedCertifications];
                }

                // Generate a random rating between 3.0 and 5.0
                $rating = rand(30, 50) / 10;

                // Update product
                $product->certifications = $selectedCertifications;
                $product->exporter_rating = $rating;
                $product->save();
            }

            $this->command->info("✓ Added certifications to {$products->count()} products for exporter: {$exporter->name}");
        }

        $this->command->info('');
        $this->command->info('Exporter certifications and ratings seeded successfully!');
    }
}
