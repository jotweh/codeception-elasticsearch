<?php
/**
 * Created by PhpStorm.
 * User: willemv
 * Date: 2015/11/24
 * Time: 15:05
 */

namespace Tests\Codeception\Module;


use Mockery as m;

class ElasticSearchIndexingTest extends ElasticSearchTestCase
{
    /**
     * @test
     */
    public function indexAnItemInElasticsearchShouldCallIndexOnClientWithIndexName()
    {
        $this->module->indexAnItemInElasticSearch('index-name', null, null);
        $this->client->shouldHaveReceived('index')->with(m::subset(['index' => 'index-name']));
    }

    /**
     * @test
     */
    public function indexAnItemInElasticsearchShouldCallIndexOnClientWithType()
    {
        $this->module->indexAnItemInElasticSearch(null, 'document-type', null);
        $this->client->shouldHaveReceived('index')->with(m::subset(['type' => 'document-type']));
    }

    /**
     * @test
     */
    public function indexAnItemInElasticsearchShouldCallIndexOnClientWithIdIfSpecified()
    {
        $this->module->indexAnItemInElasticSearch(null, null, null, 123);
        $this->client->shouldHaveReceived('index')->with(m::subset(['id' => 123]));
    }

    /**
     * @test
     */
    public function indexAnItemInElasticsearchShouldCallIndexOnClientWithoutIdIfNotSpecified()
    {
        $this->module->indexAnItemInElasticSearch(null, null, null);
        $this->client->shouldHaveReceived('index')->with(m::on(function ($actual) {
            return !array_key_exists('id', $actual);
        }));
    }

    /**
     * @test
     */
    public function indexAnItemInElasticsearchShouldCallIndexOnClientWithDocumentBody()
    {
        $documentBody = [
            'apples' => 1,
            'oranges' => 2
        ];

        $this->module->indexAnItemInElasticSearch(null, null, $documentBody);
        $this->client->shouldHaveReceived('index')->with(m::subset(['body' => $documentBody]));
    }
}
