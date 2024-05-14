<?php

require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use PhpAmqpLib\Connection\AMQPStreamConnection;
use GuzzleHttp\Client;

//info('ativou');

error_log('ativou');

$connection = new AMQPStreamConnection(
    env('RABBITMQ_HOST'),
    env('RABBITMQ_PORT'),
    env('RABBITMQ_LOGIN'),
    env('RABBITMQ_PASSWORD')
);

$channel = $connection->channel();

$queueName = env("QUEUE_NAME_SEND");

$channel->queue_declare($queueName, false, true, false, false);

$callback = function ($msg) {

    $data = json_decode($msg->body, true);

    info( 'chegou consumir');

    try {
        $client = new Client();
        $response = $client->post(env("APP_URL") . "/api/receiverabbit", [
            'json' => $data,
        ]);

        if ($response->getStatusCode() == 201) {
            $msg->ack();
            echo "Sucesso\n";
        } else {
            $statusCode = $response->getStatusCode();
            $responseBody = $response->getBody()->getContents();
            echo $responseBody;
        }
    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage();
    }
};

$channel->basic_consume($queueName, '', false, false, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}

$channel->close();
$connection->close();
