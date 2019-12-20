<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MenuApiTests extends TestCase
{
    use RefreshDatabase;

    /**
     * Tests shop index without params to see if it works
     *
     * @return void
     */
    public function testShopIndex()
    {
        $response = $this->json('POST', '/api/menus');
        $response->assertStatus(201);
    }
}
