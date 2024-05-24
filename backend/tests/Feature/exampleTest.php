<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class exampleTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertOk();
        $strings=[
            '<div class="example">',
            'sldkjdslfjlkdjsfkdfs',
            '</div>',
        ];
        $response->assertSeeInOrder($strings, false);

    }
}
