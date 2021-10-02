<?php

return [
    /**
     * Save failed logins to the database
     */
    'record_failed_login' => true,

    /**
     * Save successful logins to the database
     * Required to detect suspicious logins
     */
    'record_successful_login' => true,

    /**
     * Array of email addresses for an "admin" list of users
     * who are always notified of a suspicious login
     */
    'notify_admins' => [],

    /**
     * Should the user who's account was logged into
     * be emailed to notify them
     */
    'notify_users' => true,

    /**
     * What database column is the users email stored in
     */
    'email_column' => 'email',

    /**
     * Should we consider logins in the same city [default true] safe
     * automatically, and the same country [default false]
     */
    'safe' => [
        'city' => true,
        'country' => false,
    ],
];
