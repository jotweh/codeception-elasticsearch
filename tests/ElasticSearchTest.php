<?php
/**
 * Created by PhpStorm.
 * User: willemv
 * Date: 2015/11/24
 * Time: 10:31
 */

namespace Tests\Codeception\Module;


use Elasticsearch\Client;
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

    /**
     * @test
     * @expectedException \Exception
     * @expectedExceptionMessage Could not resolve host: test.3.1415.nonexistent-host.com
     */
    public function initializeShouldCreateClientWithConfiguredHostsIfNoClientIsPassedToConstructor()
    {
        $module = new ElasticSearch($this->container, ['hosts' => ['test.3.1415.nonexistent-host.com']]);
        $module->_initialize();
        $module->seeItemExistsInElasticsearch('any-indexname', 'any-type', 'any-id');
    }

    /**
     * @test
     */
    public function seeItemExistsInElasticsearchShouldPassIndexNameToClient()
    {
        $client = m::mock('\Elasticsearch\Client');
        $client->shouldIgnoreMissing();

        $module = new ElasticSearch($this->container, ['hosts' => []], $client);
        $module->_initialize();
        $module->seeItemExistsInElasticsearch('index-name', null, null);

        $client->shouldHaveReceived('exists')->with(m::subset(['index' => 'index-name']))->once();
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
