<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_shows_the_home_page()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSeeTextInOrder([
            config('app.name'),
        ]);
    }
}
