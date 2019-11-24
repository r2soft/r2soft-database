<?php


namespace R2Soft\R2Database\Tests;


use Orchestra\Testbench\TestCase;

abstract class AbstractTestCase extends TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp():void
    {
        parent::setUp();
        $this->loadMigrationsFrom(__DIR__.'/resources/migrations');
    }
    public function migrate()
    {
        $this->loadLaravelMigrations(['--database' => 'testbench']);
        $this->artisan('migrate', ['--database' => 'testbench'])->run();
    }

    public function getPackageProviders($app)
    {
//        return[
//            \Cviebrock\EloquentSluggable\ServiceProvider::class
//        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}
