<?php

namespace App\Tests\Service\Item;

use App\Entity\Item;
use App\Entity\User;
use App\Repository\ItemRepository;
use App\Service\Item\ItemFormHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class ItemFormHandlerTest extends TestCase
{
    /**
     * @covers \App\Service\Item\ItemFormHandler::handle
     */
    public function testMonthsInUse(): void
    {
        $itemRepository = $this->createMock(ItemRepository::class);
        $itemFormHandler = new ItemFormHandler($itemRepository);
        $request = $this->createMock(Request::class);

        $form1 = $this->createMock(Form::class);
        $form1->method('isSubmitted')->willReturn(false);
        $form1->method('isValid')->willReturn(true);
        $this->assertFalse($itemFormHandler->handle($form1, $request));

        $form2 = $this->createMock(Form::class);
        $form2->method('isSubmitted')->willReturn(true);
        $form2->method('isValid')->willReturn(false);
        $this->assertFalse($itemFormHandler->handle($form2, $request));

        $form3 = $this->createMock(Form::class);
        $form3->method('isSubmitted')->willReturn(true);
        $form3->method('isValid')->willReturn(true);
        $item = $this->createMock(Item::class);
        $form3->method('getData')->willReturn($item);
        $this->assertTrue($itemFormHandler->handle($form3, $request));

        $form4 = $this->createMock(Form::class);
        $form4->method('isSubmitted')->willReturn(true);
        $form4->method('isValid')->willReturn(true);
        $user = $this->createMock(User::class);
        $form4->method('getData')->willReturn($user);
        $this->expectException(\InvalidArgumentException::class);
        $result = $itemFormHandler->handle($form4, $request);
    }
}
