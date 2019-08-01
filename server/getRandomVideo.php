<?php

include_once '../source/video.class.php';
$video = new video('../video_data/');

$data = $video->getVideoChannalData('UCKy3MG7_If9KlVuvw3rPMfw', 5, video::ORDER_BY_RANDOM, video::QUALITY_DEFAULT);
$temp = json_encode($data);

echo "|*$temp";


