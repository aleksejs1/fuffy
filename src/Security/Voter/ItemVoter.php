<?php

namespace App\Security\Voter;

use App\Entity\Item;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ItemVoter extends Voter
{
    public const EDIT = 'ITEM_EDIT';
    public const VIEW = 'ITEM_VIEW';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof Item;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }
        /** @var Item $subject */
        if (!$subject instanceof Item) {
            return false;
        }

        switch ($attribute) {
            case self::EDIT:
            case self::VIEW:
                if ($subject->getOwner()->getId() === $user->getId()) {
                    return true;
                }
                break;
        }

        return false;
    }
}
