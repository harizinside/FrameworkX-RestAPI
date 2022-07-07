<?php

require __DIR__ . '/../vendor/autoload.php';

(new Inside\DotEnv(__DIR__ . '/../.env'))->load();
$db = (new React\MySQL\Factory())->createLazyConnection(getenv('DATABASE_DSN'));

$app = new FrameworkX\App();

$app->get('/users', function (Psr\Http\Message\ServerRequestInterface $request) use ($db) {
    $input = $request->getParsedBody();
    return $db->query(
        'SELECT title FROM book WHERE isbn = ?',
        [$isbn]
    )->then(function (React\MySQL\QueryResult $result) {


        if (count($result->resultRows) === 0) {
            return React\Http\Message\Response::plaintext(
                "Book not found\n"
            )->withStatus(React\Http\Message\Response::STATUS_NOT_FOUND);
        }

        $data = $result->resultRows[0]['title'];
        return React\Http\Message\Response::plaintext(
            $data
        );
    });
});

$app->get('/', new Inside\HelloController());
$app->get('/users/{name}', new Inside\UserController());

$app->post('/auth/register', new Inside\Register());


$app->run();
