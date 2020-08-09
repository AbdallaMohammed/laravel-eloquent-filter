<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Tests\Filters\TestModelFilter;
use Tests\Models\TestModel;
use Tests\TestCase;

class FilterTest extends TestCase
{
    /**
     * @var TestModel
     */
    protected $model;

    /**
     * @var null
     */
    protected $command;

    public function setUp(): void
    {
        parent::setUp();

        // Create dummy data
        TestModel::create([
            'name' => 'Abdallah',
            'email' => 'abdallah@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->model = new TestModel();
    }

    /** @test */
    public function it_can_filter_model()
    {
        $count = $this->model->filter(new TestModelFilter([
            'name' => 'Abd',
        ]))->count();

        $this->assertEquals($count, 1);

        $count = $this->model->filter(new TestModelFilter([
            'name' => 'WORLD',
        ]))->count();

        $this->assertEquals($count, 0);
    }

    /** @test */
    public function it_can_ignore_empty_values()
    {
        Config::set('laravel-eloquent-filter.ignore_empty', true);
        
        $count = $this->model->filter(new TestModelFilter([
            'name' => '',
        ]))->count();

        $this->assertEquals($count, 1);
    }
}