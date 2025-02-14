<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Http\Requests\SaleRequest;
use App\Models\Sale;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Sale::with(['customer', 'user', 'items'])->orderBy('due_date', 'asc')->get();

        return response()->json([
            'code_status' => Response::HTTP_OK,
            'msg_status' => 'Invoices has been loaded',
            'data' => $invoices
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaleRequest $request)
    {
        $requests = $request->safe()->except('items');
        $requests['user_id'] = auth()->user()->id;
        $requests['tax'] = $requests['amount'] * 0.1;

        $sale = Sale::create($requests);
        
        if ($request->has('items')) {
            foreach ($request->items as $item) {
                $sale->items()->create($item);
            }
        }

        return response()->json([
            'code_status' => Response::HTTP_CREATED,
            'msg_status' => 'Sale has been created',
            'data' => $sale
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        return response()->json([
            'code_status' => Response::HTTP_OK,
            'msg_status' => 'Sale has been loaded',
            'data' => $sale->load(['customer', 'user', 'items'])
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SaleRequest $request, Sale $sale)
    {
        $requests = $request->safe()->except('items');
        $requests['tax'] = $requests['amount'] * 0.1;

        $sale->update($requests);
        
        if ($request->has('items')) {
            $sale->items()->delete();
            foreach ($request->items as $item) {
                $sale->items()->create($item);
            }
        }

        return response()->json([
            'code_status' => Response::HTTP_OK,
            'msg_status' => 'Sale has been updated',
            'data' => $sale
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        $sale->items()->delete();
        $sale->delete();

        return response()->json([
            'code_status' => Response::HTTP_NO_CONTENT,
            'msg_status' => 'Sale has been deleted'
        ], Response::HTTP_NO_CONTENT);
    }
}
