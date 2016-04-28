<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PDF Tools</title>
    <link href='https://fonts.googleapis.com/css?family=Source+Code+Pro|Source+Sans+Pro' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="../styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
</head>
<body>
<h1>PDF Tools : File Manager</h1>
<div class="container">
    <div class="box">
        <h2>Files</h2>
        <table width="60%" cellspacing="0" class="fileTable">
        <?php
            chdir("../forms");
            $directoryList = scandir("./");
            $fileList = array_filter($directoryList, "is_file");
            foreach ($fileList as $entry) :?>
                <tr id="<?= $entry ?>">
                    <td>
                        <?= $entry; ?>
                    </td>
                    <td class="deleteButton">
                        X
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <div style="width: 60%; margin-right: auto; margin-left: auto">
            <div class="addButton">
                + Add A File
            </div>
            <div class="fileUpload" style="display:none">
                <form method="post" action="../index.php/file/upload" enctype="multipart/form-data">
                    <input type="file" name="form" id="fileButton" style="font-size: 0.9em"><br>
                    <input type="hidden" name="url" value="http://<?= $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] ?>">
                    <input type="submit" id="submitBtn" class="uploadBtn">
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $(".deleteButton").hover(function(){
            $(this).parent().toggleClass('deleteWarning');
        });

        $(".deleteButton").on("click", function(){
            var fileName = $(this).parent().attr("id");
            var url =  $(location).attr('href');
            var path = "public/filemanager.php";
            var strStop = url.length - path.length;
            var delUrl = url.substr(0, strStop) + "index.php/file/delete";
            console.log("URL: " + delUrl);
            $.post(delUrl, {file: fileName});
            $(this).parent().fadeOut();
        });

        $(".addButton").on("click", function(){
            $(".fileUpload").slideToggle();
            $("#fileButton").trigger('click');
        });
    });
</script>
</body>
</html>
