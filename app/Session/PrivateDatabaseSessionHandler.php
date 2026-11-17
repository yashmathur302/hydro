<?php

declare(strict_types=1);

namespace App\Session;

use Illuminate\Session\DatabaseSessionHandler;

/**
 * Database sessions without the visitor's IP address and browser string.
 *
 * Every visitor gets a session (forms need a CSRF token), and Laravel's
 * default handler records ip_address and user_agent for each one. The site
 * never uses them, so they are not stored (data minimisation). The admin's
 * user_id is still kept so sessions can be ended on logout or password change.
 */
final class PrivateDatabaseSessionHandler extends DatabaseSessionHandler
{
    /**
     * @param  array<string, mixed>  $payload
     * @return $this
     */
    protected function addRequestInformation(&$payload)
    {
        return $this;
    }
}
