<?php 

return [
    // request_log View
    [
        "nama" => "request_log.view_index",
        "deskripsi" => "View Index request_log",
        "groups" => [
            "superadmin",
        ],
        "clients" => [
            1
        ],
    ],
    // request_log Api
    [
        "nama" => "request_log.post_datatable",
        "deskripsi" => "Api post Request log datatable",
        "groups" => [
            "superadmin",
        ],
        "clients" => [
            1
        ],
    ],
];