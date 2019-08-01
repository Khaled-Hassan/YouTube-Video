<?php

$page = (int) $_POST['page'];

include_once '../source/video.class.php';
$video = new video('../video_data/');

$data = $video->getChannalVideoPageData('UCKy3MG7_If9KlVuvw3rPMfw', 10, $page, video::ORDER_BY_RATING, video::QUALITY_DEFAULT);
$temp = json_encode(['page' => $video->channelPageNumber, 'data' => $data]);

echo "|*$temp";


