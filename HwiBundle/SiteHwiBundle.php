<?php

namespace Site\HwiBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SiteHwiBundle extends Bundle
{
    public function getParent()
    {
        return 'HWIOAuthBundle';
    }
}
