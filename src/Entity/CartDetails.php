<?php

namespace App\Entity;

use App\Repository\CartDetailsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartDetailsRepository::class)]
class CartDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $productName = null;

    #[ORM\Column]
    private ?float $productPrice = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?float $subtotalHT = null;

    #[ORM\Column]
    private ?float $taxe = null;

    #[ORM\Column]
    private ?float $subtotalTTC = null;

    #[ORM\ManyToOne(inversedBy: 'CartDetails')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cart $carts = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): self
    {
        $this->productName = $productName;

        return $this;
    }

    public function getProductPrice(): ?float
    {
        return $this->productPrice;
    }

    public function setProductPrice(float $productPrice): self
    {
        $this->productPrice = $productPrice;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getSubtotalHT(): ?float
    {
        return $this->subtotalHT;
    }

    public function setSubtotalHT(float $subtotalHT): self
    {
        $this->subtotalHT = $subtotalHT;

        return $this;
    }

    public function getTaxe(): ?float
    {
        return $this->taxe;
    }

    public function setTaxe(float $taxe): self
    {
        $this->taxe = $taxe;

        return $this;
    }

    public function getSubtotalTTC(): ?float
    {
        return $this->subtotalTTC;
    }

    public function setSubtotalTTC(float $subtotalTTC): self
    {
        $this->subtotalTTC = $subtotalTTC;

        return $this;
    }

    public function getCarts(): ?Cart
    {
        return $this->carts;
    }

    public function setCarts(?Cart $carts): self
    {
        $this->carts = $carts;

        return $this;
    }
}
