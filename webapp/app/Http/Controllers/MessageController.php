<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageReportRequest;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Models\Prediction;
use App\Prediction\PredictionFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MessageController extends Controller
{
    public function store(StoreMessageRequest $request)
    {
        $message = Message::firstOrCreate($request->only('message'), []);

        return new MessageResource($message);
    }

    public function predict(Message $message, Request $request)
    {
        return [
            'result'    => [
                'message_id' => 1,
                'is_spam' => false,
            ]
        ];

        $prediction = $message->prediction()->first();
        
        if ( ! $prediction) {
            $is_spam = (new PredictionFactory($message->message))
                ->predict()
                ->isSpam();

            $prediction = $message->prediction()
                ->create([
                    'is_spam'   => $is_spam,
                ]);
        }

        return [
            'result'    => [
                'message_id' => $prediction->message_id,
                'is_spam' => $prediction->is_spam,
            ]
        ];
    }

    public function report(Message $message, StoreMessageReportRequest $request)
    {
        $message->reports()->create($request->only('correct_value'));
    }
}
