<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\Request;

trait NormalizesProductShippingCharges
{
    protected function mergeProductShippingCharges(Request $request): void
    {
        foreach (['shipping_charge_inside_dhaka', 'shipping_charge_outside_dhaka'] as $field) {
            if (! $request->filled($field)) {
                $request->merge([$field => null]);
            }
        }
    }
}
