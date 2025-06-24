<?php

    session_start();
    $print_html=$_SESSION['PrintHtml'];
    unset($_SESSION['PrintHtml']);
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $print_html['title']; ?></title>
    <link rel="stylesheet" href="style.css">
   
   
</head>
<body>

    
    <div class="content">
    <?php echo $print_html['content']; ?>
		</div>
    </div>
    <script src="script.js"></script>
</body>
</html>