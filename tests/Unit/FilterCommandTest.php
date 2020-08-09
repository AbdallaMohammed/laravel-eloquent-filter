<?php

use Mockery as m;
use Illuminate\Filesystem\Filesystem;
use Tests\TestCase;

class FilterCommandTest extends TestCase
{
    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var null
     */
    protected $command;

    public function setUp(): void
    {
        parent::setUp();

        $this->filesystem = m::mock(Filesystem::class);
        $this->command = m::mock('LaravelEloquentFilter\Commands\MakeFilterCommand[argument]', [$this->filesystem]);
    }

    /**
     * @test
     * @dataProvider commandDataProvider
     */
    public function it_can_make_filter($argument, $class)
    {
        $this->command->shouldReceive('argument')->andReturn($argument);
        $this->command->makeClassName();

        $this->assertEquals("App\\Http\\Filters\\$class", $this->command->getClassName());
    }

    public function commandDataProvider()
    {
        return [
            ['User', 'UserFilter'],
            ['Admin\\User', 'Admin\\UserFilter'],
            ['UserFilter', 'UserFilter'],
            ['user-filter', 'UserFilter'],
            ['adminUser', 'AdminUserFilter'],
            ['admin-user', 'AdminUserFilter'],
            ['admin-user\\user-filter', 'AdminUser\\UserFilter'],
        ];
    }
}