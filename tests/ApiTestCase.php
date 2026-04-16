<?php

declare(strict_types=1);

namespace App\Tests;

use JsonException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;

abstract class ApiTestCase extends WebTestCase
{
    use ResetDatabase;

    protected ?KernelBrowser $client = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient(server: $this->serverParameters());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->client = null;
    }

    final protected function client(): KernelBrowser
    {
        return $this->client;
    }

    /**
     * @param array<array-key, mixed>|string|null $body
     * @param array<string, scalar>               $headers
     *
     * @throws JsonException
     */
    final protected function jsonRequest(
        string $method,
        string $uri,
        array|string|null $body = null,
        array $headers = [],
    ): void {
        $content = is_array($body)
            ? json_encode($body, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            : ($body ?? '');

        $this->client()->request(
            $method,
            $uri,
            server: $this->serverParameters() + $headers,
            content: $content
        );
    }

    final protected function jsonResponse(): string
    {
        return (string) $this->client()->getResponse()->getContent();
    }

    final protected function responseStatusCode(): int
    {
        return $this->client()->getResponse()->getStatusCode();
    }

    /**
     * @return array<string, string>
     */
    private function serverParameters(): array
    {
        $parts = parse_url($_ENV['BASE_URL']);
        $host = $parts['host'] ?? '';
        $port = isset($parts['port']) ? ':'.$parts['port'] : '';
        $https = (($parts['scheme'] ?? '') === 'https') ? 'on' : 'off';

        return [
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
            'HTTP_HOST' => $host.$port,
            'HTTPS' => $https,
        ];
    }
}
