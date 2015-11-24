<?php
/**
 * Created by PhpStorm.
 * User: willemv
 * Date: 2015/11/24
 * Time: 14:19
 */

namespace Tests\Codeception\Module;


use Elasticsearch\Namespaces\IndicesNamespace;
use Mockery as m;

class ElasticSearchIndexManagementTest extends ElasticSearchTestCase
{
    /** @var IndicesNamespace | m\Mock */
    private $indicesNamespace;

    /**
     * @test
     */
    public function createIndexInElasticsearchShouldCallCreateOnClientIndicesWithIndexName()
    {
        $this->module->createIndexInElasticsearch('index-name');
        $this->indicesNamespace->shouldHaveReceived('create')->with(m::subset(['index' => 'index-name']));
    }

    /**
     * @test
     */
    public function deleteIndexInElasticsearchShouldCallDeleteOnClientIndicesWithIndexName()
    {
        $this->module->deleteIndexInElasticsearch('index-name');
        $this->indicesNamespace->shouldHaveReceived('delete')->with(m::subset(['index' => 'index-name']));
    }

    public function setUp()
    {
        parent::setUp();

        $this->indicesNamespace = m::mock('\Elasticsearch\Namespaces\IndicesNamespace');
        $this->indicesNamespace->shouldIgnoreMissing();
        $this->client->shouldReceive('indices')->andReturn($this->indicesNamespace)->byDefault();
    }
}
