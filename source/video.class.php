<?php

/**
 * The video class contain all needed function to get any YouTube video information.
 * @author Khaled Hassan
 * @category YouTube video
 * @link khaled.h.developer@gmail.com
 */
class video {

    /**
     * @var The standard quality size 640 * 480.
     */
    public const QUALITY_STANDARD = 1;

    /**
     * @var The high quality size 480 * 360.
     */
    public const QUALITY_HIGH = 2;

    /**
     * @var The medium quality size 320 * 180.
     */
    public const QUALITY_MEDIUM = 3;

    /**
     * @var The medium quality size 120 * 90.
     */
    public const QUALITY_DEFAULT = 4;

    /**
     * @var Order video result by title.
     */
    public const ORDER_BY_LIST = 'list';

    /**
     * @var Order video result by title.
     */
    public const ORDER_BY_TITLE = 'title';

    /**
     * @var Order video result by date.
     */
    public const ORDER_BY_DATE = 'date';

    /**
     * @var Order video result by rating.
     */
    public const ORDER_BY_RATING = 'rating';

    /**
     * @var Order video result by view count.
     */
    public const ORDER_BY_VIEW = 'viewCount';

    /**
     * @var Order video result randomly.
     */
    public const ORDER_BY_RANDOM = 'random';

    /**
     * @var str The YouTube API key.
     */
    public $API_key = 'AIzaSyCh0q-Zax9jO-n1jfDrVC3kDfTYFmd-2p4';

    /**
     * @var str The save path of JSON data.
     */
    public $savePath = 'video_data/';

    /**
     * @var int The maximum time by hours for using video file data. <br> <b>0</b> mean the file will not expire.
     */
    public $videoUpdateTime = 0;

    /**
     * @var int The maximum time by hours for using channel file data. <br> <b>0</b> mean the file will not expire.
     */
    public $channelUpdateTime = 24;

    /**
     * @var int The maximum time by hours for using playlist file data. <br> <b>0</b> mean the file will not expire.
     */
    public $playlistUpdateTime = 24;

    /**
     * @var int The YouTube channel search result page number.
     */
    public $channelPageNumber = 0;

    /**
     * @var int The YouTube playlist search result page number.
     */
    public $playlistPageNumber = 0;

    /**
     * @var str The YouTube channel ID.
     */
    private $channelId;

    /**
     * @var string The YouTube channel result order.
     */
    private $channelOrder;

    /**
     * @var int The YouTube channel maximum result.
     */
    private $channelMaxResult = 0;

    /**
     * @var string The URL of channel.
     */
    private $channelURL = '';

    /**
     * @var string The code of channel search next page.
     */
    private $channelNextPage = '';

    /**
     * @var string The code of channel search previous page.
     */
    private $channelPreviousPage = '';

    /**
     * @var array contain all channel information.
     */
    private $channelInfo = [];

    /**
     * @var array contain all channel information.
     */
    private $channelVideoInfo = ['items' => []];

    /**
     * @var str The YouTube video key.
     */
    private $videoKey;

    /**
     * @var array contain all video information.
     */
    private $videoInfo = ['items' => []];

    /**
     * @var str The YouTube video key.
     */
    private $playlistId;

    /**
     * @var string The URL of playlist.
     */
    private $playlistURL = '';

    /**
     * @var string The YouTube channel result order.
     */
    private $playlistOrder;

    /**
     * @var int The YouTube channel maximum result.
     */
    private $playlistMaxResult = 0;

    /**
     * @var string The code of playlist search next page.
     */
    private $playlistNextPage = '';

    /**
     * @var string The code of playlist search previous page.
     */
    private $playlistPreviousPage = '';

    /**
     * @var array contain all video information.
     */
    private $playlistInfo = [];

    /**
     * construct class. <br> You send video key and API key to get video information <br>  Or call loadVideo function after initialized class
     * @param string $savePath [optional] <br> The save path of JSON data <br> <b>video_data/</b> is the default value. <p></p>
     * @param int $videoUpdateTime [optional] <br> The maximum time by hours for using video file data <b>0</b> mean the file will not expire <br> <b>24</b> is the default value. <p></p>
     * @param int $channelUpdateTime [optional] <br> The maximum time by hours for using channel file data <b>0</b> mean the file will not expire <br> <b>1</b> is the default value. <p></p>
     * @param int $playlistUpdateTime [optional] <br> The maximum time by hours for using playlist file data <b>0</b> mean the file will not expire <br> <b>6</b> is the default value. <p></p>
     * @param string $videoKey [optional] <br> The YouTube video key. <p></p>
     * @param string $channelId [optional] <br> The YouTube channel ID. <p></p>
     * @param string $API_key [optional] <br> The YouTube API key <p></p>
     */
    function __construct($savePath = 'video_data/', $videoUpdateTime = 24, $channelUpdateTime = 24, $playlistUpdateTime = 24, $videoKey = '', $channelId = '', $API_key = '') {
        $this->savePath = $savePath;
        $this->videoUpdateTime = $videoUpdateTime;
        $this->channelUpdateTime = $channelUpdateTime;
        $this->playlistUpdateTime = $playlistUpdateTime;

        if ($API_key !== '') {
            $this->API_key = $API_key;
        }
        if ($videoKey !== '') {
            $this->loadVideo($videoKey);
        }
        if ($channelId !== '') {
            $this->loadChannal($channelId);
        }
    }

    /* -*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*- */

    /**
     * Get YouTube video channel information in array and can use random result.
     * @param string $channelId <br> The YouTube channel ID. <p></p>
     * @param int $maxVideoResult <br> The the maximum number of videos get from this channel. <br> <b>1</b> is minimum number and <b>50</b> is maximum number <p></p>
     * @param string $order <br> The order type of video in list. <br> <b>date</b> Order by date newest is first <b>this is default value</b>. <br> <b>title</b> Order by title characters. <b>this is default value</b>. <br> <b>random</b> Order randomly.  <p></p>
     * @param int $quality [optional] <br> <b>QUALITY_STANDARD</b> Standard quality size 640 * 480 <br> <b>QUALITY_HIGH</b> High quality size 480 * 360 <br> <b>QUALITY_MEDIUM</b> Medium quality size 320 * 180 <br> <b>QUALITY_DEFAULT</b> Default quality size 120 * 90 <b>(default value)</b>. <br> <p></p>
     * @return array <br> YouTube channel video information array like <br> [['key', 'title', 'description', 'publishedAt', 'duration', 'time', 'view', 'like', 'dislike', 'thumbnail', 'channelId', 'channelTitle'], ...].
     */
    public function getVideoChannalData($channelId = '', $maxVideoResult = 5, $order = self::ORDER_BY_DATE, $quality = self::QUALITY_DEFAULT) {
        $this->loadChannal($channelId, $maxVideoResult, $order);

        $result = [];
        for ($i = 0; $i < count($this->channelVideoInfo); $i++) {
            $videoKey = $this->channelVideoInfo[$i]['id']['videoId'];
            $temp = $this->getVideoData($videoKey, $quality);
//            $temp = $this->getVideoData($videoKey, $quality);
            if (trim($temp['title']) !== '' && (int) $temp['duration'] !== 0) {
                $result[] = $temp;
            } else {
                unlink($this->savePath . $videoKey . '.json');
            }
        }

        return $result;
    }

    /**
     * Get YouTube video channel information of spasific page in array.
     * @param string $channelId <br> The YouTube channel ID. <p></p>
     * @param int $maxVideoResult <br> The the maximum number of videos get from this channel. <br> <b>1</b> is minimum number and <b>50</b> is maximum number <p></p>
     * @param int $pageNumber <br> The page number of request <b>1</b> is default value <p></p>
     * @param string $order <br> The order type of video in list. <br> <b>date</b> Order by date newest is first <b>this is default value</b>. <br> <b>title</b> Order by title characters. <b>this is default value</b>. <br> <b>random</b> Order randomly.  <p></p>
     * @param int $quality [optional] <br> <b>QUALITY_STANDARD</b> Standard quality size 640 * 480 <br> <b>QUALITY_HIGH</b> High quality size 480 * 360 <br> <b>QUALITY_MEDIUM</b> Medium quality size 320 * 180 <br> <b>QUALITY_DEFAULT</b> Default quality size 120 * 90 <b>(default value)</b>. <br> <p></p>
     * @return array <br> YouTube channel video information array like <br> [['key', 'title', 'description', 'publishedAt', 'duration', 'time', 'view', 'like', 'dislike', 'thumbnail', 'channelId', 'channelTitle'], ...].
     */
    public function getChannalVideoPageData($channelId = '', $maxVideoResult = 5, $pageNumber = 1, $order = self::ORDER_BY_DATE, $quality = self::QUALITY_DEFAULT) {
        if ($order === self::ORDER_BY_RANDOM) {
            $order = self::ORDER_BY_DATE;
        }
        $this->loadChannal($channelId, $maxVideoResult, $order);
        $video = $this->channelVideoInfo;
        $this->channelPageNumber = 1;

        $result = [];
        for ($i = 2; $i <= $pageNumber && trim($this->channelNextPage) !== ''; $i++) {
            $video = $this->getChannalVideoNextPageData();
            $this->channelPageNumber = $i;
        }

        for ($i = 0; $i < count($video); $i++) {
            $videoKey = $video[$i]['id']['videoId'];
            $temp = $this->getVideoData($videoKey, $quality);
            if (trim($temp['title']) !== '' && (int) $temp['duration'] !== 0) {
                $result[] = $temp;
            } else {
                unlink($this->savePath . $videoKey . '.json');
            }
        }

        return $result;
    }

    /**
     * Get YouTube all video channel information in array.
     * @param string $channelId <br> The YouTube channel ID. <p></p>
     * @param string $order <br> The order type of video in list. <br> <b>date</b> Order by date newest is first <b>this is default value</b>. <br> <b>title</b> Order by title characters. <b>this is default value</b>. <br> <b>random</b> Order randomly.  <p></p>
     * @param int $quality [optional] <br> <b>QUALITY_STANDARD</b> Standard quality size 640 * 480 <br> <b>QUALITY_HIGH</b> High quality size 480 * 360 <br> <b>QUALITY_MEDIUM</b> Medium quality size 320 * 180 <br> <b>QUALITY_DEFAULT</b> Default quality size 120 * 90 <b>(default value)</b>. <br> <p></p>
     * @return array <br> YouTube channel video information array like <br> [['key', 'title', 'description', 'publishedAt', 'duration', 'time', 'view', 'like', 'dislike', 'thumbnail', 'channelId', 'channelTitle'], ...].
     */
    public function getAllChannalVideoData($channelId = '', $order = self::ORDER_BY_DATE, $quality = self::QUALITY_DEFAULT) {
        $this->channelPageNumber = 0;
        if ($order === self::ORDER_BY_RANDOM) {
            $pageOrder = self::ORDER_BY_DATE;
        } else {
            $pageOrder = $order;
        }
        $video = $this->loadChannal($channelId, 50, $pageOrder);

        while (trim($this->channelNextPage) !== '') {
            $temp = $this->getChannalVideoNextPageData();
            for ($i = 0; $i < count($temp); $i++) {
                $video[] = $temp[$i];
            }
        }

        if ($order === self::ORDER_BY_RANDOM) {
            $videoCount = count($video);
            $video = $this->getRandomData($video, $videoCount, $videoCount);
            $this->channelVideoInfo = $video;
        }

        $result = [];
        for ($i = 0; $i < count($video); $i++) {
            $videoKey = $video[$i]['id']['videoId'];
            $temp = $this->getVideoData($videoKey, $quality);
            if (trim($temp['title']) !== '' && (int) $temp['duration'] !== 0) {
                $result[] = $temp;
            } else {
                unlink($this->savePath . $videoKey . '.json');
            }
        }

        return $result;
    }

    /**
     * Get YouTube video channel search result next page.
     * @return array <br> YouTube channel video information array like <br> [['key', 'title', 'description', 'publishedAt', 'duration', 'time', 'view', 'like', 'dislike', 'thumbnail', 'channelId', 'channelTitle'], ...] <br> <b>FALSE</b> if this is last page.
     */
    public function getChannalVideoNextPageData() {
        if (trim($this->channelNextPage) === '') {
            return false;
        }

        $this->channelPageNumber += 1;
        $video = $this->loadChannalVideo($this->channelId, $this->channelMaxResult, $this->channelNextPage, $this->channelOrder);

        return $video;
    }

    /**
     * Get YouTube video channel search result previous page.
     * @return array <br> YouTube channel video information array like <br> [['key', 'title', 'description', 'publishedAt', 'duration', 'time', 'view', 'like', 'dislike', 'thumbnail', 'channelId', 'channelTitle'], ...] <br> <b>FALSE</b> if this is first page.
     */
    public function getChannalVideoPreviousPageData() {
        if (trim($this->channelPreviousPage) === '') {
            return false;
        }

        $this->channelPageNumber -= 1;
        $video = $this->loadChannalVideo($this->channelId, $this->channelMaxResult, $this->channelPreviousPage, $this->channelOrder);

        return $video;
    }

    /**
     * Get YouTube video information array.
     * @param string $channelId <br> The YouTube channel ID. <p></p>
     * @param int $maxVideoResult <br> The the maximum number of videos get from this channel. <br> <b>1</b> is minimum number and <b>50</b> is maximum number <p></p>
     * @param string $order <br> The order type of video in list. <br> <b>date</b> Order by date newest is first <b>this is default value</b>. <br> <b>title</b> Order by title characters. <b>this is default value</b>. <br> <b>random</b> Order randomly.  <p></p>
     * @return array <br> YouTube channel information.
     */
    public function loadChannal($channelId, $maxVideoResult = 5, $order = self::ORDER_BY_DATE) {
        $this->channelId = $channelId;
        $this->channelOrder = $order;
        $this->channelMaxResult = $maxVideoResult;

        $temp = $this->getSavedFile($channelId . '_CI', $this->channelUpdateTime);
        if ($temp) {
            $this->channelInfo = $temp;
        } else {
            $arrContextOptions = array(
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ),
            );

            $url = "https://www.googleapis.com/youtube/v3/search?part=snippet,id&type=channel&channelId=$channelId&key=$this->API_key";
            $info = json_decode(file_get_contents($url, FALSE, stream_context_create($arrContextOptions)), true);
            $this->channelInfo = $info['items'][0];
            $this->saveJSON($this->channelInfo, $channelId . '_CI');
        }

        $this->channelPageNumber = 1;
        $this->loadChannalVideo($channelId, $maxVideoResult, '', $order);

        return $this->channelInfo;
    }

    /**
     * Get YouTube video information array.
     * @param string $channelId <br> The YouTube channel ID. <p></p>
     * @param int $maxVideoResult <br> The the maximum number of videos get from this channel. <br> <b>1</b> is minimum number and <b>50</b> is maximum number <p></p>
     * @param string $pageToken <br> The page token code <p></p>
     * @param string $order <br> The order type of video in list. <br> <b>date</b> Order by date newest is first <b>this is default value</b>. <br> <b>title</b> Order by title characters. <b>this is default value</b>. <br> <b>random</b> Order randomly.  <p></p>
     * @return array <br> YouTube channel information.
     */
    private function loadChannalVideo($channelId, $maxVideoResult = 5, $pageToken = '', $order = self::ORDER_BY_DATE) {
        if ((int) $maxVideoResult < 1) {
            $maxVideoResult = 1;
        } elseif ((int) $maxVideoResult > 50) {
            $maxVideoResult = 50;
        }
        if ($order === self::ORDER_BY_RANDOM) {
            $realMaxResult = 50;
        } else {
            $realMaxResult = (int) $maxVideoResult;
        }

        $temp = $this->getSavedFile($channelId . '_' . $realMaxResult . '_' . $pageToken, $this->channelUpdateTime);
        if ($temp) {
            $channelData = $temp;
            $this->channelInfo = $this->getSavedFile($channelId . '_CI', $this->channelUpdateTime);
        } else {
            $arrContextOptions = array(
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ),
            );
            switch ($order) {
                case self::ORDER_BY_DATE:
                    $resultOrder = 'date';
                    break;
                case self::ORDER_BY_TITLE:
                    $resultOrder = 'title';
                    break;
                case self::ORDER_BY_RATING:
                    $resultOrder = 'rating';
                    break;
                case self::ORDER_BY_VIEW:
                    $resultOrder = 'viewCount';
                    break;
                case self::ORDER_BY_RANDOM:
                    $resultOrder = 'date';
                    break;
                default:
                    $resultOrder = 'date';
            }

            $url = "https://www.googleapis.com/youtube/v3/search?part=snippet,id&type=video&order=$resultOrder&channelId=$channelId&maxResults=$realMaxResult&key=$this->API_key";

            if (trim($pageToken) !== '') {
                $url .= "&pageToken=$pageToken";
            }

            $channel = file_get_contents($url, FALSE, stream_context_create($arrContextOptions));
            $channelData = json_decode($channel, true);
            $this->saveJSON($channelData, $channelId . '_' . $realMaxResult . '_' . $pageToken);
        }

        if (isset($channelData['nextPageToken'])) {
            $this->channelNextPage = $channelData['nextPageToken'];
        } else {
            $this->channelNextPage = '';
        }
        if (isset($channelData['previousPageToken'])) {
            $this->channelPreviousPage = $channelData['previousPageToken'];
        } else {
            $this->channelPreviousPage = '';
        }

        if ($order === self::ORDER_BY_RANDOM) {
            $videoCount = count($channelData['items']);
            if ($videoCount > 0) {
                $items = $this->getRandomData($channelData['items'], $maxVideoResult, $videoCount);
                $this->channelVideoInfo = $items;
            } else {
                $items = [];
            }
        } else {
            $this->channelVideoInfo = $channelData['items'];
            $items = $channelData['items'];
        }

        return $items;
    }

    /**
     * Get YouTube channel information in JDON array.
     * @param string $channelId <br> The YouTube channel ID. <p></p>
     * @param int $maxVideoResult <br> The the maximum number of videos get from this channel. <br> <b>5</b> is minimum number and <b>50</b> is maximum number <p></p>
     * @param string $order <br> The order type of video in list. <br> <b>date</b> Order by date newest is first <b>this is default value</b>. <br> <b>title</b> Order by title characters. <b>this is default value</b>. <br> <b>random</b> Order randomly.  <p></p>
     * @return array <br> YouTube channel video information array like <br> [['key', 'title', 'description', 'publishedAt', 'duration', 'time', 'view', 'like', 'dislike', 'thumbnail', 'channelId', 'channelTitle'], ...].
     */
    public function getChannalData($channelId = '', $maxVideoResult = 0, $order = 'date', $quality = self::QUALITY_DEFAULT) {
        if ($channelId !== '') {
            $this->loadChannal($channelId, $maxVideoResult, $order);
        }

        $result = [];
        $result['title'] = $this->getChannelTitle();
        $result['description'] = $this->getChannelDescription();
        $result['publishedAt'] = $this->getChannelPublishedAt();
        $result['thumbnail'] = $this->getChannelThumbnail($quality);

        return $result;
    }

    /**
     * Get YouTube channel title.
     * @return string <br> channel title.
     */
    public function getChannelTitle() {
        $channelTitle = $this->channelInfo['snippet']['title'];

        return $channelTitle;
    }

    /**
     * Get YouTube channel description.
     * @return string <br> channel description.
     */
    public function getChannelDescription() {
        $description = $this->channelInfo['snippet']['description'];

        return $description;
    }

    /**
     * Get YouTube channel published date time.
     * @return string <br> channel published date time.
     */
    public function getChannelPublishedAt() {
        $publishedAt = $this->channelInfo['snippet']['publishedAt'];

        return $publishedAt;
    }

    /**
     * Get YouTube channel thumbnail URL.
     * @param int $quality [optional] <br> <b>QUALITY_HIGH</b> High quality size 480 * 360 <br> <b>QUALITY_MEDIUM</b> Medium quality size 320 * 180 <br> <b>QUALITY_DEFAULT</b> Default quality size 120 * 90 <b>(default value)</b>. <br> <p></p>
     * @return string <br> channel thumbnail URL.
     */
    function getChannelThumbnail($quality = self::QUALITY_MEDIUM) {
        $thumbnail = $this->channelInfo['snippet']['thumbnails'];

        if ($quality === self::QUALITY_HIGH) {// 480 * 360
            $thumbnail = $thumbnail['high']['url'];
        } elseif ($quality === self::QUALITY_MEDIUM) {// 320 * 180
            $thumbnail = $thumbnail['medium']['url'];
        } elseif ($quality === self::QUALITY_DEFAULT) {// 120 * 90
            $thumbnail = $thumbnail['default']['url'];
        }
        return $thumbnail;
    }

    /* -*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*- */

    /**
     * Get YouTube video playlist information in array and can use random result.
     * @param string $playlistId <br> The YouTube playlist ID. <p></p>
     * @param int $maxVideoResult <br> The the maximum number of videos get from this channel. <br> <b>1</b> is minimum number and <b>50</b> is maximum number <p></p>
     * @param string $order <br> The order type of video in list. <br> <b>date</b> Order by date newest is first <b>this is default value</b>. <br> <b>title</b> Order by title characters. <b>this is default value</b>. <br> <b>random</b> Order randomly.  <p></p>
     * @param int $quality [optional] <br> <b>QUALITY_STANDARD</b> Standard quality size 640 * 480 <br> <b>QUALITY_HIGH</b> High quality size 480 * 360 <br> <b>QUALITY_MEDIUM</b> Medium quality size 320 * 180 <br> <b>QUALITY_DEFAULT</b> Default quality size 120 * 90 <b>(default value)</b>. <br> <p></p>
     * @return array <br> YouTube channel video information array like <br> [['key', 'title', 'description', 'publishedAt', 'duration', 'time', 'view', 'like', 'dislike', 'thumbnail', 'channelId', 'channelTitle'], ...].
     */
    public function getPlaylistVideoData($playlistId = '', $maxVideoResult = 5, $order = self::ORDER_BY_LIST, $quality = self::QUALITY_DEFAULT) {
        $video = $this->loadPlaylist($playlistId, $maxVideoResult, $order);
        $this->playlistPageNumber = 1;

        $result = [];
        for ($i = 0; $i < count($video); $i++) {
            $videoKey = $video[$i]['snippet']['resourceId']['videoId'];
            $result[] = $this->getVideoData($videoKey, $quality);
        }

        return $result;
    }

    /**
     * Get YouTube video channel information of spastic page in array.
     * @param string $playlistId <br> The YouTube playlist ID. <p></p>
     * @param int $maxVideoResult <br> The the maximum number of videos get from this channel. <br> <b>1</b> is minimum number and <b>50</b> is maximum number <p></p>
     * @param int $pageNumber <br> The page number of request <b>1</b> is default value <p></p>
     * @param string $order <br> The order type of video in list. <br> <b>date</b> Order by date newest is first <b>this is default value</b>. <br> <b>title</b> Order by title characters. <b>this is default value</b>. <br> <b>random</b> Order randomly.  <p></p>
     * @param int $quality [optional] <br> <b>QUALITY_STANDARD</b> Standard quality size 640 * 480 <br> <b>QUALITY_HIGH</b> High quality size 480 * 360 <br> <b>QUALITY_MEDIUM</b> Medium quality size 320 * 180 <br> <b>QUALITY_DEFAULT</b> Default quality size 120 * 90 <b>(default value)</b>. <br> <p></p>
     * @return array <br> YouTube channel video information array like <br> [['key', 'title', 'description', 'publishedAt', 'duration', 'time', 'view', 'like', 'dislike', 'thumbnail', 'channelId', 'channelTitle'], ...].
     */
    public function getPlaylistVideoPagetData($playlistId = '', $maxVideoResult = 5, $pageNumber = 1, $order = self::ORDER_BY_DATE, $quality = self::QUALITY_DEFAULT) {
        if ($order === self::ORDER_BY_RANDOM) {
            $order = self::ORDER_BY_LIST;
        }
        $this->loadPlaylist($playlistId, $maxVideoResult, $order);
        $this->playlistPageNumber = 1;

        $video = [];
        $result = [];
        for ($i = 2; $i <= count($pageNumber) && trim($this->channelNextPage) !== ''; $i++) {
            $video = $this->getPlaylistVideoNextPageData();
            $this->playlistPageNumber = $i;
        }

        for ($i = 0; $i < count($video); $i++) {
            $videoKey = $video[$i]['snippet']['resourceId']['videoId'];
            $temp = $this->getVideoData($videoKey, $quality);
            if (trim($temp['title']) !== '' && (int) $temp['duration'] !== 0) {
                $result[] = $temp;
            } else {
                unlink($this->savePath . $videoKey . '.json');
            }
        }

        return $result;
    }

    /**
     * Get YouTube video playlist information in array.
     * @param string $playlistId <br> The YouTube playlist ID. <p></p>
     * @param string $order <br> The order type of video in list. <br> <b>date</b> Order by date newest is first <b>this is default value</b>. <br> <b>title</b> Order by title characters. <b>this is default value</b>. <br> <b>random</b> Order randomly.  <p></p>
     * @param int $quality [optional] <br> <b>QUALITY_STANDARD</b> Standard quality size 640 * 480 <br> <b>QUALITY_HIGH</b> High quality size 480 * 360 <br> <b>QUALITY_MEDIUM</b> Medium quality size 320 * 180 <br> <b>QUALITY_DEFAULT</b> Default quality size 120 * 90 <b>(default value)</b>. <br> <p></p>
     * @return array <br> YouTube channel video information array like <br> [['key', 'title', 'description', 'publishedAt', 'duration', 'time', 'view', 'like', 'dislike', 'thumbnail', 'channelId', 'channelTitle'], ...].
     */
    public function getPlaylistAllVideoData($playlistId = '', $order = self::ORDER_BY_LIST, $quality = self::QUALITY_DEFAULT) {
        $this->playlistPageNumber = 0;

        $video = $this->loadPlaylist($playlistId, 50, $order);
        while (trim($this->playlistNextPage) !== '') {
            $temp = $this->getPlaylistVideoNextPageData();
            for ($i = 0; $i < count($temp); $i++) {
                $video[] = $temp[$i];
            }
        }

        if ($order === self::ORDER_BY_RANDOM) {
            $videoCount = count($video);
            $video = $this->getRandomData($video, $videoCount, $videoCount);
            $this->playlistInfo = $video;
        }

        $result = [];
        for ($i = 0; $i < count($video); $i++) {
            $videoKey = $video[$i]['snippet']['resourceId']['videoId'];
            $temp = $this->getVideoData($videoKey, $quality);
            if (trim($temp['title']) !== '' && (int) $temp['duration'] !== 0) {
                $result[] = $temp;
            } else {
                unlink($this->savePath . $videoKey . '.json');
            }
        }

        return $result;
    }

    /**
     * Get YouTube video channel search result next page.
     * @return array <br> YouTube channel video information array like <br> [['key', 'title', 'description', 'publishedAt', 'duration', 'time', 'view', 'like', 'dislike', 'thumbnail', 'channelId', 'channelTitle'], ...] <br> <b>FALSE</b> if this is last page.
     */
    public function getPlaylistVideoNextPageData() {
        if (trim($this->playlistNextPage) === '') {
            return false;
        }

        $this->playlistPageNumber += 1;
        $items = $this->loadPlaylistVideo($this->playlistId, $this->playlistMaxResult, $this->playlistNextPage, $this->playlistOrder);

        return $items;
    }

    /**
     * Get YouTube video channel search result previous page.
     * @return array <br> YouTube channel video information array like <br> [['key', 'title', 'description', 'publishedAt', 'duration', 'time', 'view', 'like', 'dislike', 'thumbnail', 'channelId', 'channelTitle'], ...] <br> <b>FALSE</b> if this is first page.
     */
    public function getPlaylistVideoPreviousPageData() {
        if (trim($this->playlistPreviousPage) === '') {
            return false;
        }

        $this->playlistPageNumber -= 1;
        $items = $this->loadPlaylistVideo($this->playlistId, $this->playlistMaxResult, $this->playlistPreviousPage, $this->playlistOrder);

        return $items;
    }

    /**
     * Get YouTube video information array.
     * @param string $playlistId <br> The YouTube playlist ID. <p></p>
     * @param int $maxVideoResult <br> The the maximum number of videos get from this channel. <br> <b>1</b> is minimum number and <b>50</b> is maximum number <p></p>
     * @param string $order <br> The order type of video in list. <br> <b>date</b> Order by date newest is first <b>this is default value</b>. <br> <b>title</b> Order by title characters. <b>this is default value</b>. <br> <b>random</b> Order randomly.  <p></p>
     * @return array <br> YouTube channel information.
     */
    public function loadPlaylist($playlistId, $maxVideoResult = 5, $order = self::ORDER_BY_LIST) {
        $this->playlistId = $playlistId;
        $this->playlistOrder = $order;
        $this->playlistMaxResult = $maxVideoResult;
        $this->playlistPageNumber = 1;

        $items = $this->loadPlaylistVideo($playlistId, $maxVideoResult, '', $order);

        return $items;
    }

    /**
     * Get YouTube video information array.
     * @param string $playlistId <br> The YouTube playlist ID. <p></p>
     * @param int $maxVideoResult <br> The the maximum number of videos get from this channel. <br> <b>1</b> is minimum number and <b>50</b> is maximum number <p></p>
     * @param string $order <br> The order type of video in list. <br> <b>date</b> Order by date newest is first <b>this is default value</b>. <br> <b>title</b> Order by title characters. <b>this is default value</b>. <br> <b>random</b> Order randomly.  <p></p>
     * @return array <br> YouTube channel information.
     */
    private function loadPlaylistVideo($playlistId, $maxVideoResult = 5, $pageToken = '', $order = self::ORDER_BY_LIST) {
        if ((int) $maxVideoResult < 1) {
            $maxVideoResult = 1;
        } elseif ((int) $maxVideoResult > 50) {
            $maxVideoResult = 50;
        }
        if ($order === self::ORDER_BY_RANDOM) {
            $realMaxResult = 50;
        } else {
            $realMaxResult = (int) $maxVideoResult;
        }

        $data = $this->getSavedFile($playlistId . '_' . $realMaxResult . '_' . $pageToken, $this->videoUpdateTime);
        if ($data) {
            $items = $data['items'];
        } else {
            $arrContextOptions = array(
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ),
            );
            $resultOrder = 'list';
            if ($order !== self::ORDER_BY_RANDOM) {
                $order = self::ORDER_BY_LIST;
            }

            $url = "https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&playlistId=$playlistId&type=list&maxResults=$realMaxResult&order=$resultOrder&key=" . $this->API_key;
            if (trim($pageToken) !== '') {
                $url .= "&pageToken=$pageToken";
            }

            $data = json_decode(file_get_contents($url, FALSE, stream_context_create($arrContextOptions)), true);

            $items = [];
            for ($i = 0; $i < count($data['items']); $i++) {
                if ($data['items'][$i]['snippet']['title'] !== 'Deleted video') {
                    $items[] = $data['items'][$i];
                }
            }

            $this->saveJSON($data, $playlistId . '_' . $realMaxResult . '_' . $pageToken);
        }

        if (isset($data['nextPageToken'])) {
            $this->playlistNextPage = $data['nextPageToken'];
        } else {
            $this->playlistNextPage = '';
        }
        if (isset($data['previousPageToken'])) {
            $this->playlistPreviousPage = $data['previousPageToken'];
        } else {
            $this->playlistPreviousPage = '';
        }

        if ($order === self::ORDER_BY_RANDOM) {
            $videoCount = count($items);
            $new = $this->getRandomData($items, $maxVideoResult, $videoCount);

            $this->playlistInfo = $new;
        } else {
            $this->playlistInfo = $items;
        }

        return $this->playlistInfo;
    }

    /* -*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*- */

    /**
     * Get YouTube video information in JDON array.
     * @param string $videoKey <br> The YouTube video key. <p></p>
     * @param int $quality [optional] <br> <b>QUALITY_STANDARD</b> Standard quality size 640 * 480 <br> <b>QUALITY_HIGH</b> High quality size 480 * 360 <br> <b>QUALITY_MEDIUM</b> Medium quality size 320 * 180 <br> <b>QUALITY_DEFAULT</b> Default quality size 120 * 90 <b>(default value)</b>. <br> <p></p>
     * @return array <br> YouTube video information array like <br> ['key', 'title', 'description', 'publishedAt', 'duration', 'time', 'view', 'like', 'dislike', 'thumbnail', 'channelId', 'channelTitle'].
     */
    public function getVideoData($videoKey = '', $quality = self::QUALITY_DEFAULT) {
        if ($videoKey !== '') {
            $this->loadVideo($videoKey);
        }

        $key = $this->videoKey;
        $title = $this->getVideoTitle();
        $description = $this->getVideoDescription();
        $publishedAt = $this->getVideoPublishedAt();
        $durationSecond = $this->getVideoDurationSecond();
        $durationTime = $this->getVideoDurationTime();
        $view = $this->getVideoViewCount();
        $like = $this->getVideoLikeCount();
        $dislike = $this->getVideoDislikeCount();
        $thumbnail = $this->getVideoThumbnail($quality);
        $channelId = $this->getVideoChannelId();
        $channelTitle = $this->getVideoChannelTitle();

        $data = [
            'key' => $key,
            'title' => $title,
            'description' => $description,
            'publishedAt' => $publishedAt,
            'duration' => $durationSecond,
            'time' => $durationTime,
            'view' => $view,
            'like' => $like,
            'dislike' => $dislike,
            'thumbnail' => $thumbnail,
            'channelId' => $channelId,
            'channelTitle' => $channelTitle
        ];

        return $data;
    }

    /**
     * Get YouTube video information in JDON array.
     * @param string $videoKey <br> The YouTube video key. <p></p>
     * @return array <br> YouTube video information.
     */
    public function loadVideo($videoKey) {
        $temp = $this->getSavedFile($videoKey, $this->videoUpdateTime);
        if ($temp) {//            echo "***** Yes ***** $videoKey <br><br>";
            $this->videoInfo = $temp;
        } else {//echo "***** No ***** $videoKey <br><br>";
            $arrContextOptions = array(
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ),
            );
            $url = "https://www.googleapis.com/youtube/v3/videos?part=snippet%2CcontentDetails%2Cstatistics&id=$videoKey&key=$this->API_key";
            $videoData = file_get_contents($url, FALSE, stream_context_create($arrContextOptions));
            $this->videoInfo = json_decode($videoData, true);

            $this->saveJSON($this->videoInfo, $videoKey);
        }

        $this->videoKey = $videoKey;

        return $this->videoInfo;
    }

    /**
     * Get YouTube video duration in second.
     * @return int <br> YouTube video duration in second.
     */
    public function getVideoDurationSecond() {
        $videoDuration = '';
        foreach ($this->videoInfo['items'] as $vidTime) {
            $videoDuration = $vidTime['contentDetails']['duration'];
        }

        if ($videoDuration === '') {
            return '0';
        }
        $videoDuration = substr($videoDuration, 2, strlen($videoDuration) - 2);

        $Hpostion = strpos($videoDuration, 'H');
        $Mpostion = strpos($videoDuration, 'M');
        $hour = (int) substr($videoDuration, 0, $Hpostion);
        if ($Mpostion) {
            if ($Hpostion > 0) {
                $minute = (int) substr($videoDuration, $Hpostion + 1, $Mpostion);
            } else {
                $minute = (int) substr($videoDuration, 0, $Mpostion);
            }
            $minute += ($hour * 60);
            $second = (int) substr($videoDuration, $Mpostion + 1);
        } else {
            $minute = (int) substr($videoDuration, $Hpostion + 1);
            $minute += ($hour * 60);
            $second = 0;
        }

        $time = ($minute * 60) + $second;
        return $time;
    }

    /**
     * Get YouTube video duration time format H:M:S.
     * @return string <br> video duration time format H:M:S.
     */
    public function getVideoDurationTime() {
        $videoDuration = '';
        foreach ($this->videoInfo['items'] as $vidTime) {
            $videoDuration = $vidTime['contentDetails']['duration'];
        }

        if ($videoDuration === '') {
            return '0';
        }
        $videoDuration = substr($videoDuration, 2, strlen($videoDuration) - 2);

        $Hpostion = strpos($videoDuration, 'H');
        $Mpostion = strpos($videoDuration, 'M');
        $hour = (int) substr($videoDuration, 0, $Hpostion);
        if ($Mpostion) {
            if ($Hpostion > 0) {
                $minute = (int) substr($videoDuration, $Hpostion + 1, $Mpostion);
            } else {
                $minute = (int) substr($videoDuration, 0, $Mpostion);
            }
            $minute += ($hour * 60);
            $second = (int) substr($videoDuration, $Mpostion + 1);
            if ($second <= 9) {
                $second = '0' + (string) $second;
            }
        } else {
            $minute = (int) substr($videoDuration, $Hpostion + 1);
            $minute += ($hour * 60);
            $second = 0;
        }
        $time = (string) $minute . ':' . (string) $second;
        return $time;
    }

    /**
     * Get YouTube video title.
     * @return string <br> video title.
     */
    public function getVideoTitle() {
        $vidoTitle = '';
        foreach ($this->videoInfo['items'] as $title) {
            $vidoTitle = $title['snippet']['title'];
        }

        return $vidoTitle;
    }

    /**
     * Get YouTube video description.
     * @return string <br> video description.
     */
    public function getVideoDescription() {
        $description = '';
        foreach ($this->videoInfo['items'] as $descrio) {
            $description = $descrio['snippet']['description'];
        }

        return $description;
    }

    /**
     * Get YouTube video published date time.
     * @return string <br> video published date time.
     */
    public function getVideoPublishedAt() {
        $publishedAt = '';
        foreach ($this->videoInfo['items'] as $publish) {
            $publishedAt = $publish['snippet']['publishedAt'];
        }

        return $publishedAt;
    }

    /**
     * Get YouTube video view number.
     * @return int <br> video view number.
     */
    public function getVideoViewCount() {
        $viewCount = 0;
        foreach ($this->videoInfo['items'] as $view) {
            $viewCount = $view['statistics']['viewCount'];
        }

        return $viewCount;
    }

    /**
     * Get YouTube video liked number.
     * @return int <br> video liked number.
     */
    public function getVideoLikeCount() {
        $likeCount = 0;
        foreach ($this->videoInfo['items'] as $like) {
            $likeCount = $like['statistics']['likeCount'];
        }

        return $likeCount;
    }

    /**
     * Get YouTube video disliked number.
     * @return int <br> video disliked number.
     */
    public function getVideoDislikeCount() {
        $dislikeCount = 0;
        foreach ($this->videoInfo['items'] as $dislike) {
            $dislikeCount = $dislike['statistics']['dislikeCount'];
        }

        return $dislikeCount;
    }

    /**
     * Get YouTube video thumbnail URL.
     * @param int $quality [optional] <br> <b>QUALITY_STANDARD</b> Standard quality size 640 * 480 <br> <b>QUALITY_HIGH</b> High quality size 480 * 360 <br> <b>QUALITY_MEDIUM</b> Medium quality size 320 * 180 <br> <b>QUALITY_DEFAULT</b> Default quality size 120 * 90 <b>(default value)</b>. <br> <p></p>
     * @return string <br> video thumbnail URL.
     */
    function getVideoThumbnail($quality = self::QUALITY_DEFAULT) {
        $thumbnail = '';
        foreach ($this->videoInfo['items'] as $vidThumb) {
            $thumbnail = $vidThumb['snippet']['thumbnails'];
        }

        if ($thumbnail === '') {
            return '';
        }

        if ($quality === self::QUALITY_STANDARD) {// 640 * 480
            $thumbnail = $thumbnail['standard']['url'];
        } elseif ($quality === self::QUALITY_HIGH) {// 480 * 360
            $thumbnail = $thumbnail['high']['url'];
        } elseif ($quality === self::QUALITY_MEDIUM) {// 320 * 180
            $thumbnail = $thumbnail['medium']['url'];
        } elseif ($quality === self::QUALITY_DEFAULT) {// 120 * 90
            $thumbnail = $thumbnail['default']['url'];
        }
        return $thumbnail;
    }

    /**
     * Get YouTube video channel ID of owner this video.
     * @return string <br> channel ID.
     */
    public function getVideoChannelId() {
        $channelId = '';
        foreach ($this->videoInfo['items'] as $title) {
            $channelId = $title['snippet']['channelId'];
        }

        return $channelId;
    }

    /**
     * Get YouTube channel title of owner this video.
     * @return string <br> channel title.
     */
    public function getVideoChannelTitle() {
        $channelTitle = '';
        foreach ($this->videoInfo['items'] as $title) {
            $channelTitle = $title['snippet']['channelTitle'];
        }

        return $channelTitle;
    }

    /* -*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*- */

    /**
     * Get the saved file if exist and check its date then ceck the file in the time range or not if in range return the JSON array.
     * @param string $fileId <br> The YouTube item ID (video key, channel ID or playlist ID). <p></p>
     * @param int $updateTime <br> The maximum number of hours can the file is valid for use <br> <b>0</b> mean the file not expire. <p></p>
     * @return array <br> IF found file and in the time range.
     * @return boolen <br> <b>FALSE</b> if don't find file or file out of time.
     */
    private function getSavedFile($fileId, $updateTime) {
        $dt = new DateTime();
        if ((int) $updateTime > 0) {
            $time = ((int) $dt->getTimestamp()) - (60 * 60 * $updateTime);
        } else {
            $time = 0;
        }
        $file = $this->savePath . $fileId . '.json';

        if (is_file($file) && (int) filemtime($file) >= $time) {
            $data = json_decode(file_get_contents($file), true);

            return $data;
        } else {
            return false;
        }
    }

    /**
     * Save the array in JDON file.
     * @param array $array <br> The array want save it. <p></p>
     * @param string $fileId <br> The YouTube item ID (video key, channel ID or playlist ID). <p></p>
     * @return array <br> IF found file and in the time range.
     * @return null.
     */
    private function saveJSON($array, $fileId) {
        $file = $this->savePath . $fileId . '.json';
        $string = json_encode($array);
        $unescaped = preg_replace_callback('/\\\\u(\w{4})/', function ($matches) {
            return html_entity_decode('&#x' . $matches[1] . ';', ENT_COMPAT, 'UTF-8');
        }, $string);
        file_put_contents($file, $unescaped);
    }

    /**
     * Chose the random items from array and return it.
     * @param string $fileId <br> The YouTube item ID (video key, channel ID or playlist ID). <p></p>
     * @param int $updateTime <br> The maximum number of hours can the file is valid for use <br> <b>0</b> mean the file not expire. <p></p>
     * @return array <br> random items from original array.
     */
    private function getRandomData($video, $maxVideoResult, $videoCount) {
        if ($maxVideoResult === 0 || $maxVideoResult > $videoCount) {
            $maxVideoResult = $videoCount;
        }

        $result = [];
        do {
            $id = mt_rand(0, $videoCount - 1);
            if (!in_array($id, $result)) {
                $result[] = $id;
            }
        } while (count($result) < $maxVideoResult);

        $items = [];
        for ($i = 0; $i < count($result); $i++) {
            if (isset($video[(int) $result[$i]])) {
                $items[] = $video[(int) $result[$i]];
            }
        }

        return $items;
    }

}
