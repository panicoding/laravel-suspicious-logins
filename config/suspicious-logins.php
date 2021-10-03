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

    /**
     * Opt-in to using the Advent Reputation service at https://reputation.advent.dev by
     * changing 'enabled' to true.
     *
     * This provides a central API for detecting suspicious logins based
     * on IP address reputation where your users can be alerted of
     * suspicious logins even if they are in the same city.
     *
     * By enabling it you will also submit IP address login attempts from your
     * system (only the IP and if it succeeded or failed, no other information)
     *
     * riskLevelToAlert can be 1, 2, or 3. We recommend 2 as a default, but you
     * can change it to 1 to be more likely to find an IP suspicious or 3 to
     * be less likely.
     */
    'reputation' => [
        'enabled' => false,
        'riskLevelToAlert' => 3,
    ],
];
