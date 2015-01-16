<?php

namespace Laracasts\Behat\Context\Services;

use GuzzleHttp\Client;
use Exception;

trait MailTrap
{

    /**
     * The MailTrap configuration.
     *
     * @var array
     */
    protected $mailTrapInboxId;

    /**
     * The MailTrap API Key.
     *
     * @var string
     */
    protected $mailTrapApiKey;

    /**
     * Get the configuration for MailTrap.
     *
     * @param integer|null $inboxId
     * @throws Exception
     */
    public function applyMailTrapConfiguration($inboxId = null)
    {
        if ($this->alreadyConfigured()) {
            return;
        }

        if (is_null($config = Config::get('services.mailtrap'))) {
            throw new Exception(
                'You must set "secret" and "default_inbox" keys for "mailtrap" in "config/services.php."'
            );
        }

        $this->mailTrapInboxId = $inboxId ?: $config['default_inbox'];
        $this->mailTrapApiKey = $config['secret'];
    }

    /**
     * Fetch a MailTrap inbox.
     *
     * @param null $inboxId
     * @return mixed
     */
    public function fetchInbox($inboxId = null)
    {
        $this->applyMailTrapConfiguration($inboxId);

        return $this->requestClient()->get("/api/v1/inboxes/{$this->mailTrapInboxId}/messages")->json();
    }

    /**
     *
     * Empty the MailTrap inbox.
     *
     * @AfterScenario @mail
     */
    public function emptyInbox()
    {
        $this->requestClient()->patch(
            "/api/v1/inboxes/{$this->mailTrapInboxId}/clean", ['future' => true]
        );
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
        return new Client([
            'base_url' => 'https://mailtrap.io',
            'defaults' => [
                'headers' => ['Api-Token' => $this->mailTrapApiKey]
            ]
        ]);
    }

}