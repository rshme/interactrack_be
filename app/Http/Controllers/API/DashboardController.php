<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Interaction;
use App\Models\Sale;
use Illuminate\Http\Response;
use App\Services\SalesFunnelReportService;
use App\Services\CustomerLifetimeValueReportService;
use App\Services\CustomerSegmentationReportService;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    private $salesFunnelReport;
    private $customerLifetimeValueReport;
    private $customerSegmentationReport;

    public function __construct(
        SalesFunnelReportService $salesFunnelReport,
        CustomerLifetimeValueReportService $customerLifetimeValueReport,
        CustomerSegmentationReportService $customerSegmentationReport
    ) {
        $this->salesFunnelReport = $salesFunnelReport;
        $this->customerLifetimeValueReport = $customerLifetimeValueReport;
        $this->customerSegmentationReport = $customerSegmentationReport;
    }

    public function getAnalytics()
    {
        $totalInvoicePaid = Sale::whereStatus('paid')->count();
        $totalInteractionCompleted = Interaction::whereStatus('completed')->count();

        return response()->json([
            'code_status' => Response::HTTP_OK,
            'msg_status' => 'Dashboard has been loaded',
            'data' => [
                'total_invoice_paid' => $totalInvoicePaid,
                'total_interaction_completed' => $totalInteractionCompleted,
                'sales_funnel' => $this->salesFunnelReport->generate(
                    Carbon::now()->subMonths(3),
                    Carbon::now()
                ),
                'top_customers_by_clv' => $this->customerLifetimeValueReport
                    ->generate()
                    ->sortByDesc('clv')
                    ->take(10)
                    ->values(),
                'customer_segments' => $this->customerSegmentationReport
                    ->generateRFMSegmentation()
                    ->groupBy('segment')
                    ->map(function ($customers) {
                        return [
                            'count' => $customers->count(),
                            'total_revenue' => $customers->sum('total_spent'),
                            'average_clv' => $customers->avg('clv')
                        ];
                    })
            ]
        ], Response::HTTP_OK);
    }
}
