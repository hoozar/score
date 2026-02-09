<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\StatisticRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatisticRepository::class)]
class Statistic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 127)]
    private ?string $match_id = null;

    #[ORM\Column(length: 127)]
    private ?string $team_id = null;

    #[ORM\Column(length: 31)]
    private ?string $type = null;

    #[ORM\Column]
    private ?int $count = null;

    public function __construct(
        ?string $matchId,
        ?string $teamId,
        ?string $type,
        ?int    $count
    ) {
        $this->match_id = $matchId;
        $this->team_id = $teamId;
        $this->type = $type;
        $this->count = $count;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatchId(): ?string
    {
        return $this->match_id;
    }

    public function getTeamId(): ?string
    {
        return $this->team_id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function countUp(): void
    {
        $this->count++;
    }
}
