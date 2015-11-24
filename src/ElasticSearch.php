<?php
/**
 * @author Jan Wyszynski
 */

namespace Codeception\Module;

use Codeception\Lib\ModuleContainer;
use Codeception\Module;
use Elasticsearch\Client;

class ElasticSearch extends Module
{
    /**
     * @var Client
     */
    private $elasticSearch = null;

    public function __construct(ModuleContainer $moduleContainer, $config = null, Client $client = null)
    {
        $this->setModuleConfiguration($config);
        $this->sanitizeModuleConfiguration();
        $this->setElasticSearchClient($client);

        parent::__construct($moduleContainer);
    }

    /**
     * @param $config
     */
    private function setModuleConfiguration($config)
    {
        $this->config = $config;
    }

    private function sanitizeModuleConfiguration()
    {
        $this->guardThatConfigurationHasHosts();
        $this->wrapConfiguredHostsInArrayIfNeeded();
    }

    private function guardThatConfigurationHasHosts()
    {
        if (!isset($this->config['hosts'])) {
            throw new \Exception('please configure hosts for ElasticSearch codeception module');
        }
    }

    private function wrapConfiguredHostsInArrayIfNeeded()
    {
        if (!is_array($this->config['hosts'])) {
            $this->config['hosts'] = array($this->config['hosts']);
        }
    }

    /**
     * @param Client $client
     */
    private function setElasticSearchClient($client)
    {
        $this->elasticSearch = $client;
    }

    public function _initialize()
    {
        if ($this->doesNotHaveElasticSearchClient()) {
            $this->buildElasticSearchClient();
        }
    }

    /**
     * @return bool
     */
    private function doesNotHaveElasticSearchClient()
    {
        return is_null($this->elasticSearch);
    }

    private function buildElasticSearchClient()
    {
        $this->elasticSearch = new Client($this->config);
    }

    /**
     * check if an item exists in a given index
     *
     * @param string $index index name
     * @param string $type item type
     * @param string $id item id
     *
     * @return array
     */
    public function seeItemExistsInElasticsearch($index, $type, $id)
    {
        return $this->elasticSearch->exists(
            [
                'index' => $index,
                'type' => $type,
                'id' => $id
            ]
        );
    }

    /**
     * grab an item from search index
     *
     * @param null $index
     * @param null $type
     * @param string $queryString
     *
     * @return array
     */
    public function grabAnItemFromElasticsearch($index = null, $type = null, $queryString = '*')
    {
        $result = $this->elasticSearch->search(
            [
                'index' => $index,
                'type' => $type,
                'q' => $queryString,
                'size' => 1
            ]
        );

        if ($this->isEmptyResult($result)) {
            return array();
        }

        return $this->getFirstItemFromResult($result);
    }

    /**
     * @param $result
     * @return bool
     */
    private function isEmptyResult($result)
    {
        return empty($result['hits']['hits']);
    }

    /**
     * @param $result
     * @return mixed
     */
    private function getFirstItemFromResult($result)
    {
        return $result['hits']['hits'][0]['_source'];
    }

    public function getHosts()
    {
        return $this->config['hosts'];
    }

    public function createIndexInElasticsearch($indexName)
    {
        $this->elasticSearch->indices()->create(['index' => $indexName]);
    }

    public function deleteIndexInElasticsearch($indexName)
    {
        $this->elasticSearch->indices()->delete(['index' => $indexName]);
    }

    public function seeIndexExistsInElasticsearch($indexName)
    {
        $this->assertTrue($this->elasticSearch->indices()->exists(['index' => $indexName]));
    }
}