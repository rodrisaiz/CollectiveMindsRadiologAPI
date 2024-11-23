<?php

namespace App\Http\V2\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Webhook;

class WebhookController extends Controller
{

    public function index()
    {
        $webhook = Webhook::all();

        if(!$webhook->isEmpty()){
        return response()->json([
            'data' => $webhook
        ], 200);
        }else{
            return response()->json([
                'data' => 'There are not webhooks'
            ], 200);
        }
    }


    public function store(Request $request)
    {
        $data = $request->all(); 

        if ($request->path() == "api/v2/webhooks/subject") {
            $data['type'] = 'subjectV2';
        }elseif($request->path() == "api/v2/webhooks/project") {
            $data['type'] = 'projectV2';
        }

        $validator = Validator::make($data, [
            'type' => 'required|string|max:255',
            'url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation Error', 
                'messages' => $validator->errors()
            ], 422);
        }

        $existingWebhook = Webhook::where('type', $data['type'])->first();

        if ($existingWebhook) {
            $existingWebhook->delete(); 
        }

        $webhook = Webhook::create($data);

        return response()->json([
            'data' => $webhook, 
            'message' => 'Webhook created successfully'
        ], 201);
    }

    public function update(Request $request, Webhook $webhook)
    {
                
        try{
            $data = $request->all(); 
            
            if ($request->path() == "api/v2/webhooks/subject") {
                $data['type'] = 'subjectV2';
            }elseif($request->path() == "api/v2/webhooks/project") {
                $data['type'] = 'projectV2';
            }

            $validator = Validator::make($data, [
                'url' => 'required|url',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Validation Error', 
                    'messages' => $validator->errors()
                ], 422);
            }
        
            $webhook->update($data);

            return response()->json([
                'data' => $webhook, 
                'message' => 'Webhook updated successfully'
            ], 200);
        }catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Webhook not found'
            ], 404);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $webhook = Webhook::findOrFail($id);
          
            return response()->json([
                'data' => $webhook
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Webhook not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error retrieving subject', 
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $webhook = Webhook::findOrFail($id);
            $webhook->delete();
            return response()->json([
                'message' => 'Webhook deleted successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Webhook not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error deleting subject', 
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
