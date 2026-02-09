<?php
declare(strict_types=1);

namespace App\Message;

class EventOccurred
{
    public function __construct(
        private string $type,
        private string $teamId,
        private string $matchId,
    ) {
        // empty constructor
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getTeamId(): string
    {
        return $this->teamId;
    }

    public function getMatchId(): string
    {
        return $this->matchId;
    }
}
