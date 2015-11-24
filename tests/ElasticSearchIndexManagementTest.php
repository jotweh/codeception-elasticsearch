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

    /**
     * @test
     */
    public function seeIndexExistsInElasticsearchShouldCallExistsOnClientIndicesWithIndexName()
    {
        $this->module->seeIndexExistsInElasticsearch('index-name');
        $this->indicesNamespace->shouldHaveReceived('exists')->with(m::subset(['index' => 'index-name']));
    }

    /**
     * @test
     */
    public function seeIndexExistsInElasticsearchShouldDoNothingIfIndexExists()
    {
        $this->indicesNamespace->shouldReceive('exists')->andReturn(true);
        $this->module->seeIndexExistsInElasticsearch('index-name');
    }

    /**
     * @test
     * @expectedException \PHPUnit_Framework_ExpectationFailedException
     */
    public function seeIndexExistsInElasticsearchShouldHaveFailingAssertionIfIndexDoesNot()
    {
        $this->indicesNamespace->shouldReceive('exists')->andReturn(false);
        $this->module->seeIndexExistsInElasticsearch('index-name');
    }

    /**
     * @test
     */
    public function dontSeeIndexExistsInElasticsearchShouldDoNothingIfIndexDoesNotExist()
    {
        $this->indicesNamespace->shouldReceive('exists')->andReturn(false);
        $this->module->dontSeeIndexExistsInElasticsearch('index-name');
    }

    /**
     * @test
     * @expectedException \PHPUnit_Framework_ExpectationFailedException
     */
    public function dontSeeIndexExistsInElasticsearchShouldHaveFailingAssertionIfIndexExists()
    {
        $this->indicesNamespace->shouldReceive('exists')->andReturn(true);
        $this->module->dontSeeIndexExistsInElasticsearch('index-name');
    }

    public function setUp()
    {
        parent::setUp();

        $this->indicesNamespace = m::mock('\Elasticsearch\Namespaces\IndicesNamespace');
        $this->indicesNamespace->shouldReceive('exists')->andReturn(true)->byDefault();
        $this->indicesNamespace->shouldIgnoreMissing();
        $this->client->shouldReceive('indices')->andReturn($this->indicesNamespace)->byDefault();
    }
}
