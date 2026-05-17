<?php

namespace App\Helpers;

class EmployeePermission
{
    /**
     * All permission modules with their actions.
     * Key format: module.action  (e.g. "products.add")
     */
    public static function modules(): array
    {
        return [
            'MAIN' => [
                'label' => 'Main',
                'icon'  => 'fa-home',
                'permissions' => [
                    'dashboard.view'          => 'Dashboard – View',
                    'analytics.view'          => 'Analytics & Reports – View',
                    'analytics.export'        => 'Analytics & Reports – Export',
                ],
            ],
            'CATALOG' => [
                'label' => 'Catalog Management',
                'icon'  => 'fa-boxes',
                'permissions' => [
                    'products.add'            => 'Products – Add',
                    'products.edit'           => 'Products – Edit',
                    'products.delete'         => 'Products – Delete',
                    'products.view'           => 'Products – View',
                    'products.approve'        => 'Products – Approve / Review',
                    'categories.manage'       => 'Categories – Add / Edit / Delete / View',
                    'brands.manage'           => 'Brands – Add / Edit / Delete / View',
                    'attributes.manage'       => 'Attributes – Add / Edit / Delete / View',
                    'reviews.view'            => 'Reviews & Ratings – View',
                    'reviews.delete'          => 'Reviews & Ratings – Delete',
                    'reviews.approve'         => 'Reviews & Ratings – Approve',
                    'reviews.reply'           => 'Reviews & Ratings – Reply',
                ],
            ],
            'SALES' => [
                'label' => 'Sales & Orders',
                'icon'  => 'fa-shopping-cart',
                'permissions' => [
                    'orders.view'             => 'Orders – View',
                    'orders.update_status'    => 'Orders – Update Status',
                    'orders.cancel'           => 'Orders – Cancel',
                    'orders.approve'          => 'Orders – Approve',
                    'returns.view'            => 'Returns & Refunds – View',
                    'returns.approve'         => 'Returns & Refunds – Approve',
                    'returns.reject'          => 'Returns & Refunds – Reject',
                ],
            ],
            'USERS' => [
                'label' => 'User Management',
                'icon'  => 'fa-users',
                'permissions' => [
                    'users.view'              => 'All Users – View',
                    'users.edit'              => 'All Users – Edit',
                    'users.block'             => 'All Users – Block / Unblock',
                    'users.add'               => 'All Users – Add',
                    'verification.view'       => 'Verification – View',
                    'verification.edit'       => 'Verification – Edit',
                    'user_permissions.view'   => 'User Permissions – View',
                    'user_permissions.edit'   => 'User Permissions – Edit',
                    'role_settings.manage'    => 'Role Settings – Create / Edit / Delete / View',
                    'activity_logs.view'      => 'Activity Logs – View',
                    'activity_logs.export'    => 'Activity Logs – Export',
                    'activity_logs.clear'     => 'Activity Logs – Clear',
                ],
            ],
            'EMPLOYEES' => [
                'label' => 'Employee Management',
                'icon'  => 'fa-user-tie',
                'permissions' => [
                    'employees.add'           => 'All Employees – Add',
                    'employees.edit'          => 'All Employees – Edit',
                    'employees.delete'        => 'All Employees – Delete',
                    'employees.view'          => 'All Employees – View',
                    'employee_permissions.view'  => 'Employee Permissions – View',
                    'employee_permissions.edit'  => 'Employee Permissions – Edit',
                    'employee_permissions.copy'  => 'Employee Permissions – Copy Permission',
                ],
            ],
            'VENDORS' => [
                'label' => 'Vendor Management',
                'icon'  => 'fa-store',
                'permissions' => [
                    'vendors.view'            => 'All Vendors – View',
                    'vendors.edit'            => 'All Vendors – Edit',
                    'vendors.block'           => 'All Vendors – Block / Unblock',
                    'vendor_badges.manage'    => 'Vendor Badges – Add / Edit / Delete',
                    'vendor_applications.approve' => 'Vendor Applications – Approve',
                    'vendor_applications.reject'  => 'Vendor Applications – Reject',
                    'vendor_applications.view'    => 'Vendor Applications – View',
                ],
            ],
            'COMMUNICATION' => [
                'label' => 'Communication',
                'icon'  => 'fa-comments',
                'permissions' => [
                    'tickets.view'            => 'Support Tickets – View',
                    'tickets.reply'           => 'Support Tickets – Reply',
                    'tickets.close'           => 'Support Tickets – Close',
                    'chat.view'               => 'Chat – View',
                    'chat.reply'              => 'Chat – Reply',
                ],
            ],
            'PAYMENTS' => [
                'label' => 'Payments',
                'icon'  => 'fa-money-bill-wave',
                'permissions' => [
                    'advance_payments.view'   => 'Advance Payments – View',
                    'advance_payments.approve'=> 'Advance Payments – Approve',
                    'advance_payments.reject' => 'Advance Payments – Reject',
                ],
            ],
            'SYSTEM' => [
                'label' => 'System Settings',
                'icon'  => 'fa-cog',
                'permissions' => [
                    'otp.manage'              => 'OTP Management – View / Edit',
                    'otp_api.manage'          => 'OTP / API Settings – View / Edit',
                ],
            ],
            'DELIVERY' => [
                'label' => 'Delivery & Commission',
                'icon'  => 'fa-truck',
                'permissions' => [
                    'commission.manage'       => 'Commission Settings – View / Edit',
                    'delivery.manage'         => 'Delivery Management – View / Edit',
                ],
            ],
            'MARKETING' => [
                'label' => 'Marketing & Promotion',
                'icon'  => 'fa-bullhorn',
                'permissions' => [
                    'coupons.manage'          => 'Coupons – Add / Edit / Delete',
                    'special_offers.manage'   => 'Special Offers – Add / Edit / Delete',
                    'banners.manage'          => 'Banners – Add / Edit / Delete',
                    'promo_banners.manage'    => 'Promo Banners – Add / Edit / Delete',
                ],
            ],
            'PAGES' => [
                'label' => 'Pages',
                'icon'  => 'fa-file-alt',
                'permissions' => [
                    'pages.add'               => 'Pages – Add',
                    'pages.edit'              => 'Pages – Edit',
                    'pages.delete'            => 'Pages – Delete',
                    'pages.view'              => 'Pages – View',
                ],
            ],
            'SHIPPING' => [
                'label' => 'Shipping',
                'icon'  => 'fa-shipping-fast',
                'permissions' => [
                    'shipping_info.manage'    => 'Shipping Info – View / Edit',
                    'shipping.manage'         => 'Shipping Management – Add / Edit / Delete',
                ],
            ],
            'FINANCE' => [
                'label' => 'Payment & Finance',
                'icon'  => 'fa-chart-line',
                'permissions' => [
                    'transactions.view'       => 'Transactions – View',
                    'payment_gateways.edit'   => 'Payment Gateways – Edit',
                    'payment_verification.manage' => 'Payment Verification – Approve / Reject',
                    'cod.view'                => 'Cash on Delivery – View',
                    'cod.manage'              => 'Cash on Delivery – Update Status / Cancel / Approve',
                ],
            ],
        ];
    }

    /**
     * Flat list of all permission keys.
     */
    public static function allKeys(): array
    {
        $keys = [];
        foreach (self::modules() as $module) {
            foreach ($module['permissions'] as $key => $label) {
                $keys[] = $key;
            }
        }
        return $keys;
    }

    /**
     * Role templates — predefined permission sets.
     */
    public static function roleTemplates(): array
    {
        return [
            'admin_full' => [
                'label' => 'Admin – Full Access',
                'color' => 'bg-red-100 text-red-700',
                'permissions' => self::allKeys(),
            ],
            'manager' => [
                'label' => 'Manager – Product + Order + Vendor',
                'color' => 'bg-blue-100 text-blue-700',
                'permissions' => [
                    'dashboard.view',
                    'products.add','products.edit','products.delete','products.view','products.approve',
                    'categories.manage','brands.manage','attributes.manage',
                    'reviews.view','reviews.approve','reviews.reply',
                    'orders.view','orders.update_status','orders.cancel','orders.approve',
                    'returns.view','returns.approve','returns.reject',
                    'vendors.view','vendors.edit','vendors.block',
                    'vendor_applications.approve','vendor_applications.reject','vendor_applications.view',
                ],
            ],
            'support' => [
                'label' => 'Support – Ticket + Order View',
                'color' => 'bg-green-100 text-green-700',
                'permissions' => [
                    'dashboard.view',
                    'orders.view',
                    'tickets.view','tickets.reply','tickets.close',
                    'chat.view','chat.reply',
                    'users.view',
                ],
            ],
            'accounts' => [
                'label' => 'Accounts – Payment + Commission',
                'color' => 'bg-yellow-100 text-yellow-700',
                'permissions' => [
                    'dashboard.view',
                    'analytics.view','analytics.export',
                    'advance_payments.view','advance_payments.approve','advance_payments.reject',
                    'commission.manage',
                    'transactions.view',
                    'payment_gateways.edit',
                    'payment_verification.manage',
                    'cod.view','cod.manage',
                ],
            ],
        ];
    }

    /**
     * Super Admin only permissions — cannot be given by regular admin.
     */
    public static function superAdminOnly(): array
    {
        return [
            'employees.delete',
            'role_settings.manage',
            'activity_logs.clear',
            'employee_permissions.copy',
        ];
    }
}
