<?php
return [
    /*
    | --------------------------------------------------------------------------
    | ROLE PERMISSIONS
    | --------------------------------------------------------------------------
    | Do not modify the core permissions unless you know what you are doing.
    | Add your custom module permissions at the 'extended' section.
    | If you add or remove a permission, you may have to rerun the 'create_roles_table' migration.
    */
    'permissions' => [
        'core' => [
            // Users related
            'create_users',
            'read_users',
            'update_users',
            'delete_users',

            // Post related
            'create_posts',
            'update_elses_posts',
            'delete_elses_posts',

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
