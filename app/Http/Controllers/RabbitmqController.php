<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Services\RabbitMQService as RabbitMQService;

class RabbitmqController extends Controller
{
    protected $rabbitMQService;

    public function __construct(RabbitMQService $rabbitMQService)
    {
        $this->rabbitMQService = $rabbitMQService;

    }

    public function store(Request $request)
    {
        try {
            
            $valorx = $this->rabbitMQService->sendMessage(json_encode($request), env('QUEUE_NAME_SEND'));
            return $valorx;
        } catch (Exception $th) {
            return response()->json($th->getMessage());
        }
    }
}
