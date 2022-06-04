<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\ItemRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/app/user')]
class UserController extends AbstractController
{
    #[Route('/settings', name: 'user_settings')]
    public function settings(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('login');
        }
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                (string) $form->get('plainPassword')->getData()
                )
            );

            $userRepository->add($user, true);
        }

        return $this->render('user/settings.html.twig', [
            'userForm' => $form->createView(),
        ]);
    }

    #[Route('/delete', name: 'user_delete')]
    public function delete(
        ItemRepository $itemRepository,
        UserRepository $userRepository,
        TokenStorageInterface $tokenStorage
    ): Response {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('login');
        }

        $tokenStorage->setToken(null);

        foreach ($user->getItems() as $item) {
            if (!$item instanceof Item) {
                throw new \Exception('Something went wrong. Broken data. Item is not item');
            }
            $itemRepository->remove($item);
        }
        $userRepository->remove($user, true);

        return $this->redirectToRoute('app_logout');
    }
}
