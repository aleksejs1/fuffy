<?php

namespace App\Service\Item;

use App\Entity\Item;
use App\Repository\ItemRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class ItemFormHandler
{
    public function __construct(
        private readonly ItemRepository $itemRepository
    ) {
    }

    public function handle(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();
            if (!$item instanceof Item) {
                throw new \InvalidArgumentException('Form data should be Item::class');
            }

            $this->itemRepository->add($item, true);

            return true;
        }

        return false;
    }
}
