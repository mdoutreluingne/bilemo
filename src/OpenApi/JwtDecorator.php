<?php

declare(strict_types=1);

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Model;

final class JwtDecorator implements OpenApiFactoryInterface
{
    private $decorated;

    public function __construct(
        OpenApiFactoryInterface $decorated
    ) {
        $this->decorated = $decorated;
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);
        $schemas = $openApi->getComponents()->getSchemas();
     
        $this->configurationSchemaToken($schemas);
        $this->configurationSchemaCredentials($schemas);
        $this->configurationSchemaBadRequest($schemas);

        /* Create path item */
        $pathItem = new Model\PathItem(
            'JWT Token',
            null,
            null,
            null,
            null,
            $this->configurationPostOperation()
        );
        $openApi->getPaths()->addPath('/login', $pathItem);

        return $openApi;
    }

    /**
     * configurationSchemaToken
     *
     * @param mixed $schemas
     * @return void
     */
    private function configurationSchemaToken($schemas): void
    {
        $schemas['Token'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'token' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
            ],
        ]);
    }

    /**
     * configurationSchemaBadRequest
     *
     * @param mixed $schemas
     * @return void
     */
    private function configurationSchemaBadRequest($schemas): void
    {
        $schemas['BadRequest'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'type' => [
                    'type' => 'string',
                    'example' => 'https://tools.ietf.org/html/rfc2616#section-10',
                ],
                'title' => [
                    'type' => 'string',
                    'example' => 'An error occurred',
                ],
                'status' => [
                    'type' => 'integer',
                    'example' => 400,
                ],
                'detail' => [
                    'type' => 'string',
                    'example' => 'Invalid request body. You must provide \'email\' and \'password\' keys',
                ],
            ],
        ]);
    }

    /**
     * configurationSchemaCredentials
     *
     * @param mixed $schemas
     * @return void
     */
    private function configurationSchemaCredentials($schemas): void
    {
        $schemas['Credentials'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'email' => [
                    'type' => 'string',
                    'example' => 'johndoe@example.com',
                ],
                'password' => [
                    'type' => 'string',
                    'example' => 'apassword',
                ],
            ],
        ]);
    }

    /**
     * configurationPostOperation
     *
     * @return Model\Operation
     */
    private function configurationPostOperation(): Model\Operation
    {
        return new Model\Operation(
                'postCredentialsItem',
                ['Authentication'],
                [
                    '200' => [
                        'description' => 'Get JWT token',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Token',
                                ],
                            ],
                        ],
                    ],
                    '400' => [
                        'description' => 'Bad request',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/BadRequest',
                                ],
                            ],
                        ],
                    ]
                ],
                'Get JWT token to login.',
                '',
                null,
                [],
                new Model\RequestBody(
                    'Generate new JWT Token',
                    new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/Credentials',
                            ],
                        ],
                    ]),
                ),
            );
    }
}
