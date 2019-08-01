<!DOCTYPE html>
<?php
//     thumbs-up     thumbs-down     eye
include_once 'source/video.class.php';
$video = new video();

$video->loadVideo('SYsspLKYeUM');
$video1 = $video->getVideoTitle() . ' (' . $video->getVideoDurationTime() . ')' .
        '<i class="status"><b><i class="fa fa-eye"></i></b> ' . $video->getVideoViewCount() .
        ' <b><i class="fa fa-thumbs-up"></i></b> ' . $video->getVideoLikeCount() .
        ' <b><i class="fa fa-thumbs-down"></i></b> ' . $video->getVideoDislikeCount() . '</i>';
$video1Thumbnail = $video->getVideoThumbnail(video::QUALITY_DEFAULT);

$video->loadVideo('r8h_WKqm3zA');
$video2 = $video->getVideoTitle() . ' (' . $video->getVideoDurationTime() . ')'.
        '<i class="status"><b><i class="fa fa-eye"></i></b> ' . $video->getVideoViewCount() .
        ' <b><i class="fa fa-thumbs-up"></i></b> ' . $video->getVideoLikeCount() .
        ' <b><i class="fa fa-thumbs-down"></i></b> ' . $video->getVideoDislikeCount() . '</i>';
$video2Thumbnail = $video->getVideoThumbnail(video::QUALITY_DEFAULT);

$video->loadVideo('qkc8oAr7pIE');
$video3 = $video->getVideoTitle() . ' (' . $video->getVideoDurationTime() . ')'.
        '<i class="status"><b><i class="fa fa-eye"></i></b> ' . $video->getVideoViewCount() .
        ' <b><i class="fa fa-thumbs-up"></i></b> ' . $video->getVideoLikeCount() .
        ' <b><i class="fa fa-thumbs-down"></i></b> ' . $video->getVideoDislikeCount() . '</i>';
$video3Thumbnail = $video->getVideoThumbnail(video::QUALITY_DEFAULT);
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
            #playList{
                direction: rtl;
                width: 620px;
                float: right;
            }
            input{
                width: 350px;
            }
            input, button{
                font-size: 18px;
            }
            ul{
                list-style: none;
                width: 600px;
                height: 400px;
                border: solid;
                padding: 5px;
            }
            ul li{
                display: inline-block;
                font-size: 24px;
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
                margin-top: 5px;
            }
            img{
                float: right;
                margin-left: 10px;
            }
            .status{
                display: inline-block;
                font-size: 18px;
            }
            #videoWatch{
                float: left;
            }
        </style>
    </head>
    <body>
        <h1>Show player and video information</h1>
        <!-- HTML tags and js for make player view -->
        <div id="videoWatch">
            <div id="player"></div>
            <div id="videoControl"></div>
        </div>
        <script>video.creatWatchElement('videoControl', false, false);/*Creat video watch element by sending div id to the function*/</script>
        <!-- End of player -->

        <!-- control and test video -->
        <div id="playList">
            <input id="videoAddress" type="text" value="Pb_mg1j2HSU">
            <button onclick="video.loadVideo(document.getElementById('videoAddress''medium').value)">Load</button>
            <ul>
                <li onclick="video.loadVideo('SYsspLKYeUM', 'medium', 60, 80)"><img src="<?php echo $video1Thumbnail; ?>"> <p><?php echo "$video1"; ?></p></li>
                <li onclick="video.loadVideo('r8h_WKqm3zA', 'medium')"><img src="<?php echo $video2Thumbnail; ?>"> <p><?php echo "$video2"; ?></p></li>
                <li onclick="video.loadVideo('qkc8oAr7pIE', 'medium')"><img src="<?php echo $video3Thumbnail; ?>"> <p><?php echo "$video3"; ?></p></li>
            </ul>
        </div>
        <!--<button onclick="video.loadVideo('r8h_WKqm3zA', 'medium')">test</button>-->
    </body>
</html>