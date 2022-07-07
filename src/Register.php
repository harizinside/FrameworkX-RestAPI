<?php

namespace Inside;

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

class Register
{
    public function __invoke(ServerRequestInterface $request)
    {
        // return Response::json($request->getParsedBody()['name']);
        $input = $request->getParsedBody();

        $res = $db->query("INSERT INTO `users` (`name`, `email`, `phone`, `password`, `created_at`)
             VALUES (?, ?, ?, ?, NOW())", [$input['name'], $input['email'], $input['phone'], password_hash($input['password'])])
            ->then(function (React\MySQL\QueryResult $result) {
                return $result;
            });

        return Response::json($res);
    }
}
