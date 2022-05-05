<?php

namespace TokenizedLogin\Http;

use TokenizedLogin\Facades\BaseFacade;
use TokenizedLogin\Http\Responses\Responses;

class ResponderFacade extends BaseFacade
{
    protected static function getFacadeAccessor()
    {
        return Responses::class;
    }
}