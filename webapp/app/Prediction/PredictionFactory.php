<?php

namespace App\Prediction;

use Exception;
use App\Prediction\PredictionServerException;
use Illuminate\Support\Facades\Http;

class PredictionFactory 
{
    /**
     * 
     */
    protected string $url;

    /**
     * 
     */
    protected string $token;
    
    /**
     * 
     */
    protected int $timeout = 30;
    
    /**
     * 
     */
    protected string $message;
    
    /**
     * 
     */
    protected bool $isSpam;

    /**
     * Undocumented function
     *
     * @param string $message
     */
    public function __construct(string $message) 
    {
        $this->message = $message;
        $this->url = config('app.prediction_url');
        $this->token = config('app.prediction_url_token', 'token');
    }

    /**
     * Undocumented function
     *
     * @return PredictionFactory
     */
    public function predict(): PredictionFactory
    {
        $response = $this->response();

        $responseObject = json_decode($response);

        try {
            if (is_object($responseObject) && isset($responseObject->spam)) {
                if ($responseObject->spam === 1) {
                $this->isSpam = true;
                } else if ($responseObject->spam === 0) {
                    $this->isSpam = false;
                } else {
                    throw new Exception();
                }
            } else {
                throw new Exception();
            }
            
        } catch (Exception $e) {
            throw new PredictionServerException("The prediction server threw an unknown value \"{$response}\", check with the prediction server administration.");
        }

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return boolean
     */
    public function isSpam(): bool 
    {
        return $this->isSpam;
    }

    /**
     * Undocumented function
     *
     * @return boolean
     */
    public function isHam(): bool 
    {
        return ! $this->isSpam();
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    protected function response(): string
    {
        $response = Http::timeout($this->timeout)
            ->acceptJson()
            ->asForm()
            ->post($this->url, [
                'message'   => $this->message,
                'token'   => $this->token,
            ]);

        if ($response->clientError()) {
            throw new PredictionServerException("Client error while retrieving information form the prediction server, status code is {$response->status()}, check if the message is not null.");
        }
        
        if ($response->serverError()) {
            throw new PredictionServerException("Server error while retrieving information form the prediction server, status code is {$response->status()}, check with the prediction server administration.");
        }

        return $response->body();
    }
}
