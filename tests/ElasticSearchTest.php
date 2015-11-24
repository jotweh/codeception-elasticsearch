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
     * @var Client | m\Mock
     */
    private $client;
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
        $module = new ElasticSearch($this->container, ['hosts' => []], $this->client);
        $module->seeItemExistsInElasticsearch('index-name', null, null);
        $this->client->shouldHaveReceived('exists')->with(m::subset(['index' => 'index-name']))->once();
    }

    /**
     * @test
     */
    public function seeItemExistsInElasticsearchShouldPassTypeToClient()
    {
        $module = new ElasticSearch($this->container, ['hosts' => []], $this->client);
        $module->seeItemExistsInElasticsearch(null, 'document-type', null);
        $this->client->shouldHaveReceived('exists')->with(m::subset(['type' => 'document-type']))->once();
    }

    public function setUp()
    {
        $this->container = m::mock('\Codeception\Lib\ModuleContainer');
        $this->client = m::mock('\Elasticsearch\Client');
        $this->client->shouldIgnoreMissing();
    }

    public function tearDown()
    {
        m::close();
    }
}
