<?php
namespace extas\components\quality\crawlers\jira;

use extas\components\Item;
use extas\interfaces\quality\crawlers\jira\IJiraClient;
use extas\interfaces\quality\crawlers\jira\IJiraSearchJQL;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

/**
 * Class JiraClient
 *
 * @package extas\components\quality\crawlers\jira
 * @author jeyroik@gmail.com
 */
class JiraClient extends Item implements IJiraClient
{
    use TJiraConfiguration;

    /**
     * JiraClient constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config[static::FIELD__HTTP_CLIENT] = $config[static::FIELD__HTTP_CLIENT] ?? new Client();

        parent::__construct($config);
    }

    /**
     * @param IJiraSearchJQL $jql
     *
     * @return \Generator
     * @throws
     */
    public function allTickets(IJiraSearchJQL $jql)
    {
        $search = new JiraSearch($this->getResponse($jql));
        $items = $search->hasItems() ? $search->getItems() : [];

        foreach ($items as $item) {
            yield $item;
        }
    }

    /**
     * @return ClientInterface|Client
     */
    public function getHttClient()
    {
        return $this->config[static::FIELD__HTTP_CLIENT] ?? null;
    }

    /**
     * @param IJiraSearchJQL $jql
     *
     * @return array
     * @throws \Exception
     */
    protected function getResponse(IJiraSearchJQL $jql)
    {
        $config = $this->cfg();
        $isWrapped = $config->getIsWrapped();

        if ($isWrapped) {
            return $this->getWrappedResponse($jql);
        } else {
            $client = $this->getHttClient();
            $response = $client->get($jql->build(), [
                'auth' => [
                    $config->getLogin(),
                    $config->getPassword()
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            return $data;
        }
    }

    /**
     * @param IJiraSearchJQL $jql
     *
     * @return array|mixed
     * @throws \Exception
     */
    protected function getWrappedResponse(IJiraSearchJQL $jql)
    {
        $client = $this->getHttClient();
        $response = $client->get(
            $this->cfg()->getEndpoint(),
            ['json' => $jql->buildJson()]
        );

        $data = json_decode($response->getBody(), true);
        $data = $data['data'] ?? [];
        $data = $data['proxy-response'] ?? [];

        return $data;
    }

    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
