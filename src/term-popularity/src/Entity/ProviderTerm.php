<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'providers_terms')]
#[ORM\UniqueConstraint(name: "one_result_idx", columns: ["provider", "term"])]
class ProviderTerm
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: Types::INTEGER)]
    private $id;

    #[ORM\ManyToOne(targetEntity: "Provider", inversedBy: "terms")]
    #[ORM\JoinColumn(name: "provider_id", referencedColumnName: "id", nullable: false)]
    private Provider $provider;

    #[ORM\ManyToOne(targetEntity: "Term", inversedBy: "providers")]
    #[ORM\JoinColumn(name: "term_id", referencedColumnName: "id", nullable: false)]
    private Term $term;

    #[ORM\Column(type: Types::FLOAT)]
    private ?float $score;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProvider(): Provider
    {
        return $this->provider;
    }

    public function setProvider(Provider $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    public function getTerm(): Term
    {
        return $this->term;
    }

    public function setTerm(Term $term): self
    {
        $this->term = $term;

        return $this;
    }

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function setScore(float $score): self
    {
        $this->score = $score;

        return $this;
    }
}
