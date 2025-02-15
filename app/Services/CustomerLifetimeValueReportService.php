<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Customer;

class CustomerLifetimeValueReportService
{
    public function generate()
    {
        return Customer::select('customers.id', 'customers.first_name', 'customers.last_name',
            DB::raw('SUM(sales.amount) as total_revenue'),
            DB::raw('COUNT(DISTINCT sales.id) as total_purchases'),
            DB::raw('AVG(sales.amount) as average_purchase_value'),
            DB::raw('EXTRACT(DAY FROM (MAX(sales.created_at) - MIN(sales.created_at))) as customer_lifespan_days')
        )
        ->leftJoin('sales', 'customers.id', '=', 'sales.customer_id')
        ->where('sales.status', 'paid')
        ->groupBy('customers.id', 'customers.first_name', 'customers.last_name')
        ->get()
        ->map(function ($customer) {
            $customer->clv = $this->calculateCLV(
                $customer->average_purchase_value,
                $customer->total_purchases,
                $customer->customer_lifespan_days
            );
            return $customer;
        });
    }

    private function calculateCLV($avgPurchaseValue, $totalPurchases, $lifespanDays)
    {
        if ($lifespanDays == 0) return 0;
        
        $purchaseFrequency = $lifespanDays > 0 ? 
            ($totalPurchases / ($lifespanDays / 365)) : 0;
        
        return $avgPurchaseValue * $purchaseFrequency;
    }
}
