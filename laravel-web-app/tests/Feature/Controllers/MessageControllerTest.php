<?php

namespace Tests\Feature\Controllers;

use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MessageControllerTest extends TestCase
{
    public function test_it_stores_the_message_and_returns_data()
    {
        $this->postJson('/api/messages')
            ->assertJsonValidationErrors(['message']);
        
        $message = "Hello world!";

        $response = $this->postJson('/api/messages', [
            'message'   => $message,
        ]);
        
        $response->assertJsonStructure([
            'data',
        ]);
        $response->assertJsonFragment([
            'message'   => $message,
        ]);

        $this->assertDatabaseHas('messages', [
            'message'   => $message,
        ]);
    }

    public function test_it_reports_the_message()
    {
        $message = Message::factory()->create();
        
        $this->postJson("/api/messages/{$message->id}/report")
            ->assertJsonValidationErrors(['correct_value']);

        $response = $this->postJson("/api/messages/{$message->id}/report", [
            'correct_value'   => false,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('reports', [
            'message_id'   => $message->id,
            'correct_value'   => 0,
        ]);
    }

    public function test_it_predicts_the_message()
    {
        $message = Message::factory()->create();

        $response = $this->postJson("/api/messages/{$message->id}/predict");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'is_spam'   => false,
        ]);
        $response->assertJsonStructure([
            'result'   => [
                'message_id',
                'is_spam',
            ],
        ]);

        $this->assertDatabaseHas('predictions', [
            'message_id'   => $message->id,
            'is_spam'   => 0,
        ]);
    }
}
