<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Http\EventSubscriber;

use App\Shared\Domain\Validation\ConflictException;
use App\Shared\Domain\Validation\NotFoundException;
use App\Shared\Domain\Validation\UnprocessableException;
use App\Shared\UI\Http\JsonResponseFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

readonly class DomainExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private JsonResponseFactory $jsonResponseFactory,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 10],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $response = match (true) {
            $exception instanceof UnprocessableException => $this->jsonResponseFactory->unprocessable(
                $exception->getMessage(),
                $exception->violations()
            ),
            $exception instanceof ConflictException => $this->jsonResponseFactory->conflict(
                $exception->getMessage()
            ),
            $exception instanceof NotFoundException => $this->jsonResponseFactory->notFound(
                $exception->getMessage(),
                $event->getRequest()->getPathInfo()
            ),
            default => null,
        };

        if (null !== $response) {
            $event->setResponse($response);
        }
    }
}
