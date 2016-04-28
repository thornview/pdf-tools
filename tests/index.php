<!doctype html>
<html>
<head>
    <title>
        Testing
    </title>
    <style>
        body {
            background-color: #9bc2c2;
        }
        .box {
            border: 1px solid #003333;
            border-radius: 20px;
            padding: 10px;
            margin-top: 20px;
            width: 45%;
            display: inline-block;
            background-color: #fff;
        }
        h1, h2 {
            font-family: "Source Sans Pro", Helvetica, Arial, sans-serif;
            color: #0d4d4d;
        }
    </style>
</head>
<body>
<h1>Implementation Examples</h1>
<div>
      <div class="box">
        <h2>Form/Fill (upload user file)</h2>
        <?php
        $data = array(
            '0.pte.name' => "Grape Ape",
            '0.sub.name' => "Underdog",
            '0.pte.pi'   => "Mr. Peabody",
            '0.sub.pi'   => "Hong Kong Fooey"
        );
        $json = htmlspecialchars(json_encode($data));
        ?>
        <form action="http://<?= $_SERVER['SERVER_NAME']?>/pdf-tool/index.php/form/fill" method="post" enctype="multipart/form-data">
            <p><input type="file" name="form"></p>
            <input type="hidden" name="data" value="<?= $json ?>">
            <input type="submit">
        </form>
    </div>

    <div class="box">
        <h2>Form/Fill (using server-hosted pdf)</h2>
        <?php
        $data = array(
            '0.pte.name' => "Buster Keaton",
            '0.sub.name' => "Harold Lloyd",
            '0.pte.pi'   => "Charlie Chaplin",
            '0.sub.pi'   => "Clara Bow"
        );
        $json = htmlspecialchars(json_encode($data));
        ?>
        <form action="http://<?= $_SERVER['SERVER_NAME']?>/pdf-tool/index.php/form/fill" method="post">
            <p>Form: <input type="text" name="form" value="fdp-master.pdf"></p>
            <input type="hidden" name="data" value="<?= $json ?>">
            <input type="submit">
        </form>
    </div>

    <div class="box">
        <h2>Form/Selfreport</h2>
        <form action="http://<?= $_SERVER['SERVER_NAME']?>/pdf-tool/index.php/form/selfreport" method="post" enctype="multipart/form-data">
            <p><input type="file" name="form"></p>
            <input type="submit">
        </form>
    </div>

    <div class="box">
        <h2>Table/Extract</h2>
        <form action="http://<?= $_SERVER['SERVER_NAME/]?>pdf-tool/index.php/table/extract" method="post" enctype="multipart/form-data">
            <p>File: <input name="pdf" type="file"></p>
            <input type="submit" value="Push Me">
        </form>
    </div>
</div>
</body>
</html>
