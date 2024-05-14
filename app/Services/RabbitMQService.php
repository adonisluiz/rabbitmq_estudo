<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Exception;
use Illuminate\Support\Facades\Http;

class RabbitMQService
{

    function sendMessage($request,$queueName)
    {
        try {
                $connection = new AMQPStreamConnection(
                env('RABBITMQ_HOST'),
                env('RABBITMQ_PORT'),
                env('RABBITMQ_LOGIN'),
                env('RABBITMQ_PASSWORD')
            );

            $channel = $connection->channel();

            $channel->queue_declare($queueName, false, true, false, false);

            $msg = new AMQPMessage($request);

            $channel->basic_publish($msg, '', $queueName);

            return 'Dados enviados com sucesso!';

            $channel->close();
            $connection->close();
        } catch (Exception $th) {
            return $th->getMessage();
        }
    }
}