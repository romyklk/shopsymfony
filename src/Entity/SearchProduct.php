<?php

namespace App\Entity;


class SearchProduct
{


   
    private ?int $priceMin = null;

   
    private ?int $priceMax = null;

    /**
     * @var Categories[]
     */
    private array $categories = [];

   
    private ?string $tags = null;


    public function getPriceMin(): ?int
    {
        return $this->priceMin;
    }

    public function setPriceMin(?int $priceMin): self
    {
        $this->priceMin = $priceMin;

        return $this;
    }

    public function getPriceMax(): ?int
    {
        return $this->priceMax;
    }

    public function setPriceMax(?int $priceMax): self
    {
        $this->priceMax = $priceMax;

        return $this;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function setCategories(?array $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(?string $tags): self
    {
        $this->tags = $tags;

        return $this;
    }
}
