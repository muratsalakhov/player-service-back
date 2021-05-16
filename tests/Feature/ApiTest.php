<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAddScript()
    {
        $response = $this->postJson('/api/player/script/test',
            [
                'testScriptId' => 'testScriptId',
                'testFrames' => [
                    'testFrameId' => 'testFrameId'
                ]
            ]);
        $response
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson(
                [
                    'testScriptId' => 'testScriptId',
                    'testFrames' => [
                        'testFrameId' => 'testFrameId'
                    ]
                ]
            );
    }

    public function testGetScriptById()
    {
        $response = $this->get('/api/player/script/test');
        $response
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson(
                [
                    'testScriptId' => 'testScriptId',
                    'testFrames' => [
                        'testFrameId' => 'testFrameId'
                    ]
                ]
            );
    }

    public function testGetScriptByNullId()
    {
        $response = $this->get('/api/player/script/non-existing-id');
        $response
            ->assertStatus(404)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson(
                [
                    'status' => 'Script not found'
                ]
            );
    }

    /*public function testAddStatisticById()
    {
        $response = $this->postJson('/api/player/statistic/test',
            [
                'testScript' => 'testScriptResult',
                'testFrames' => [
                    'testFrame' => 'testFrameResult'
                ]
            ]
        );
        $response
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json')
            ->assertOk();
    }*/

    public function testGetStatisticById()
    {
        $response = $this->get('/api/player/statistic/test');
        $response
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson(
                [
                    [
                        'testScript' => 'testScriptResult',
                        'testFrames' => [
                            'testFrame' => 'testFrameResult'
                        ]
                    ]
                ]
            );
    }
}
