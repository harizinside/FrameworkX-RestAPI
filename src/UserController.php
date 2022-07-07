<?php

namespace Inside;

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

class UserController
{
    public function __invoke(ServerRequestInterface $request)
    {
        return Response::plaintext(
            "Hello " . $request->getAttribute('name') . "!\n"
        );
    }
}
