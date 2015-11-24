<?php
/**
 * Created by PhpStorm.
 * User: willemv
 * Date: 2015/11/24
 * Time: 14:19
 */

namespace Tests\Codeception\Module;


use Mockery as m;

class ElasticSearch_createIndexInElasticsearchTest extends ElasticSearchTestCase
{
    /**
     * @test
     */
    public function createIndexInElasticsearchShouldCallIndicesCreateOnClientWithIndexName()
    {
        $indicesNamespace = m::mock('\Elasticsearch\Namespaces\IndicesNamespace');
        $indicesNamespace->shouldIgnoreMissing();
        $this->client->shouldReceive('indices')->andReturn($indicesNamespace);

        $this->module->createIndexInElasticsearch('index-name');

        $indicesNamespace->shouldHaveReceived('create')->with(m::subset(['index' => 'index-name']));
    }
}
