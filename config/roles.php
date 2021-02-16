<?php
return [
    /*
    | --------------------------------------------------------------------------
    | DEFAULT ROLES
    | --------------------------------------------------------------------------
    | The following roles are the default ones. Do NOT modify them unless you 
    | know what you are doing.
    */
    'permissions' => [
        'core' => [
            // Users related
            'create_users',
            'read_users',
            'update_users',
            'delete_users',

            // Post related
            'create_post',
            'update_elses_post',
            'delete_elses_post',

            // Comments related
            'update_elses_comments',
            'delete_elses_comments',

            // Roles related
            'create_roles',
            'read_roles',
            'update_roles',
            'delete_roles',
        ],
        'extended' => [
            
        ]
    ],

];
