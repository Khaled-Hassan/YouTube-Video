<!DOCTYPE html>
<?php
set_time_limit(7200);
include_once 'source/video.class.php';
$video = new video();

$data = $video->getAllChannalVideoData('UCKy3MG7_If9KlVuvw3rPMfw', video::ORDER_BY_RANDOM, video::QUALITY_DEFAULT);
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
        <script type="text/javascript" src="source/video.js"></script>
        <link type="text/css" href="source/style.css" rel="stylesheet">
        <!-- CSS for page -->
        <style>
            h1{
                text-align: center;
                margin: 5px 0 15px;
            }
            #logo{
                float: none;
                display: block;
                width: 120px;
                margin: 0 auto;
            }
            #videoWatch {
                float: left;
                margin-top: 20px;
            }
            #playList{
                text-align: right;
                width: 620px;
                float: right;
            }
            ul{
                list-style: none;
                width: 600px;
                border: solid;
                padding: 5px;
            }
            ul li{
                display: inline-block;
                font-size: 20px;
                margin: 5px;
                cursor: pointer;
            }
            ul li:hover{
                color: red;
            }
            p{
                float: right;
                display: inline-block;
                vertical-align: middle;
                width: 460px;
            }
            img{
                float: right;
                margin-left: 10px;
            }
            .status{
                display: inline-block;
                font-size: 18px;
            }
        </style>
    </head>
    <body>
        <h1>Get all channel video in 1 operation</h1>
        <img id="logo" src="<?php echo $video->getChannelThumbnail(); ?>">

        <!-- HTML tags and js for make player view -->
        <div id="videoWatch">
            <div id="player"></div>
            <div id="videoControl"></div>
        </div>
        <script>video.creatWatchElement('videoControl');/*Creat video watch element by sending div id to the function*/</script>
        <!-- End of player -->

        <!-- control and test video -->
        <div id="playList">
            <ul>
                <?php
                $n = 0;
                for ($i = 0; $i < count($data); $i++) {
                    $key = $data[$i]['key'];
                    $thumbnail = $data[$i]['thumbnail'];
                    $title = $data[$i]['title'];
                    $time = $data[$i]['time'];
                    $view = $data[$i]['view'];
                    $like = $data[$i]['like'];
                    $dislike = $data[$i]['dislike'];
                    echo "<li onclick=\"video.loadVideo('$key', 'medium')\"><img src=\"$thumbnail\"> <p>$title ($time) "
                            . "<i class=\"status\"><b><i class=\"fa fa-eye\"></i></b> $view <b><i class=\"fa fa-thumbs-up\">"
                            . "</i></b> $like <b><i class=\"fa fa-thumbs-down\"></i></b> $dislike</i></p></li>";
                }
                ?>
            </ul>
        </div>
    </body>
</html>