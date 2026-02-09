<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 31)]
    private ?string $type = null;

    #[ORM\Column]
    private ?int $timestamp = null;

    #[ORM\Embedded(class: EventData::class, columnPrefix: "data_")]
    private ?EventData $data = null;

    public function __construct(
        ?string $type,
        ?int $timestamp,
        ?EventData $data
    ) {
        $this->type = $type;
        $this->timestamp = $timestamp;
        $this->data = $data;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getTimestamp(): ?int
    {
        return $this->timestamp;
    }

    public function getData(): ?EventData
    {
        return $this->data;
    }
    public static function fromArray(array $data): self
    {
        return new self(
            $data['type'] ?? null,
            time(),
            EventData::fromArray($data)
        );
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'timestamp' => $this->timestamp,
            'data' => $this->data->toArray()
        ];
    }
}
