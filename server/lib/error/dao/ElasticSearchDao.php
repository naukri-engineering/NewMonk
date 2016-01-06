<?php

namespace NewMonk\lib\error\dao;

class ElasticSearchDao
{
    private $elasticSearchClient;

    public function __construct($elasticSearchClient) {
        $this->elasticSearchClient = $elasticSearchClient;
    }

    public function save($errorLogs) {
        $documents = [];
        foreach ($errorLogs as $errorLog) {
            $documents[] = new \Elastica\Document($id, $errorLog);
        }
        $this->elasticSearchClient->addDocuments($documents);
    }

    public function getCount($appId, $from, $until) {
        $fromGmtDate = gmdate('Y-m-d H:i:s', $from);
        $untilGmtDate = gmdate('Y-m-d H:i:s', $until);

        $query = [
            'query' => [
                'filtered' => [
                    'query' => [
                        'query_string' => [
                            'query' => 'appId:'.$appId,
                            'analyze_wildcard' => true,
                            'lowercase_expanded_terms' => false
                        ]
                    ],
                    'filter' => [
                        'bool' => [
                            'must' => [
                                [
                                    'range' => [
                                        'exception.gmtTimestamp' => [
                                            'gte' => $fromGmtDate,
                                            'lte' => $untilGmtDate
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'size' => 0,
            'aggs' => [
                '1' => [
                    'sum' => [
                        'field' => 'exception.count'
                    ]
                ]
            ]
        ];
        $response = $this->elasticSearchClient->request('_search', \Elastica\Request::GET, $query);
        return $responseArray = $response->getData()['aggregations'][1]['value'];
    }
}
