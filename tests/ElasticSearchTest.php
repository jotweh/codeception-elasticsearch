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
     * @var ElasticSearch
     */
    private $module;
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
    public function seeItemExistsInElasticsearchShouldCallExistsWithIndexNameOnClient()
    {
        $this->module->seeItemExistsInElasticsearch('index-name', null, null);
        $this->client->shouldHaveReceived('exists')->with(m::subset(['index' => 'index-name']))->once();
    }

    /**
     * @test
     */
    public function seeItemExistsInElasticsearchShouldCallExistsWithTypeOnClient()
    {
        $this->module->seeItemExistsInElasticsearch(null, 'document-type', null);
        $this->client->shouldHaveReceived('exists')->with(m::subset(['type' => 'document-type']))->once();
    }

    /**
     * @test
     */
    public function seeItemExistsInElasticsearchShouldCallExistsWithIdOnClient()
    {
        $this->module->seeItemExistsInElasticsearch(null, null, 'document-id');
        $this->client->shouldHaveReceived('exists')->with(m::subset(['id' => 'document-id']))->once();
    }

    /**
     * @test
     */
    public function grabAnItemFromElasticsearchShouldCallSearchWithIndexOnClient()
    {
        $this->module->grabAnItemFromElasticsearch('index-name');
        $this->client->shouldHaveReceived('search')->with(m::subset(['index' => 'index-name']));
    }

    /**
     * @test
     */
    public function grabAnItemFromElasticsearchShouldCallSearchWithTypeOnClient()
    {
        $this->module->grabAnItemFromElasticsearch(null, 'some-document-type');
        $this->client->shouldHaveReceived('search')->with(m::subset(['type' => 'some-document-type']));
    }

    /**
     * @test
     */
    public function grabAnItemFromElasticsearchShouldCallSearchWithQueryStringOnClient()
    {
        $this->module->grabAnItemFromElasticsearch(null, null, 'something');
        $this->client->shouldHaveReceived('search')->with(m::subset(['q' => 'something']));
    }

    public function setUp()
    {
        /** @var ModuleContainer container */
        $this->container = m::mock('\Codeception\Lib\ModuleContainer');
        $this->client = m::mock('\Elasticsearch\Client');
        $this->client->shouldIgnoreMissing();
        $this->module = new ElasticSearch($this->container, ['hosts' => []], $this->client);
    }

    public function tearDown()
    {
        m::close();
    }
}
