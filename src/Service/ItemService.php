<?php

namespace App\Service;

use App\Dto\ItemDto;
use App\Entity\Item;

class ItemService
{
    public function getDto(Item $item): ItemDto
    {
        return new ItemDto($item);
    }

    public function itemArrayToDto(array $items): array
    {
        $dtos = [];
        /** @var Item $item */
        foreach ($items as $item) {
            $dtos[] = $this->getDto($item);
        }

        return $dtos;
    }
}
