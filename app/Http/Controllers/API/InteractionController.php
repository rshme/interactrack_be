<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\InteractionRequest;
use App\Models\Interaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InteractionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $interactions = Interaction::with(['customer', 'user'])->orderBy('interaction_date', 'desc')->get();

        return response()->json([
            'code_status' => Response::HTTP_OK,
            'msg_status' => 'Interactions has been loaded',
            'data' => $interactions
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InteractionRequest $request)
    {
        $requests = $request->validated();
        $requests['user_id'] = auth()->user()->id;

        $interaction = Interaction::create($requests);

        return response()->json([
            'code_status' => Response::HTTP_CREATED,
            'msg_status' => 'Interaction has been created',
            'data' => $interaction
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Interaction $interaction)
    {

        return response()->json([
            'code_status' => Response::HTTP_OK,
            'msg_status' => 'Interaction has been loaded',
            'data' => $interaction->load(['customer', 'user'])
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InteractionRequest $request, Interaction $interaction)
    {
        $interaction->update($request->validated());

        return response()->json([
            'code_status' => Response::HTTP_OK,
            'msg_status' => 'Interaction has been updated',
            'data' => $interaction
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Interaction $interaction)
    {
        $interaction->delete();

        return response()->json([
            'code_status' => Response::HTTP_NO_CONTENT,
            'msg_status' => 'Interaction has been deleted'
        ], Response::HTTP_NO_CONTENT);
    }
}
