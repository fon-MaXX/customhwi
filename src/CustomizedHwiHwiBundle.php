<?php

namespace CustomizedHwi\HwiBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CustomizedHwiHwiBundle extends Bundle
{
    public function getParent()
    {
        return 'HWIOAuthBundle';
    }
}
