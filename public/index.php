<?php

require __DIR__ . '/../vendor/autoload.php';

(new Inside\DotEnv(__DIR__ . '/../.env'))->load();
$db = (new React\MySQL\Factory())->createLazyConnection(getenv('DATABASE_DSN'));

$app = new FrameworkX\App();

$app->post('/auth/register', function (Psr\Http\Message\ServerRequestInterface $request) use ($db) {
    $input = $request->getParsedBody();
    return $db->query(
        "INSERT INTO `users` (`name`, `email`, `phone`, `password`, `created_at`)
            VALUES (?, ?, ?, ?, NOW())",
        [$input['name'], $input['email'], $input['phone'], password_hash($input['password'], PASSWORD_DEFAULT)]
    )->then(function (React\MySQL\QueryResult $result) {

        if ($result->affectedRows) {
            return React\Http\Message\Response::json([
                'status' => true,
                'messages' => 'success'
            ]);
        } else {
            return React\Http\Message\Response::json([
                'status' => false,
                'messages' => 'error'
            ]);
        }
    });
});

$app->get('/', new Inside\HelloController());
$app->get('/users/{name}', new Inside\UserController());

$app->run();
