<?php

declare(strict_types=1);

namespace App\Shared\UI\Http\ApiDoc;

use App\Shared\UI\Http\ControllerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

readonly class ApiDocController implements ControllerInterface
{
    public function __construct(
        private Environment $twig,
    ) {
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    #[Route('/api/doc', name: 'app_api_doc', methods: ['GET'])]
    public function __invoke(): Response
    {
        return new Response(
            $this->twig->render('api_doc/redoc.html.twig'),
            Response::HTTP_OK,
            ['Content-Type' => 'text/html']
        );
    }
}
