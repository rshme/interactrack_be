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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaleRequest $request)
    {
        $sale = Sale::create($request->safe()->except('items'));
        
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
        $sale->update($request->safe()->except('items'));
        
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
