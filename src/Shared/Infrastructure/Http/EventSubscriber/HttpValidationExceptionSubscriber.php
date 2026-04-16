<?php

namespace App\Shared\Infrastructure\Http\EventSubscriber;

use App\Shared\Domain\Validation\ViolationList;
use App\Shared\UI\Http\JsonResponseFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

final readonly class HttpValidationExceptionSubscriber implements EventSubscriberInterface
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
        $throwable = $event->getThrowable();

        if (!$throwable instanceof UnprocessableEntityHttpException) {
            return;
        }

        $previous = $throwable->getPrevious();

        if (!$previous instanceof ValidationFailedException) {
            return;
        }

        $request = $event->getRequest();
        $requestData = json_decode($request->getContent(), true) ?? [];

        $violations = $this->convertSymfonyViolations($previous->getViolations(), $requestData);

        $response = $this->jsonResponseFactory->unprocessable('Validation failed', $violations);

        $event->setResponse($response);
    }

    /**
     * @param array<string, mixed> $requestData
     */
    private function convertSymfonyViolations(ConstraintViolationListInterface $constraintViolations, array $requestData): ViolationList
    {
        $violations = ViolationList::create();

        foreach ($constraintViolations as $violation) {
            $invalidValue = $violation->getInvalidValue();

            // If validator returns null, try to get the actual value from request data
            if (null === $invalidValue) {
                $invalidValue = $requestData[$violation->getPropertyPath()] ?? null;
            }

            $violations->add($violation->getMessage(), $violation->getPropertyPath(), $invalidValue);
        }

        return $violations;
    }
}
