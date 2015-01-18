<?php

namespace Laracasts\Behat\Context\Services;

use GuzzleHttp\Client;
use Config;
use Exception;

trait MailTrap
{

    /**
     * The MailTrap configuration.
     *
     * @var integer
     */
    protected $mailTrapInboxId;

    /**
     * The MailTrap API Key.
     *
     * @var string
     */
    protected $mailTrapApiKey;

    /**
     * The Guzzle client.
     *
     * @var Client
     */
    protected $client;

    /**
     * Get the configuration for MailTrap.
     *
     * @param integer|null $inboxId
     * @throws Exception
     */
    protected function applyMailTrapConfiguration($inboxId = null)
    {
        if (is_null($config = Config::get('services.mailtrap'))) {
            throw new Exception(
                'Set "secret" and "default_inbox" keys for "mailtrap" in "config/services.php."'
            );
        }

        $this->mailTrapInboxId = $inboxId ?: $config['default_inbox'];
        $this->mailTrapApiKey = $config['secret'];
    }

    /**
     * Fetch a MailTrap inbox.
     *
     * @param  integer|null $inboxId
     * @return mixed
     */
    protected function fetchInbox($inboxId = null)
    {
        if ( ! $this->alreadyConfigured()) {
            $this->applyMailTrapConfiguration($inboxId);
        }

        return $this->requestClient()
            ->get($this->getMailTrapMessagesUrl())
            ->json();
    }

    /**
     *
     * Empty the MailTrap inbox.
     *
     * @AfterScenario @mail
     */
    public function emptyInbox()
    {
        $this->requestClient()->patch($this->getMailTrapCleanUrl(), ['future' => true]);
    }

    /**
     * Get the MailTrap messages endpoint.
     *
     * @return string
     */
    protected function getMailTrapMessagesUrl()
    {
        return "/api/v1/inboxes/{$this->mailTrapInboxId}/messages";
    }

    /**
     * Get the MailTrap "empty inbox" endpoint.
     *
     * @return string
     */
    protected function getMailTrapCleanUrl()
    {
        return "/api/v1/inboxes/{$this->mailTrapInboxId}/clean";
    }

    /**
     * Determine if MailTrap config has been retrieved yet.
     *
     * @return boolean
     */
    protected function alreadyConfigured()
    {
        return $this->mailTrapApiKey;
    }

    /**
     * Request a new Guzzle client.
     *
     * @return Client
     */
    protected function requestClient()
    {
        if ( ! $this->client) {
            $this->client = new Client([
                'base_url' => 'https://mailtrap.io',
                'defaults' => [
                    'headers' => ['Api-Token' => $this->mailTrapApiKey]
                ]
            ]);
        }

        return $this->client;
    }

}