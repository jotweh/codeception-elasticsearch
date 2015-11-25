<?php

namespace Tests\Codeception\Module;


use Mockery as m;

/**
 * Created by PhpStorm.
 * User: willemv
 * Date: 2015/11/24
 * Time: 14:13
 */
class ElasticSearch_seeItemExistsInElasticsearchTest extends ElasticSearchTestCase
{
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
}