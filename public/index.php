<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

session_start();

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

// Register middleware
require __DIR__ . '/../src/middleware.php';

// Register routes
require __DIR__ . '/../src/routes.php';


require __DIR__ . '/../src/library.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

//Implementation Goes Here

$app->get('/api/library/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
	if(!is_numeric($id) || $id < 0) {
		$errorData = array("ErrorMessage" => "Invalid ID");
		$response->withJson($errorData, 200);
	} else {
		$library = Library::populateFromId($id);
		if($library == null) {
			$message = array("Message" => "ID not found");
			$response->withJson($message, 200);
		} else {
			$response->withJson($library, 200);
		}
	}
    return $response;
});

$app->post('/api/library/{token}', function (Request $request, Response $response) {
	$authToken = $request->getAttribute('token');
	if(!$authToken || $authToken == "") {
		$errorData = array("ErrorMessage" => "Un authorised, invalid auth token.");
		$response->withJson($errorData, 401);
		return $response;
	}
	$library = $request->getParam("library", null);
	if($library === null) {
		$errorData = array("ErrorMessage" => "You must specify a library");
		$response->withJson($errorData, 200);
		return $response;
	}
	$libraryJson = json_decode($library);
	if(!$libraryJson) {
		$errorData = array("ErrorMessage" => "Malformed Library JSON");
		$response->withJson($errorData, 200);
		return $response;
	}
	$l = new Library($libraryJson);
	$l->writeToFile();
	$data = array("Message" => "Successfully Added");
	$response->withJson($data, 200);
	return $response;
});

// Run app
$app->run();


