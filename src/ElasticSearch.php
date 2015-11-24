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
        $this->configureModuleWithSettingsInArray($config);

        if (!is_null($client)) {
            $this->elasticSearch = $client;
        }

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
     * @param $configArray
     * @throws \Exception
     */
    private function configureModuleWithSettingsInArray($configArray)
    {
        if (!isset($configArray['hosts'])) {
            throw new \Exception('please configure hosts for ElasticSearch codeception module');
        }

        if (isset($configArray['hosts']) && !is_array($configArray['hosts'])) {
            $configArray['hosts'] = array($configArray['hosts']);
        }
        $this->config = (array)$configArray;
    }
}