<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM; // Note: ORM namespace is only needed for type hinting the Category entity.

class CategorySearch
{
    // Note: In Symfony 7, we should import Category::class.
    // Assuming Category is in the same namespace, you only need to check the file structure.
    private $category;

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;
        return $this;
    }
}