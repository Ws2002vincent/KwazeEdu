<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/css/cml_testing.css">
    <style>
        p > span.enlarge {  /* Targets span elements directly within a paragraph */
            font-size: 16px;
            transition: font-size 0.3s ease-in-out;
        }
        
        p > span.enlarge:hover {
            font-size: 36px;
        }
    </style>
</head>
<body>
    <p>
    <?php 
    $string = "My   name   is,   Gugubird.   I   love   oppai   very   much!   Why   are   you   surprised??   My   dingdong   is   HUGEEE..."; 
    $sentences = preg_split('/(?<=[.?!])\s+(?=[a-zA-Z])/',$string);
    foreach ($sentences as $sentence) {
        $words = explode(" ", $sentence);
        for ($i = 0; $i < count($words); $i++) {
            echo '<span class="enlarge">' . $words[$i] . '&nbsp;</span>';
        }
    }
    ?>
    </p>
</body>
</html>