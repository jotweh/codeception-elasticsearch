<?php

namespace Tests\Codeception\Module;

use Mockery as m;
use Codeception\Lib\ModuleContainer;
use Codeception\Module\ElasticSearch;
use Elasticsearch\Client;

/**
 * Created by PhpStorm.
 * User: willemv
 * Date: 2015/11/24
 * Time: 14:07
 */
class ElasticSearchTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ElasticSearch
     */
    protected $module;
    /**
     * @var Client | m\Mock
     */
    protected $client;
    /**
     * @var ModuleContainer | m\Mock
     */
    protected $container;

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