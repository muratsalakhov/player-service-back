<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ImageTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetExistingWebpImage()
    {
        $mockImage = file_get_contents(storage_path() . '/app/public/5fc13fcaaa303a46ead63656/images-webp/00fed7c9-e92f-4a9c-902b-c0122c014638.png.webp');
        $response = $this->get('/data/5fc13fcaaa303a46ead63656/00fed7c9-e92f-4a9c-902b-c0122c014638.png');
        $response
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'image/webp')
            ->assertSee($mockImage);
    }

    public function testGetNonExistingWebpImage()
    {
        $mockImage = file_get_contents(storage_path() . '/app/public/5fc13fcaaa303a46ead63656/images-png/00fed7c9-e92f-4a9c-902b-c0122c0146382.png');
        $response = $this->get('/data/5fc13fcaaa303a46ead63656/00fed7c9-e92f-4a9c-902b-c0122c0146382.png');
        $response
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'image/png')
            ->assertSee($mockImage);
    }

    public function testGetNonExistingImage()
    {
        $response = $this->get('/data/non-existing-program/non-existing-image.png');
        $response
            ->assertStatus(404)
            ->assertHeader('Content-Type', 'application/json');
    }
}
