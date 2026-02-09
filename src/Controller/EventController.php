<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\EventTypeEnum;
use App\Message\EventOccurred;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validation;
use Throwable;

final class EventController extends AbstractController
{
    #[Route('/event', name: 'app_event', methods: ['POST'])]
    public function index(
        Request $request,
        EventRepository $eventRepository,
        MessageBusInterface $messageBus,
    ): JsonResponse {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

            $violations = $this->validateAddEventData($data);
            if (count($violations) > 0) {
                throw new ValidationFailedException($data, $violations);
            }

            $event = Event::fromArray($data);
            $eventRepository->save($event);

            $messageBus->dispatch(
                new EventOccurred(
                    $event->getData()->getType(),
                    $event->getData()->getTeamId(),
                    $event->getData()->getMatchId(),
                )
            );

            return $this->json([
                'status' => 'success',
                'message' => 'Event saved successfully',
                'event' => $event->toArray()
            ]);

        } catch (ValidationFailedException $e) {
            $msg = [];
            /** @var ConstraintViolationInterface $violation */
            foreach ($e->getViolations() as $violation) {
                $msg[] = $violation->getMessage();
            };
            return $this->json(
                ['error' => 'Validation failed: ' . implode(PHP_EOL, $msg)],
                400
            );

        } catch (Throwable $e) {
            return $this->json(
                ['error' => 'Malformed input data: ' . $e->getMessage()],
                500
            );
        }
    }

    private function validateAddEventData(array $data): ConstraintViolationListInterface
    {
        $result = new ConstraintViolationList();
        $validator = Validation::createValidator();
        $result->addAll(
            $validator->validate(
                $data,
                [
                    new Constraints\NotBlank(),
                    new Constraints\Type('array')
                ]
            )
        );
        // for heavy malformed or empty input data, it's no need to continue validation
        if (count($result) > 0) {
            return $result;
        }

        $result->addAll(
            $validator->validate(
                $data['type'] ?? null,
                [
                    new Constraints\NotBlank(message: 'Event type is required.'),
                    new Constraints\Choice(choices: EventTypeEnum::values(), message: 'Invalid event type.'),
                ]
            )
        );

        $result->addAll(
            $validator->validate(
                $data['team_id'] ?? null,
                [new Constraints\NotBlank(message: 'Team ID is required.')]
            )
        );

        $result->addAll(
            $validator->validate(
                $data['match_id'] ?? null,
                [new Constraints\NotBlank(message: 'Match ID is required.')]
            )
        );

        $result->addAll(
            $validator->validate(
                $data['player'] ?? null,
                [new Constraints\NotBlank(message: 'Match ID is required.')]
            )
        );

        $result->addAll(
            $validator->validate(
                $data['minute'] ?? null,
                [
                    new Constraints\NotBlank(message: 'Minute is required.'),
                    new Constraints\Type(type: 'numeric', message: 'Minute must be numeric.')
                ]
            )
        );

        $result->addAll(
            $validator->validate(
                $data['second'] ?? null,
                [
                    new Constraints\NotBlank(message: 'Second is required.'),
                    new Constraints\Type(type: 'numeric', message: 'Second must be numeric.')
                ]
            )
        );

        return $result;
    }
}
