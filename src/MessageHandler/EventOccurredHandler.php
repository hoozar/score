<?php
declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\Statistic;
use App\Message\EventOccurred;
use App\Repository\StatisticRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class EventOccurredHandler
{

    public function __construct(
        private StatisticRepository $repository,
    ) {
    }

    public function __invoke(EventOccurred $message): void
    {
        $statistic = $this->repository->findOneByCriteria([
            'type' => $message->getType(),
            'team_id' => $message->getTeamId(),
            'match_id' => $message->getMatchId(),
        ]);

        if ($statistic === null) {
            $statistic = new Statistic(
                $message->getMatchId(),
                $message->getTeamId(),
                $message->getType(),
                0
            );
        }

        $statistic->countUp();
        $this->repository->save($statistic);
    }
}
