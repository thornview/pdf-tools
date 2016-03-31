<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();

$app->post('/form/upload', function(Request $request) {
    $f = new PdfTool\File();
    $dir = $_SERVER['DOCUMENT_ROOT']. "/pdf-tool/forms/";
    return $f->uploadFile('form', $dir);
});

$app->post('/form/fill', function(Request $request) use($app) {
    $f = new PdfTool\File();
    $newfile = false; 
    if (!empty($_FILES)) {
        $file = $f->uploadFile('form', 'temp', true);
        $newfile = true;
    } else {
        $file = $request->get('form');
    }
    $data = $request->get('data');
    $p = new PdfTool\Pdf();
    // Need to validate that the fields in the form match the fields in the data set
    $result = $p->fillForm($file, $data);
    if (is_file($result)) {
        if ($newfile) {
            $f->cleanUp($file);
        }
        return $app->sendFile($result);
    } else {
        return "ERROR";
    }
});

$app->post('/form/describe', function(Request $request) {
    $f = new PdfTool\File();
    if (!empty($_FILES)) {
        $file = $f->uploadFile('form', 'temp', true);   
    } else {
        $file = $request->get('form');
    }
    $p = new PdfTool\Pdf();
    $json = $p->getFields($file, 'json');
    $f->cleanUp($file);
    return $json;
});

$app->post('/form/selfreport', function() use($app){
   // Return a PDF of the Form PDF with each field filled in with its field name
    $f = new PdfTool\File();
    $p = new PdfTool\Pdf();
    // Get the file
    $file = $f->uploadFile('form', 'temp', true);

    // Get JSON of the fields
    $json = $p->getFields($file, 'json');

    // Create new JSON - fieldName = FieldName
    $map = array();
    $data = json_decode($json, true);
    foreach ($data as $entry=>$detail) {
        $field = $detail['FieldName'];
        $map[$field] = $field;
    }
    $jmap = json_encode($map);
    // Merge
    $result = $p->fillForm($file, $jmap);
    return $app->sendFile($result);
});

$app->get('/', function(){
    $html = file_get_contents('./documentation.html');
    return $html;
});

$app->run();