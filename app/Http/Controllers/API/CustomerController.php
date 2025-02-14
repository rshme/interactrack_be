<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::orderBy('status')->get();

        return response()->json([
            'code_status' => Response::HTTP_OK,
            'msg_status' => 'Customers has been loaded',
            'data' => $customers
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request)
    {
        $customer = Customer::create($request->validated());

        return response()->json([
            'code_status' => Response::HTTP_CREATED,
            'msg_status' => 'Customer has been created',
            'data' => $customer
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return response()->json([
            'code_status' => Response::HTTP_OK,
            'msg_status' => 'Customer has been loaded',
            'data' => $customer->load(['interactions', 'sales'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $request, Customer $customer)
    {
        $customer->update($request->validated());

        return response()->json([
            'code_status' => Response::HTTP_OK,
            'msg_status' => 'Customer has been updated',
            'data' => $customer
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return response()->json([
            'code_status' => Response::HTTP_NO_CONTENT,
            'msg_status' => 'Customer has been deleted'
        ], Response::HTTP_NO_CONTENT);
    }
}
