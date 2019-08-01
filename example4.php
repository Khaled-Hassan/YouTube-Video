<!DOCTYPE html>
<?php
include_once 'source/video.class.php';
$video = new video();

$data = $video->getChannalVideoPageData('UCKy3MG7_If9KlVuvw3rPMfw', 10, 1, video::ORDER_BY_RATING, video::QUALITY_DEFAULT);
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
        <script type="text/javascript" src="source/video.js"></script>
        <script type="text/javascript" src="source/ajax.js"></script>
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
            #direction{
                margin: 5px;
            }
            #direction button{
                font-size: 24px;
                font-weight: bold;
            }
            #direction #number{
                display: inline-block;
                width: 50px;
                text-align: center;
                font-size: 24px;
                font-weight: bold;
            }
            #videoWatch {
                float: left;
                margin-top: 60px;
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
    <body onload="getNext()">
        <h1>Get all video channel by pages every page contain 10 video</h1>
        <img id="logo" src="<?php echo $video->getChannelThumbnail(); ?>">

        <!-- HTML tags and js for make player view -->
        <div id="videoWatch">
            <div id="player"></div>
            <div id="videoControl"></div>
        </div>
        <script>video.creatWatchElement('videoControl');/*Creat video watch element by sending div id to the function*/</script>
        <!-- End of player -->

        <div id="direction">
            <button onclick="getPrevious()"><i class="fa fa-angle-double-left"></i></button>
            <div id="number">1</div>
            <button onclick="getNext()"><i class="fa fa-angle-double-right"></i></button>
        </div>
        <!-- control and test video -->
        <div id="playList">
            <ul id="list">
                <?php
//                for ($i = 0; $i < count($data); $i++) {
//                    $key = $data[$i]['key'];
//                    $thumbnail = $data[$i]['thumbnail'];
//                    $title = $data[$i]['title'];
//                    $time = $data[$i]['time'];
//                    $view = $data[$i]['view'];
//                    $like = $data[$i]['like'];
//                    $dislike = $data[$i]['dislike'];
//                    echo "<li onclick=\"video.loadVideo('$key', 'medium')\"><img src=\"$thumbnail\"> <p>$title ($time) "
//                    . "<i class=\"status\"><b><i class=\"fa fa-eye\"></i></b> $view <b><i class=\"fa fa-thumbs-up\">"
//                    . "</i></b> $like <b><i class=\"fa fa-thumbs-down\"></i></b> $dislike</i></p></li>";
//                }
                ?>
            </ul>
        </div>
    </body>
</html>
<script>
    var page = 0;
    function getPrevious() {
        if(page === 1){
            return;
        }
        var param = ['page'];
        param['page'] = page - 1;
        AJAX.call('server/getvideoPage.php', 'POST', param, true, 'showVideo');
    }
    function getNext() {
        var param = ['page'];
        param['page'] = page + 1;
        AJAX.call('server/getvideoPage.php', 'POST', param, true, 'showVideo');
    }
    function showVideo(request){
        var temp = '';
        page = request.page;
        document.getElementById('number').innerHTML = page;
        request = request.data;
        for(var i = 0; i < request.length; i++){
            var curr = request[i], key = curr.key, thumbnail = curr.thumbnail, title = curr.title, 
                    time = curr.time, view = curr.view, like = curr.like, dislike = curr.dislike;
            
            temp += "<li onclick=\"video.loadVideo('" + key + "', 'medium')\"><img src=\"" + thumbnail + "\"> <p>" + 
                    title + " (" + time  + ") <i class=\"status\"><b><i class=\"fa fa-eye\"></i></b> " + view + 
                    " <b><i class=\"fa fa-thumbs-up\"></i></b> " + like + " <b><i class=\"fa fa-thumbs-down\"></i></b> " + 
                    dislike + "</i></p></li>";
        }
        
        document.getElementById('list').innerHTML = temp;
    }
</script>