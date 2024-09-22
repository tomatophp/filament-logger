<?php

return [
    "title" => "Activity Log",
    "group" => "Settings",
    "single" => "Activity",
    "columns" => [
        "model" => "Model",
        "response_time" => "Response Time",
        "status" => "Status",
        "method" => "Method",
        "url" => "URL",
        "referer" => "Referer",
        "query" => "Query",
        "remote_address" => "Remote Address",
        "user_agent" => "User Agent",
        "response" => "Response",
        "level" => "Level",
        "user" => "User",
        "log" => "Log",
        "created_at" => "Created At",
        "updated_at" => "Updated At"
    ],
    "actions" => [
        "clear" => [
            "label" => "Clear Activities",
            "success" => [
                "title" => "Activities Cleared",
                "body" => "All activities have been cleared."
            ],
        ],
        "poll" => [
            "label" => "Realtime Poll",
            "enabled" => [
                "title" => "Polling Enabled",
                "body" => "Activities will be polled every 2 seconds."
            ],
            "disabled" => [
                "title" => "Polling Disabled",
                "body" => "Activities will no longer be polled."
            ]
        ]
    ]
];
