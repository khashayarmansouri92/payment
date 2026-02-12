<?php

return [
    'default' => 'default',
    'documentations' => [
        'default' => [
            'proxy' => false,
            'api' => [
                'title' => 'Laravel API Documentation',
            ],
            'routes' => [
                'api' => 'api/documentation',
            ],
            'paths' => [
                'use_absolute_path' => env('L5_SWAGGER_USE_ABSOLUTE_PATH', false),
                'annotations' => base_path('app'),
                'base' => env('L5_SWAGGER_BASE_PATH', null),
                'swagger' => base_path('storage/api-docs'),
                'defaults' => base_path('storage/api-docs'),
                'docs' => base_path('storage/api-docs'),
                'docs_json' => 'api-docs.json',
                'docs_yaml' => 'api-docs.yaml',
                'excludes' => [],
            ],
            'swagger_ui' => [
                'display_doc' => true,
                'additional_config_url' => null,
                'validator_url' => null,
                'proxy' => null,
            ],
        ],
    ],
        'defaults' => [
        'proxy' => false,
        'operations_sort' => null,
        'additional_config_url' => null,
        'validator_url' => null,
        'generate_always' => env('L5_SWAGGER_GENERATE_ALWAYS', false),
        'routes' => [
            'docs' => 'docs',
            'oauth2_callback' => 'api/oauth2-callback',
            'middleware' => [
                'api' => [],
                'asset' => [],
                'docs' => [],
                'oauth2_callback' => [],
            ],
            'group_by' => 'tags',
            'groups' => [],
        ],
        'paths' => [
            'use_absolute_path' => env('L5_SWAGGER_USE_ABSOLUTE_PATH', false),
            'annotations' => base_path('app'),
            'base' => env('L5_SWAGGER_BASE_PATH', null),
            'swagger' => base_path('storage/api-docs'),
            'defaults' => base_path('storage/api-docs'),
            'docs' => base_path('storage/api-docs'),
            'docs_json' => 'api-docs.json',
            'docs_yaml' => 'api-docs.yaml',
            'excludes' => [],
        ],
        'swagger' => [
            'swagger' => '2.0',
            'info' => [
                'description' => 'Laravel API Documentation',
                'version' => '1.0.0',
                'title' => 'Laravel API',
            ],
            'host' => env('L5_SWAGGER_CONST_HOST', 'localhost:8000'),
            'basePath' => '/',
            'schemes' => [
                'http',
                'https',
            ],
            'consumes' => [
                'application/json',
            ],
            'produces' => [
                'application/json',
            ],
        ],
        'securityDefinitions' => [
            'securitySchemes' => [
                'bearerAuth' => [
                    'type' => 'http',
                    'scheme' => 'bearer',
                    'bearerFormat' => 'JWT',
                ],
            ],
            'security' => [
                [
                    'bearerAuth' => [],
                ],
            ],
        ],
        'operations' => [
            'security' => [
                [
                    'bearerAuth' => [],
                ],
            ],
        ],
        'operations_sort' => null,
        'swagger_ui' => [
            'display_doc' => true,
            'additional_config_url' => null,
            'validator_url' => null,
            'proxy' => null,
        ],
        'constants' => [
            'L5_SWAGGER_CONST_HOST' => env('L5_SWAGGER_CONST_HOST', 'localhost:8000'),
        ],
    ],
];

