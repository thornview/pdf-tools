<?php
require_once __DIR__ . '/vendor/autoload.php';
define("TEMP_PATH", __DIR__ . "/temp/");
define("FORM_PATH", __DIR__ . "/forms/");

define("PDFTK", "pdftk");
define("TABULA", "tabula");

use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();

// ----- FORM/UPLOAD ----------------------------------------------
$app->post('/form/upload', function() {
    $f = new PdfTool\File();
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
    if (empty($file)) {
        return PdfTool\Error::message("No file found.");
    }
    $data = $request->get('data');
    $p = new PdfTool\PdfForms();
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
    $p = new PdfTool\PdfForms();
    $json = $p->getFields($file);
    unlink($file);
    return $json;
});

// ----- FORM/SELFREPORT ------------------------------------------
$app->post('/form/selfreport', function() use($app){
    $f = new PdfTool\File();
    $p = new PdfTool\PdfForms();
    $file = $f->uploadFile('form');
    if (is_file($file)) {
        $json = $p->getFields($file);
        $data = json_decode($json, true);
        if (key_exists('ERROR', $data)) {
            return $json; // json would have error message
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

// ----- TABLE/EXTRACT -----------------------------------------------
$app->post('/table/extract', function(){
    $f = new PdfTool\File();
    $t = new PdfTool\PdfTables();
    
    $file = $f->uploadFile('pdf');
    $json = $t->extractTableDataFromPdf($file);
    
    unlink($file);
    return $json;
});

// ----- DOCUMENTATION ------------------------------------------------
$app->get('/', function(){
    $html = file_get_contents('./public/documentation.html');
    return $html;
});

$app->run();