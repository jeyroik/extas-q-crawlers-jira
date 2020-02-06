<?php
namespace extas\interfaces\quality\crawlers\jira;

use extas\interfaces\IItem;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

/**
 * Interface IJiraClient
 *
 * @package extas\interfaces\quality\crawlers\jira
 * @author jeyroik@gmail.com
 */
interface IJiraClient extends IItem
{
    const SUBJECT = 'extas.quality.crawler.jira.client';

    const FIELD__HTTP_CLIENT = 'http_client';

    /**
     * @return string
     */
    public function getProjectKey(): string;

    /**
     * @param string $key
     *
     * @return IJiraClient
     */
    public function setProjectKey(string $key): IJiraClient;

    /**
     * @param array $returnFields
     *
     * @return \Generator
     */
    public function allStories(array $returnFields = []);

    /**
     * @param array $keys
     * @param array $returnFields
     *
     * @return \Generator
     */
    public function allTickets(array $keys, array $returnFields = []);

    /**
     * @return ClientInterface|Client
     */
    public function getHttClient();
}
