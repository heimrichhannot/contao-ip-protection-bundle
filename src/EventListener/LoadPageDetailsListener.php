<?php

namespace HeimrichHannot\IpProtectionBundle\EventListener;


use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\PageModel;
use Contao\StringUtil;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\RequestStack;

#[AsHook('loadPageDetails')]
class LoadPageDetailsListener
{
    public function __construct(
        protected RequestStack $requestStack,
    )
    {
    }

    public function __invoke(array $parentModels, PageModel $pageModel): void
    {
        if (!$pageModel->protected) {
            return;
        }

        $request = $this->requestStack->getCurrentRequest();
        if (!$request) {
            return;
        }

        $pageModel->ipProtected = (bool) $pageModel->ipProtected;

        if (!$pageModel->ipProtected) {
            foreach ($parentModels as $parentModel) {
                if ($parentModel->protected && $parentModel->ipProtected && $pageModel->ipProtected === false) {
                    $pageModel->ipProtected = true;
                    $pageModel->allowedIps = $parentModel->allowedIps;
                    break;
                }
            }
        }

        $allowedIps = StringUtil::deserialize($pageModel->allowedIps, true);
        if (empty($allowedIps)) {
            return;
        }

        $ip = $request->getClientIp();

        if (IpUtils::checkIp($ip, $allowedIps)) {
            $pageModel->protected = false;
        }
    }
}