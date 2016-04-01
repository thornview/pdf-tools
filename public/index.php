<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap.php';

use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();

// ----- FORM/UPLOAD ----------------------------------------------
$app->post('/form/upload', function(Request $request) {
    $f = new PdfTool\File();
    $dir = $_SERVER['DOCUMENT_ROOT']. "/pdf-tool/forms/";
    return $f->uploadFile('form', true);
});

//----- FORM/FILL -------------------------------------------------
$app->post('/form/fill', function(Request $request) use($app) {
    $f = new PdfTool\File();
    if (empty($_FILES)) {
        $file = FORM_PATH . $request->get('form');
    } else {
        $file = $f->uploadFile('form');
    }
    $data = $request->get('data');
    $p = new PdfTool\Pdf();
    $result = $p->fillForm($file, $data);
    if (is_file($result)) {
        return $app->sendFile($result)
            ->deleteFileAfterSend(true);
    } else {
        return PdfTool\Error::message("Error generating form");
    }
});

// ----- FORM/DESCRIBE -------------------------------------------
$app->post('/form/describe', function(Request $request) {
    $f = new PdfTool\File();
    if (!empty($_FILES)) {
        $file = $f->uploadFile('form');
    } else {
        $file = $request->get('form');
    }
    $p = new PdfTool\Pdf();
    $json = $p->getFields($file);
    $f->cleanUp($file);
    return $json;
});

// ----- FORM/SELFREPORT ------------------------------------------
$app->post('/form/selfreport', function() use($app){
    $f = new PdfTool\File();
    $p = new PdfTool\Pdf();
    $file = $f->uploadFile('form');
    if (is_file($file)) {
        $json = $p->getFields($file);
        $data = json_decode($json, true);
        if (key_exists('ERROR', $data)) {
            return $json; // $json would be json with error message
        } else {
            $map = array();
            foreach ($data as $entry => $detail) {
                $field = $detail['FieldName'];
                $map[$field] = $field;
            }
            $jmap = json_encode($map);
            $result = $p->fillForm($file, $jmap);
            return $app->sendFile($result)
                ->deleteFileAfterSend(true);
        }
    } else {
        return $file; // $file would be json with error message
    }
});

// ----- DOCUMENTATION ------------------------------------------------
$app->get('/', function(){
    $html = file_get_contents('./documentation.html');
    return $html;
});

$app->run();