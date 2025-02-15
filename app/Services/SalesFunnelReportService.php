<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\Customer;

class SalesFunnelReportService
{
    public function generate($startDate = null, $endDate = null)
    {
        $query = Sale::query();
        
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return [
            'prospects' => Customer::where('status', 'active')->count(),
            'negotiations' => Sale::where('status', 'draft')->count(),
            'proposals' => Sale::where('status', 'sent')->count(),
            'won_deals' => $query->where('status', 'paid')->count(),
            'lost_deals' => Sale::where('status', 'cancelled')->count(),
            'conversion_rates' => [
                'prospect_to_negotiation' => $this->calculateConversionRate('active', 'draft'),
                'negotiation_to_won' => $this->calculateConversionRate('active', 'paid'),
            ]
        ];
    }

    private function calculateConversionRate($fromStatus, $toStatus)
    {
        $fromCount = Customer::where('status', $fromStatus)->count();
        $toCount = Sale::where('status', $toStatus)->count();
        
        return $fromCount > 0 ? ($toCount / $fromCount) * 100 : 0;
    }
}
