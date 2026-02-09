<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class EventData
{
    #[ORM\Column(length: 31)]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $player = null;

    #[ORM\Column(length: 127)]
    private ?string $team_id = null;

    #[ORM\Column(length: 127)]
    private ?string $match_id = null;

    #[ORM\Column]
    private ?int $minute = null;

    #[ORM\Column]
    private ?int $second = null;

    public function __construct(
        ?string $type,
        ?string $player,
        ?string $teamId,
        ?string $matchId,
        ?int    $minute,
        ?int    $second
    ) {
        $this->type = $type;
        $this->player = $player;
        $this->team_id = $teamId;
        $this->match_id = $matchId;
        $this->minute = $minute;
        $this->second = $second;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getPlayer(): ?string
    {
        return $this->player;
    }

    public function getTeamId(): ?string
    {
        return $this->team_id;
    }

    public function getMatchId(): ?string
    {
        return $this->match_id;
    }

    public function getMinute(): ?int
    {
        return $this->minute;
    }

    public function getSecond(): ?int
    {
        return $this->second;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['type'] ?? null,
            $data['player'] ?? null,
            $data['team_id'] ?? null,
            $data['match_id'] ?? null,
            $data['minute'] ?? null,
            $data['second'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'player' => $this->player,
            'team_id' => $this->team_id,
            'match_id' => $this->match_id,
            'minute' => $this->minute,
            'second' => $this->second
        ];
    }
}
