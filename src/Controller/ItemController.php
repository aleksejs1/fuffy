<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\User;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use App\Security\Voter\ItemVoter;
use App\Service\Item\ItemExportService;
use App\Service\Item\ItemFormHandler;
use App\Service\Item\ItemService;
use App\Service\UserQuotaService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/app/item')]
class ItemController extends AbstractController
{
    #[Route('/', name: 'app_item_index', methods: ['GET'])]
    public function index(ItemService $itemService): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('login');
        }

        return $this->render('item/index.html.twig', [
            'items' => $itemService->itemArrayToDto($user->getItems()->toArray()),
        ]);
    }

    #[Route('/new', name: 'app_item_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ItemFormHandler $itemFormHandler, UserQuotaService $quotaService): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('login');
        }

        if ($quotaService->checkNoQuota($user)) {
            $this->addFlash('error', 'No quota left');

            return $this->redirectToRoute('app_item_index', [], Response::HTTP_SEE_OTHER);
        }

        $item = new Item($user);
        $form = $this->createForm(ItemType::class, $item);

        if ($itemFormHandler->handle($form, $request)) {
            return $this->redirectToRoute('app_item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('item/new.html.twig', [
            'item' => $item,
            'form' => $form,
        ]);
    }

    #[Route('/export', name: 'app_item_export', methods: ['GET'])]
    public function export(ItemExportService $itemExportService): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('login');
        }

        $response = new StreamedResponse(function () use ($user, $itemExportService) {
            $itemExportService->exportCsv($user);
        });
        $response->headers->set('Content-Disposition', HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            'export.csv'
        ));

        return $response;
    }

    #[Route('/{id}/edit', name: 'app_item_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Item $item, ItemFormHandler $itemFormHandler): Response
    {
        $this->denyAccessUnlessGranted(ItemVoter::EDIT, $item);
        $form = $this->createForm(ItemType::class, $item);

        if ($itemFormHandler->handle($form, $request)) {
            return $this->redirectToRoute('app_item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('item/edit.html.twig', [
            'item' => $item,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_item_delete', methods: ['POST'])]
    public function delete(Request $request, Item $item, ItemRepository $itemRepository): Response
    {
        $this->denyAccessUnlessGranted(ItemVoter::EDIT, $item);
        $token = $request->request->get('_token');
        if ((null === $token || is_string($token)) && $this->isCsrfTokenValid('delete'.($item->getId() ?? ''), $token)) {
            $itemRepository->remove($item, true);
        }

        return $this->redirectToRoute('app_item_index', [], Response::HTTP_SEE_OTHER);
    }
}
