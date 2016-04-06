<!doctype html>
<html>
<head>
    <title>
        ScRAtChpAd
    </title>
</head>
<body>
<h1>ScRAtChpAd</h1>
<div>
    <button id="api">API Button</button>
    <div id="fields"></div>

    <div style="border: 1px solid #708090; border-radius: 20px; padding: 10px; margin-top: 20px">
        <h2>Fill Form</h2>
        <?php
        $data = array(
            '0.pte.name' => "Grape Ape",
            '0.sub.name' => "Underdog",
            '0.pte.pi'   => "Mr. Peabody",
            '0.sub.pi'   => "Hong Kong Fooey"
        );
        $json = htmlspecialchars(json_encode($data));
        ?>
        <form action="http://localhost/index.php/form/fill" method="post" enctype="multipart/form-data">
            <p><input type="file" name="form"></p>
            <input type="hidden" name="data" value="<?= $json ?>">
            <input type="submit">
        </form>
    </div>

    <div style="border: 1px solid #cd5c5c; border-radius: 20px; padding: 10px;  margin-top: 20px">
        <h2>Self Report</h2>
        <form action="http://localhost/index.php/form/selfreport" method="post" enctype="multipart/form-data">
            <p><input type="file" name="form"></p>
            <input type="submit">
        </form>
    </div>

    <div style="border: 1px solid #cd5c5c; border-radius: 20px; padding: 10px;  margin-top: 20px">
        <h2>Merge Local File</h2>
        <?php
        $data = array(
            '0.pte.name' => "Buster Keaton",
            '0.sub.name' => "Harold Lloyd",
            '0.pte.pi'   => "Charlie Chaplin",
            '0.sub.pi'   => "Clara Bow"
        );
        $json = htmlspecialchars(json_encode($data));
        ?>
        <form action="http://localhost/index.php/form/fill" method="post">
            <p>Form: <input type="text" name="form" value="fdp-master.pdf"></p>
            <input type="hidden" name="data" value="<?= $json ?>">
            <input type="submit">
        </form>
    </div>
</div>
</body>
</html>
