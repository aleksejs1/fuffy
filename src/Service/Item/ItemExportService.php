<?php

namespace App\Service\Item;

use App\Entity\Item;
use App\Entity\User;

class ItemExportService
{
    public function exportCsv(User $user, string $filename = 'php://output', string $mode = 'wb'): void
    {
        $csv = fopen($filename, $mode);
        if (false === $csv) {
            return;
        }

        $this->putHeadersCsv($csv);
        $this->exportItemsCsv($csv, $user);

        fclose($csv);
    }

    /** @param resource $stream **/
    private function putHeadersCsv($stream): void
    {
        fputcsv($stream, [
            'Id',
            'Name',
            'Model',
            'Price',
            'Buy date',
            'End date',
            'Plan to use in months',
        ]);
    }

    /** @param resource $stream **/
    private function exportItemsCsv($stream, User $user): void
    {
        foreach ($user->getItems() as $item) {
            if (!$item instanceof Item) {
                throw new \Exception('Something went wrong. Broken data. Item is not item');
            }
            $this->exportItemCsv($stream, $item);
        }
    }

    /** @param resource $stream **/
    private function exportItemCsv($stream, Item $item): void
    {
        fputcsv($stream, [
            $item->getId(),
            $item->getName(),
            $item->getModel(),
            $item->getPrice(),
            $item->getBuyDate()?->format('d.m.Y H:i:s'),
            $item->getEndDate()?->format('d.m.Y H:i:s'),
            $item->getPlanToUseInMonths(),
        ]);
    }
}
