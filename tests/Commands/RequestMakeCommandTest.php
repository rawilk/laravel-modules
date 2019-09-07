<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Concerns\TestsGenerators;
use Spatie\Snapshots\MatchesSnapshots;

class RequestMakeCommandTest extends BaseTestCase
{
    use MatchesSnapshots, TestsGenerators;

    /** @test */
    public function it_generates_a_new_form_request_class()
    {
        $this->artisan('module:make-request', ['name' => 'CreateBlogPostRequest', 'module' => 'Blog']);

        $path = $this->modulePath . '/Http/Requests/CreateBlogPostRequest.php';

        $this->assertTrue(is_file($path));
        $this->assertMatchesSnapshot($this->finder->get($path));
    }

    /** @test */
    public function it_can_change_the_default_namespace()
    {
        $this->app['config']->set('modules.paths.generator.request.path', 'CustomPath');

        $this->artisan('module:make-request', ['name' => 'CreateBlogPostRequest', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/CustomPath/CreateBlogPostRequest.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_change_the_namespace_specifically()
    {
        $this->app['config']->set('modules.paths.generator.request.namespace', 'CustomPath');

        $this->artisan('module:make-request', ['name' => 'CreateBlogPostRequest', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/Http/Requests/CreateBlogPostRequest.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_generate_a_request_class_in_a_nested_namespace()
    {
        $this->artisan('module:make-request', ['name' => 'Nested/Path/CreateBlogPostRequest', 'module' => 'Blog']);

        $path = $this->modulePath . '/' . 'Http/Requests/Nested/Path/CreateBlogPostRequest.php';

        $this->assertTrue(is_file($path));
        $this->assertMatchesSnapshot($this->finder->get($path));
    }

    /** @test */
    public function it_can_use_a_custom_base_request_class()
    {
        $this->artisan('module:make-request', [
            'name'         => 'CreateBlogPostRequest',
            'module'       => 'Blog',
            '--base_class' => 'App/Http/Requests/BaseRequest'
        ]);

        $file = $this->finder->get($this->modulePath . '/Http/Requests/CreateBlogPostRequest.php');

        $this->assertMatchesSnapshot($file);
    }
}
