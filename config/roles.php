<?php
return [

    /*
    | --------------------------------------------------------------------------
    | DEFAULT ROLE
    | --------------------------------------------------------------------------
    | This is the default role.
    | You may change the name of the role if you want.
    | DO NOT modify the id unless you know what you are doing. This is used
    | in the role validation rules to differentiate between a DB role and this one.
    | You could change the hierarchy number if you change the uint type of the
    | hierarchy column in the migration 'create_roles_table'.
    | By default 16777214 is the max possible value -1.
    */
    'default_role' => [
        'name' => 'STANDARD',
        'hierarchy' => 16777214,
        'id' => null
    ],

    /*
    | --------------------------------------------------------------------------
    | ROLE PERMISSIONS
    | --------------------------------------------------------------------------
    | This permissions are global for all roles.
    | Do not modify the core permissions unless you know what you are doing.
    | Add your custom module permissions at the 'extended' section.
    | If you add or remove a permission, you may have to rerun the 'create_roles_table' migration.
    | or create another migration. That's why you should know how many permissions
    | will be in your web application.
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

            //Quotes related
            'read_quotes',
            'delete_quotes'
        ],
        
        'extended' => [

        ]
    ],

];
