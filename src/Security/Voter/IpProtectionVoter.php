<?php

namespace HeimrichHannot\IpProtectionBundle\Security\Voter;

use Contao\CoreBundle\Security\ContaoCorePermissions;
use Contao\PageModel;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class IpProtectionVoter extends Voter
{
    public function __construct(
        private RequestStack $requestStack,
    )
    {
    }

    protected function supports(string $attribute, $subject): bool
    {
        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request) {
            return false;
        }

        $page = $request->attributes->get('pageModel');
        if (null === $page) {
            return false;
        }
        if (is_numeric($page)) {
            $page = PageModel::findByPk($page);
            if (!$page) {
                return false;
            }
        }
        $page->loadDetails();

        if (!$page->protected || $page->ipProtectionType !== 'ipProtection') {
            return false;
        }

        $ip = $request->getClientIp();

        IpUtils::checkIp($ip, []);

        return false;
    }

    public function supportsAttribute(string $attribute): bool
    {
        if (ContaoCorePermissions::MEMBER_IN_GROUPS === $attribute) {
            return true;
        }

        return false;
    }
}