<?php
/**
 * Created by PhpStorm.
 * User: willemv
 * Date: 2015/11/24
 * Time: 10:31
 */

namespace Tests\Codeception\Module;


use Mockery as m;

class ElasticSearch_grabAnItemFromElasticsearchTest extends ElasticSearchTestCase
{
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

    /**
     * @test
     */
    public function grabAnItemFromElasticsearchShouldCallSearchWithSizeOneOnClient()
    {
        $this->module->grabAnItemFromElasticsearch(null, null, null);
        $this->client->shouldHaveReceived('search')->with(m::subset(['size' => 1]));
    }

    /**
     * @test
     */
    public function grabAnItemFromElasticsearchShouldReturnEmptyArrayIfThereAreNoHits()
    {
        $this->client->shouldReceive('search')->andReturn(['hits' => ['hits' => []]]);
        $item = $this->module->grabAnItemFromElasticsearch(null, null, null);
        $this->assertEmpty($item);
    }

    /**
     * @test
     */
    public function grabAnItemFromElasticsearchShouldReturnFirstSourceArrayIfThereIsAHits()
    {
        $expectedItem = ['apples' => 1, 'oranges' => 2];
        $this->client->shouldReceive('search')->andReturn(['hits' => ['hits' => [['_source' => $expectedItem]]]]);
        $actualItem = $this->module->grabAnItemFromElasticsearch(null, null, null);
        $this->assertEquals($expectedItem, $actualItem);
    }
}
