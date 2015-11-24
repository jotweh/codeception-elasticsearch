<?php

namespace Tests\Codeception\Module;

use Codeception\Module\ElasticSearch;

/**
 * Created by PhpStorm.
 * User: willemv
 * Date: 2015/11/24
 * Time: 14:10
 */
class ElasticSearchConfigurationTest extends ElasticSearchTestCase
{
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
     */
    public function shouldEncapsulateHostsInArrayIfNotEncapsulated()
    {
        $module = new ElasticSearch($this->container, ['hosts' => 'test.host.com']);
        $hosts = $module->getHosts();
        $this->assertEquals('test.host.com', $hosts[0]);
    }
}