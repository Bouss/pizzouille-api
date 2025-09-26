<?php

declare(strict_types=1);

namespace App\Tests;

use JsonException;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiTestCase extends WebTestCase
{
    protected ?KernelBrowser $client = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = null;
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->client = null;
    }

    protected function client(): KernelBrowser
    {
        return $this->client ??= static::createClient(server: $this->serverParameters());
    }

    /**
     * @throws JsonException
     */
    protected function jsonRequest(
        string $method,
        string $uri,
        array | string | null $json = null,
        array $headers = []
    ): Response {
        $content = is_array($json)
            ? json_encode($json, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            : ($json ?? '');

        $this->client()->request(
            $method,
            $uri,
            server: $this->serverParameters() + $headers,
            content: $content
        );

        return $this->client()->getResponse();
    }

    protected function response(): Response
    {
        return $this->client()->getResponse();
    }

    /**
     * @return array<string, mixed> | list<mixed>
     * @throws JsonException
     */
    protected function jsonResponse(): array
    {
        $content = $this->response()->getContent();

        if ($content === false || $content === '') {
            Assert::fail('Response has no content.');
        }


        /** @var array $decoded */
        $decoded = json_decode($content, true, flags: JSON_THROW_ON_ERROR);

        return $decoded;
    }

    protected function assertStatus(int $expected): void
    {
        self::assertSame($expected, $this->response()->getStatusCode(), (string) $this->response()->getContent());
    }

    /**
     * @return array<string, string>
     */
    private function serverParameters(): array
    {
        $parts = parse_url($_ENV['BASE_URL']);
        $host = $parts['host'] ?? '';
        $port = isset($parts['port']) ? ':' . $parts['port'] : '';
        $https = (($parts['scheme'] ?? '') === 'https') ? 'on' : 'off';

        return [
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
            'HTTP_HOST' => $host . $port,
            'HTTPS' => $https,
        ];
    }
}
