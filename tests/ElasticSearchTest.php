<?php
/**
 * Created by PhpStorm.
 * User: willemv
 * Date: 2015/11/24
 * Time: 10:31
 */

namespace Tests\Codeception\Module;


use Mockery as m;
use Codeception\Lib\ModuleContainer;
use Codeception\Module\ElasticSearch;

class ElasticSearchTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \Exception
     * @expectedExceptionMessage please configure hosts for ElasticSearch codeception module
     */
    public function shouldNotInstantiateWithoutConfigArray()
    {
        /** @var ModuleContainer | m\Mock $container */
        $container = m::mock('\Codeception\Lib\ModuleContainer');
        new ElasticSearch($container, null);
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function shouldNotInstantiateWithoutHostsArrayInConfigArray()
    {
        /** @var ModuleContainer | m\Mock $container */
        $container = m::mock('\Codeception\Lib\ModuleContainer');
        new ElasticSearch($container, ['hosts' => null]);
    }
}
