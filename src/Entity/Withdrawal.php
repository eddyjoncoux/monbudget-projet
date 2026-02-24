<?php

namespace App\Entity;

use App\Enum\WithdrawalFrequency;
use App\Repository\WithdrawalRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WithdrawalRepository::class)]
class Withdrawal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $nextWithdrawalDate = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $lastWithdrawalDate = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startDate = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $endDate = null;

    #[ORM\Column(enumType: WithdrawalFrequency::class)]
    private ?WithdrawalFrequency $frequency = null;

    #[ORM\Column]
    private bool $isActive = true;

    #[ORM\ManyToOne]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'withdrawals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->startDate = new \DateTimeImmutable();
        $this->nextWithdrawalDate = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = abs($amount);
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getNextWithdrawalDate(): ?\DateTimeImmutable
    {
        return $this->nextWithdrawalDate;
    }

    public function setNextWithdrawalDate(\DateTimeImmutable $nextWithdrawalDate): static
    {
        $this->nextWithdrawalDate = $nextWithdrawalDate;
        return $this;
    }

    public function getLastWithdrawalDate(): ?\DateTimeImmutable
    {
        return $this->lastWithdrawalDate;
    }

    public function setLastWithdrawalDate(?\DateTimeImmutable $lastWithdrawalDate): static
    {
        $this->lastWithdrawalDate = $lastWithdrawalDate;
        return $this;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeImmutable $startDate): static
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeImmutable $endDate): static
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function getFrequency(): ?WithdrawalFrequency
    {
        return $this->frequency;
    }

    public function setFrequency(WithdrawalFrequency $frequency): static
    {
        $this->frequency = $frequency;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function isOverdue(): bool
    {
        return $this->isActive && $this->nextWithdrawalDate < new \DateTimeImmutable('now') && (!$this->endDate || $this->endDate > new \DateTimeImmutable('now'));
    }
}
