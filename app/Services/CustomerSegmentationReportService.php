<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Customer;

class CustomerSegmentationReportService
{
    public function generateRFMSegmentation()
    {
        $customers = Customer::select('customers.id', 'customers.first_name', 'customers.last_name')
            ->addSelect([
                // Recency
                DB::raw('EXTRACT(DAY FROM (NOW() - MAX(sales.created_at))) as days_since_last_purchase'),
                // Frequency
                DB::raw('COUNT(sales.id) as purchase_count'),
                // Monetary
                DB::raw('SUM(sales.amount) as total_spent')
            ])
            ->leftJoin('sales', 'customers.id', '=', 'sales.customer_id')
            ->where('sales.status', 'paid')
            ->groupBy('customers.id', 'customers.first_name', 'customers.last_name')
            ->get();

        return $customers->map(function ($customer) {
            $customer->rfm_score = $this->calculateRFMScore(
                $customer->days_since_last_purchase,
                $customer->purchase_count,
                $customer->total_spent
            );
            $customer->segment = $this->determineSegment($customer->rfm_score);
            return $customer;
        });
    }

    private function calculateRFMScore($recency, $frequency, $monetary)
    {
        // Score each component from 1-5
        $recencyScore = $this->scoreRecency($recency);
        $frequencyScore = $this->scoreFrequency($frequency);
        $monetaryScore = $this->scoreMonetary($monetary);

        return [
            'recency' => $recencyScore,
            'frequency' => $frequencyScore,
            'monetary' => $monetaryScore,
            'total' => $recencyScore + $frequencyScore + $monetaryScore
        ];
    }

    private function determineSegment($rfmScore)
    {
        $total = $rfmScore['total'];

        if ($total >= 13) return 'champions';
        if ($total >= 10) return 'loyal_customers';
        if ($total >= 7) return 'potential_loyalists';
        if ($total >= 5) return 'at_risk';
        return 'lost_customers';
    }

    private function scoreRecency($days)
    {
        if ($days <= 30) return 5;
        if ($days <= 60) return 4;
        if ($days <= 90) return 3;
        if ($days <= 180) return 2;
        return 1;
    }

    private function scoreFrequency($count)
    {
        if ($count >= 20) return 5;
        if ($count >= 10) return 4;
        if ($count >= 5) return 3;
        if ($count >= 2) return 2;
        return 1;
    }

    private function scoreMonetary($amount)
    {
        if ($amount >= 10000) return 5;
        if ($amount >= 5000) return 4;
        if ($amount >= 2500) return 3;
        if ($amount >= 1000) return 2;
        return 1;
    }
}
