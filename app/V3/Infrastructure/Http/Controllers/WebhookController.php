<?php

namespace App\V3\Infrastructure\Http\Controllers;

use App\V3\Application\UseCases\Webhook\createWebhook;
use App\V3\Application\UseCases\Webhook\allWebhook;
use App\V3\Application\UseCases\Webhook\updateWebhook;
use App\V3\Application\UseCases\Webhook\deleteWebhook;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;



class WebhookController
{
    private createWebhook $createWebhook;
    private allWebhook $allWebhook;
    private updateWebhook $updateWebhook;
    private deleteWebhook $deleteWebhook;

    public function __construct(createWebhook $createWebhook, allWebhook $allWebhook, updateWebhook $updateWebhook, deleteWebhook $deleteWebhook)
    {
        $this->createWebhook = $createWebhook;
        $this->allWebhook = $allWebhook;
        $this->updateWebhook = $updateWebhook;
        $this->deleteWebhook = $deleteWebhook;
    }

    public function index(): JsonResponse
    {   
        $allWebhooks = $this->allWebhook->execute();

        if(!empty($allWebhooks)){
            return response()->json([
                'data' => array_map(fn ($Webhook) => [
                    'id' => $Webhook->getId(),
                    'type' => $Webhook->getType(),
                    'url' => $Webhook->getUrl(),
                ], $allWebhooks)
            ], 200);    
        }else{
            return response()->json([
                'message' => 'No Webhooks found',
            ], 200);    
        }
        
    }
    
    public function store(Request $request): JsonResponse
    {      
        try {
            $data = $request->all();  

            if ($request->path() == "api/v3/webhooks/subject") {
                $data['type'] = 'subjectV3';
            }elseif($request->path() == "api/v3/webhooks/project") {
                $data['type'] = 'projectV3';
            }

            $validator = Validator::make($data, [
                'url' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Validation Error', 
                    'messages' => $validator->errors()
                ], 422);
            }

            $Webhook = $this->createWebhook->execute(
                $data['type'],
                $data['url'],
            );
    
            return response()->json([
                'data' => [
                    'type' => $Webhook->getType(),
                    'url' => $Webhook->getUrl(),
                    'id' => $Webhook->getId(),
                ],
                'message' => 'Webhook created successfully'
            ], 200);
    
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation Error', 
                'messages' => $e->errors() 
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error', 
                'message' => $e->getMessage() 
            ], 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {  try {
            $data = $request->all();  

            if ($request->path() == "api/v3/webhooks/subject") {
                $data['type'] = 'subjectV3';
            }elseif($request->path() == "api/v3/webhooks/project") {
                $data['type'] = 'projectV3';
            }

            $validator = Validator::make($data, [
                'url' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Validation Error', 
                    'messages' => $validator->errors()
                ], 422);
            }
            
            $Webhook = $this->updateWebhook->execute($id, $data);
            
            if(!is_null($Webhook)){
                return response()->json([
                    'data' => [
                        'id' => $Webhook->getId(),
                        'type' => $Webhook->getType(),
                        'url' => $Webhook->getUrl(),
                    ],
                    'message' => 'Webhook updated successfully'
                ], 201);
            }else{
                return response()->json([
                    'message' => 'Webhook not found'
                ], 201);
            }
    
        } catch (\Exception $e) {
            Log::error('Error in update method', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Unable to process the request'], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {  
        $webhook = $this->deleteWebhook->execute($id);
        
        if(is_null($webhook)){
            return response()->json([
                'message' => 'Webhook not found'
            ], 201);
        }
        
        return response()->json([
            'message' => 'Webhook deleted successfully'
        ], 201);

    }
}
