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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getPlayer(): ?string
    {
        return $this->player;
    }

    public function setPlayer(?string $player): static
    {
        $this->player = $player;

        return $this;
    }

    public function getTeamId(): ?string
    {
        return $this->team_id;
    }

    public function setTeamId(string $team_id): static
    {
        $this->team_id = $team_id;

        return $this;
    }

    public function getMatchId(): ?string
    {
        return $this->match_id;
    }

    public function setMatchId(string $match_id): static
    {
        $this->match_id = $match_id;

        return $this;
    }

    public function getMinute(): ?int
    {
        return $this->minute;
    }

    public function setMinute(int $minute): static
    {
        $this->minute = $minute;

        return $this;
    }

    public function getSecond(): ?int
    {
        return $this->second;
    }

    public function setSecond(int $second): static
    {
        $this->second = $second;

        return $this;
    }
}
