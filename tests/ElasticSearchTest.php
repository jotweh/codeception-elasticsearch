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
     * @var ModuleContainer | m\Mock
     */
    private $container;

    /**
     * @test
     * @expectedException \Exception
     * @expectedExceptionMessage please configure hosts for ElasticSearch codeception module
     */
    public function shouldNotInstantiateWithoutConfigArray()
    {
        new ElasticSearch($this->container, null);
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function shouldNotInstantiateWithoutHostsArrayInConfigArray()
    {
        new ElasticSearch($this->container, ['hosts' => null]);
    }

    public function setUp()
    {
        $this->container = m::mock('\Codeception\Lib\ModuleContainer');
    }

    public function tearDown()
    {
        m::close();
    }
}
