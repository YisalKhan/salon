<?php

class SLN_Third_GoogleCalendarImportMainSettings
{
    const SYNC_TOKEN_KEY = 'salon_google_client_calendar_sync_token';

    private $gScope;

    /**
     * SLN_Third_GoogleCalendarImport constructor.
     * @param $gScope
     */
    public function __construct($gScope)
    {
        $this->gScope = $gScope;
    }

    public function updateSyncToken($syncToken)
    {
        update_option(self::SYNC_TOKEN_KEY, $syncToken);
    }

    public function getSyncToken()
    {
        return get_option(self::SYNC_TOKEN_KEY);
    }

    public function getGoogleScope()
    {
        return $this->gScope;
    }

    public function getChangedPostAttrs(array $attrs)
    {
        return $attrs;
    }
}