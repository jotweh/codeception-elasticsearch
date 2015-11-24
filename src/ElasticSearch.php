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
        $this->config = $config;
        $this->guardThatConfigurationHasHosts();
        $this->wrapConfiguredHostsInArrayIfNeeded();
        $this->setElasticSearchClientIfInjected($client);

        parent::__construct($moduleContainer);
    }

    public function _initialize()
    {
        if (is_null($this->elasticSearch)) {
            $this->elasticSearch = new Client($this->config);
        }
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

        return !empty($result['hits']['hits'])
            ? $result['hits']['hits'][0]['_source']
            : array();
    }

    public function getHosts()
    {
        return $this->config['hosts'];
    }

    /**
     * @param Client $client
     */
    private function setElasticSearchClientIfInjected($client)
    {
        if (!is_null($client)) {
            $this->elasticSearch = $client;
        }
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
}