<?php

namespace HeimrichHannot\IpProtectionBundle\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class IpProtectionVoter extends Voter
{

    protected function supports(string $attribute, $subject): bool
    {
        return false;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        return false;
    }
}