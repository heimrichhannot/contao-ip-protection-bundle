<?php

namespace HeimrichHannot\IpProtectionBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class HeimrichHannotIpProtectionBundle extends Bundle
{
    public function getPath()
    {
        return \dirname(__DIR__);
    }

}