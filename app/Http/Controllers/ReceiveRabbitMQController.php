<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RabbitMQService;

class ReceiveRabbitMQController extends Controller
{
    protected $rabbitMQService;

    public function __construct(RabbitMQService $rabbitMQService)
    {
        $this->rabbitMQService = $rabbitMQService;
    }

    public function __invoke(Request $request)
    {
        try {
            $data = $request->all();

            // Processar os dados recebidos do RabbitMQ
            info('data');
            info($data);
            //$this->rabbitMQService->sendMessage($request,env('QUEUE_NAME_SEND'));
            // Retornar uma resposta de sucesso
            return response()->json(['message' => 'Mensagem do RabbitMQ processada com sucesso'], 200);
        } catch (\Exception $e) {
            // Em caso de erro, retornar uma resposta de erro
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}