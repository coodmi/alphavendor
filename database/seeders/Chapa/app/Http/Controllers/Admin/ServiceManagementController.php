<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class ServiceManagementController extends Controller
{
    public function index()
    {
        $services = [
            [
                'id' => 1,
                'name' => 'Magazines',
                'slug' => 'magazines',
                'description' => 'Create and customize magazine layouts with various binding options',
                'icon' => '📰',
                'color' => 'blue',
                'route' => '/admin/service-categories',
            ],
            [
                'id' => 2,
                'name' => 'Books',
                'slug' => 'books',
                'description' => 'Design custom books with multiple format and binding choices',
                'icon' => '📚',
                'color' => 'green',
                'route' => '/admin/service-categories',
            ],
            [
                'id' => 3,
                'name' => 'Catalog',
                'slug' => 'catalog',
                'description' => 'Professional catalogs for showcasing products and services',
                'icon' => '📋',
                'color' => 'purple',
                'route' => '/admin/service-categories',
            ],
            [
                'id' => 4,
                'name' => 'Marketing Material',
                'slug' => 'marketing',
                'description' => 'Brochures, flyers, and marketing collateral',
                'icon' => '📢',
                'color' => 'orange',
                'route' => '/admin/service-categories',
            ],
            [
                'id' => 5,
                'name' => 'Business Cards',
                'slug' => 'business-cards',
                'description' => 'Professional business cards with various finishes',
                'icon' => '💼',
                'color' => 'indigo',
                'route' => '/admin/service-categories',
            ],
            [
                'id' => 6,
                'name' => 'Invitation & Stationery',
                'slug' => 'stationery',
                'description' => 'Custom invitations and stationery products',
                'icon' => '✉️',
                'color' => 'pink',
                'route' => '/admin/service-categories',
            ],
            [
                'id' => 7,
                'name' => 'Banners',
                'slug' => 'banners',
                'description' => 'Large format banners for events and promotions',
                'icon' => '🎯',
                'color' => 'red',
                'route' => '/admin/service-categories',
            ],
            [
                'id' => 8,
                'name' => 'Promotional Items',
                'slug' => 'promotional-items',
                'description' => 'Custom promotional products and merchandise',
                'icon' => '🎁',
                'color' => 'yellow',
                'route' => '/admin/service-categories',
            ],
        ];

        return Inertia::render('Admin/ServiceManagement', [
            'services' => $services,
        ]);
    }
}
