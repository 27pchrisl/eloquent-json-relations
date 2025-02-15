<?php

namespace Tests\Concatenation\BelongsToJson;

use Illuminate\Database\Capsule\Manager as DB;
use Tests\Models\Country;
use Tests\TestCase;

class LastPositionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (DB::connection()->getDriverName() === 'sqlite') {
            $this->markTestSkipped();
        }
    }

    public function testLazyLoading()
    {
        $roles = Country::find(71)->roles;

        $this->assertEquals([1, 2], $roles->pluck('id')->all());
    }

    public function testLazyLoadingWithObjects()
    {
        if (DB::connection()->getDriverName() === 'sqlsrv') {
            $this->markTestSkipped();
        }

        $roles = Country::find(71)->roles2;

        $this->assertEquals([1, 2], $roles->pluck('id')->all());
    }

    public function testEagerLoading()
    {
        $countries = Country::with('roles')->get();

        $this->assertEquals([1, 2], $countries[0]->roles->pluck('id')->all());
        $this->assertEquals([], $countries[1]->roles->pluck('id')->all());
        $this->assertEquals([2, 3], $countries[2]->roles->pluck('id')->all());
    }

    public function testEagerLoadingWithObjects()
    {
        if (DB::connection()->getDriverName() === 'sqlsrv') {
            $this->markTestSkipped();
        }

        $countries = Country::with('roles2')->get();

        $this->assertEquals([1, 2], $countries[0]->roles2->pluck('id')->all());
        $this->assertEquals([], $countries[1]->roles2->pluck('id')->all());
        $this->assertEquals([2, 3], $countries[2]->roles2->pluck('id')->all());
    }

    public function testExistenceQuery()
    {
        $countries = Country::has('roles')->get();

        $this->assertEquals([71, 73], $countries->pluck('id')->all());
    }

    public function testExistenceQueryWithObjects()
    {
        if (DB::connection()->getDriverName() === 'sqlsrv') {
            $this->markTestSkipped();
        }

        $countries = Country::has('roles2')->get();

        $this->assertEquals([71, 73], $countries->pluck('id')->all());
    }
}
