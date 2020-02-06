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
     * @param IJiraSearchJQL $jql
     *
     * @return \Generator
     */
    public function allTickets(IJiraSearchJQL $jql);

    /**
     * @return ClientInterface|Client
     */
    public function getHttClient();
}
