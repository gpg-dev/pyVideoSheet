<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Video;
use App\Models\Category;
use App\Models\VideoCategory;
use App\Models\SourceSite;
use Illuminate\Http\Request;
use App\Services\UploadHandler;
use App\Services\Setting;
use App\Models\Tag;
use App\Models\VideoTag;
use Illuminate\Support\Facades\DB;

class VideoController extends Controller {

    protected function template($view) {
        return 'backend.video.' . $view;
    }

    public function index() {
        $categories = Category::where('isDeleted', '0')->get();
        $videos = Video::where(Video::TABLE . '.id', '!=', 0)->select(Video::TABLE . '.*');
        $searchParams = [
            'title' => '',
            'category' => ''
        ];

        if (isset($_GET['category']) && $_GET['category']) {
            $videos = $videos->join(VideoCategory::TABLE . ' AS vt', Video::TABLE . '.id', '=', 'vt.videoId')
                    ->where('catId', $_GET['category']);
            $searchParams['category'] = $_GET['category'];
        }
        if (isset($_GET['title']) && $_GET['title']) {
            $videos = $videos->where('title', 'like', '%' . $_GET['title'] . '%');
            $searchParams['title'] = $_GET['title'];
        }

        $videos = $videos->orderBy(Video::TABLE . '.id', 'created_at')->paginate(20);

        return View($this->template('index'))
                        ->with('videos', $videos)->with('searchParams', $searchParams)->with('categories', $categories);
    }

    public function getImport() {
        $sourceSites = SourceSite::whereIn('formatCSVFrom', array('tubecorporate.com', 'xvideos.com', 'xhamster.com', 'spankwire'))
                ->where('isDeleted', '0')
                ->orderBy('title', 'ASC')->get();

        return View($this->template('import'))->with('sourceSites', $sourceSites);
    }

    public function postImport(Request $req) {
        if (isset($req->sourcesite) && $req->sourcesite) {
            $sourceSite = SourceSite::find($req->sourcesite);
            if (!empty($req->url_source)) {
                $content_page = Video::get_remote_data($req->url_source);
                if ($content_page) {
                    switch ($sourceSite->formatCSVFrom) {
                        case 'tubecorporate.com':
                            $data = explode("\n", $content_page);
                            foreach ($data as $item) {
                                $result = explode("|", $item);
                                if (count($result) > 12) {
                                    $this->insertVideoFromURLTube($result, $sourceSite->id);
                                }
                            }
                            break;
                        case 'xvideos.com':
                            $data = new \SimpleXMLElement($content_page);
                            foreach ($data->channel->item as $item) {
                                $this->insertVideoFromXVideo($item, $sourceSite->id);
                            }
                            break;
                        case 'xhamster.com':
                            $this->importVideoFromURLXHamter($content_page, $sourceSite->id);
                            break;
                        case 'spankwire':
                            $this->importVideoSpankwire($content_page, $sourceSite->id);
                            break;
                    }
                }
            }
        } else {
            return back()->with('error', 'Please Enter Source Site.');
        }
        return back()->with('success', 'Import Video is Successfull.');
    }

    private function importVideoTubeCor($csvFile, $sourceId) {
        while (!feof($csvFile)) {
            $result = fgetcsv($csvFile, 0, '|');
            if ($i != 0 && $result) {
                $video = array();
                $video['sourceSiteId'] = $sourceId;
                $video['videoId'] = $result[0];
                $video['url'] = $result[1];
                $video['title'] = substr($result[2], 0, 200);
                $video['slug'] = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $video['title']));
                $video['description'] = $result[3];
                $video['image'] = $result[4];
                $video['duration'] = $result[6];
                $video['createdAt'] = date('Y-m-d', strtotime($result[7]));
                $video['updatedAt'] = date('Y-m-d', strtotime($result[7]));
                $validator = Validator::make($video, Video::$ruleCreate);
                if ($validator->passes()) {
                    $videoModel = new Video($video);
                    $videoModel->save();

                    $catTitles = explode(',', $result[8]);
                    foreach ($catTitles as $catTitle) {
                        if ($catTitle) {
                            $this->insertVideoCategory($catTitle, $videoModel->id);
                        }
                    }

                    $tagTitles = explode(',', $result[9]);
                    foreach ($tagTitles as $tagTitle) {
                        if ($tagTitle) {
                            $this->insertVideoTag($tagTitle, $videoModel->id);
                        }
                    }
                }
                unset($video);
            }
            $i++;
            unset($result);
        }
    }

    private function importVideoXVideo($csvFile, $sourceId) {
        while (!feof($csvFile)) {
            $result = fgetcsv($csvFile, 0, ';');
            if (count($result) > 7) {
                $video = array();
                $video['sourceSiteId'] = $sourceId;
                $video['videoId'] = $result[6];
                $video['url'] = $result[0];
                $video['title'] = substr($result[1], 0, 200);
                $video['slug'] = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $video['title']));
                $video['description'] = null;
                $video['image'] = $result[3];
                $duration = explode(' ', $result[2]);
                $video['duration'] = ($duration[0]) ? $duration[0] : null;
                $validator = Validator::make($video, Video::$ruleCreate);
                if ($validator->passes()) {
                    $videoModel = new Video($video);
                    $videoModel->save();

                    $catTitles = explode(',', $result[7]);
                    foreach ($catTitles as $catTitle) {
                        if ($catTitle) {
                            $this->insertVideoCategory($catTitle, $videoModel->id);
                        }
                    }

                    $tagTitles = explode(',', $result[5]);
                    foreach ($tagTitles as $tagTitle) {
                        if ($tagTitle) {
                            $this->insertVideoTag($tagTitle, $videoModel->id);
                        }
                    }
                }
                unset($video);
            }
            unset($result);
        }
    }

    private function importVideoXHamter($csvFile, $sourceId) {
        $i = 0;
        while (!feof($csvFile)) {
            $result = fgetcsv($csvFile, 0, '|');
            if ($i != 0 && $result) {
                $video = array();
                $video['sourceSiteId'] = $sourceId;
                $video['videoId'] = $result[0];
                $video['url'] = $result[1];
                $video['title'] = substr($result[3], 0, 200);
                $video['slug'] = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $video['title']));
                $video['description'] = null;
//                $images = explode(';', $result[6]);
//                $video['image'] = (count($images)) ? $images[0] : null;
                $minute = explode('m', $result[7]);
                if (isset($minute[1])) {
                    $second = explode('s', $minute[1]);
                    $video['duration'] = $minute[0] * 60 + $second[0] * 1;
                } else {
                    $video['duration'] = null;
                }
                $video['createdAt'] = date('Y-m-d', strtotime($result[8]));
                $video['updatedAt'] = date('Y-m-d', strtotime($result[8]));
                $validator = Validator::make($video, Video::$ruleCreate);
                if ($validator->passes()) {
                    $videoModel = new Video($video);
                    $videoModel->save();

                    if ($items[4]) {
                        $catTitles = explode(';', $items[4]);
                        foreach ($catTitles as $catTitle) {
                            if ($catTitle) {
                                $this->insertVideoCategory($catTitle, $videoModel->id);
                            }
                        }
                    } else {
                        $videoCatModel = new VideoCategory();
                        $videoCatModel->videoId = $videoModel->id;
                        $videoCatModel->catId = Setting::getOtherCateId();
                        $videoCatModel->save();
                    }
                }
                unset($video);
            }
            $i++;
            unset($result);
        }
    }

    private function importVideoFromURLXHamter($content_page, $sourceId) {
        $videos = explode("\n", $content_page);
        $map = [];

        foreach ($videos as $index => $videoParse) {
            $items = explode("|", $videoParse);

            if(count($items) > 1) {
                if($index == 0) {
                    $map = array_flip($items);
                } else {
                    $video['videoId'] = $items[$map['#ID']];
                    $video['image'] = $items[$map['#THUMB']];
                    $video['sourceSiteId'] = $sourceId;
                    $video['url'] = $items[$map['#URL']];
                    $video['title'] = $items[$map['#TITLE']];
                    $video['slug'] = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $video['title']));
                    $video['description'] = '';

                    $video['duration'] = null;
                    $minute = explode('m', $items[$map['#DURATION']]);
                    if (isset($minute[1])) {
                        $second = explode('s', $minute[1]);
                        $video['duration'] = (int)$minute[0] * 60 + (int)$second[0];
                    }

                    $validator = Validator::make($video, Video::$ruleCreate);

                    if ($validator->passes()) {
                        $check = Video::where('url', $video['url'])->count();
                        if($check == 0) {
                            $videoModel = new Video($video);
                            $videoModel->save();

                            if ($items[$map['#CHANNEL']]) {
                                $catTitles = explode(';', $items[$map['#CHANNEL']]);
                                foreach ($catTitles as $catTitle) {
                                    if ($catTitle) {
                                        $this->insertVideoCategory($catTitle, $videoModel->id);
                                    }
                                }
                            } else {
                                $videoCatModel = new VideoCategory();
                                $videoCatModel->videoId = $videoModel->id;
                                $videoCatModel->catId = Setting::getOtherCateId();
                                $videoCatModel->save();
                            }
                        }
                    }
                }
            }
        }
    }

    private function importVideoPornsharing($csvFile, $sourceId) {
        while (!feof($csvFile)) {
            $result = fgetcsv($csvFile, 0, '|');
            if (count($result) > 8) {
                $video = array();
                $video['sourceSiteId'] = $sourceId;
                $video['videoId'] = $result[0];
                $video['url'] = $result[1];
                $video['title'] = substr($result[3], 0, 200);
                $video['slug'] = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $video['title']));
                $video['description'] = null;
                $video['image'] = $result[8];
                $video['duration'] = $result[2];
                $validator = Validator::make($video, Video::$ruleCreate);
                if ($validator->passes()) {
                    $videoModel = new Video($video);
                    $videoModel->save();

                    if ($result[5]) {
                        $catTitles = explode(',', $result[5]);
                        foreach ($catTitles as $catTitle) {
                            if ($catTitle) {
                                $this->insertVideoCategory($catTitle, $videoModel->id);
                            }
                        }
                    } else {
                        $videoCatModel = new VideoCategory();
                        $videoCatModel->videoId = $videoModel->id;
                        $videoCatModel->catId = Setting::getOtherCateId();
                        $videoCatModel->save();
                    }
                }
                unset($video);
            }
            unset($result);
        }
    }

    private function importVideoPornHub($csvFile, $sourceId) {
        while (!feof($csvFile)) {
            $result = fgetcsv($csvFile, 0, '|');
            if (count($result) > 9) {
                $video = array();
                $video['sourceSiteId'] = $sourceId;
                $video['videoId'] = null;
                $video['url'] = $result[1];
                $video['title'] = substr($result[5], 0, 200);
                $video['slug'] = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $video['title']));
                $video['description'] = null;
                $video['image'] = $result[9];
                $video['duration'] = $result[7];
                $validator = Validator::make($video, Video::$ruleCreate);
                if ($validator->passes()) {
                    $videoModel = new Video($video);
                    $videoModel->save();

                    $catTitles = explode(';', $result[2]);
                    foreach ($catTitles as $catTitle) {
                        if ($catTitle) {
                            $this->insertVideoCategory($catTitle, $videoModel->id);
                        }
                    }

                    $tagTitles = explode(';', $result[6]);
                    foreach ($tagTitles as $tagTitle) {
                        if ($tagTitle) {
                            $this->insertVideoTag($tagTitle, $videoModel->id);
                        }
                    }
                }
                unset($video);
            }
            unset($result);
        }
    }

    private function importVideoRedTube($csvFile, $sourceId) {
        while (!feof($csvFile)) {
            $result = fgetcsv($csvFile, 0, '|');
            if (count($result) > 7) {
                $video = array();
                $video['sourceSiteId'] = $sourceId;
                $video['videoId'] = $result[0];
                $video['url'] = $result[2];
                $video['title'] = substr($result[3], 0, 200);
                $video['slug'] = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $video['title']));
                $video['description'] = null;
                $video['image'] = $result[1];
                $minute = explode('m', $result[7]);
                if (isset($minute[1])) {
                    $second = explode('s', $minute[1]);
                    $video['duration'] = $minute[0] * 60 + $second[0] * 1;
                } else {
                    $video['duration'] = null;
                }
                $validator = Validator::make($video, Video::$ruleCreate);
                if ($validator->passes()) {
                    $videoModel = new Video($video);
                    $videoModel->save();

                    $catTitles = explode(';', $result[4]);
                    foreach ($catTitles as $catTitle) {
                        if ($catTitle) {
                            $this->insertVideoCategory($catTitle, $videoModel->id);
                        }
                    }

                    $tagTitles = explode(';', $result[5]);
                    foreach ($tagTitles as $tagTitle) {
                        if ($tagTitle) {
                            $this->insertVideoTag($tagTitle, $videoModel->id);
                        }
                    }
                }
                unset($video);
            }
            unset($result);
        }
    }

    private function importVideoXTube($csvFile, $sourceId) {
        while (!feof($csvFile)) {
            $result = fgetcsv($csvFile, 0, '|');
            if (count($result) > 8) {
                $video = array();
                $video['sourceSiteId'] = $sourceId;
                $video['videoId'] = null;
                $video['url'] = $result[1];
                $video['title'] = substr($result[5], 0, 200);
                $video['slug'] = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $video['title']));
                $video['description'] = null;
                $video['image'] = $result[8];
                $video['duration'] = $result[7];
                $validator = Validator::make($video, Video::$ruleCreate);
                if ($validator->passes()) {
                    $videoModel = new Video($video);
                    $videoModel->save();

                    $catTitles = explode(';', $result[2]);
                    foreach ($catTitles as $catTitle) {
                        if ($catTitle) {
                            $this->insertVideoCategory($catTitle, $videoModel->id);
                        }
                    }

                    $tagTitles = explode(';', $result[6]);
                    foreach ($tagTitles as $tagTitle) {
                        if ($tagTitle) {
                            $this->insertVideoTag($tagTitle, $videoModel->id);
                        }
                    }
                }
                unset($video);
            }
            unset($result);
        }
    }

    private function importVideoSpankwire($contentFile, $sourceId) {
        $arr_video = json_decode($contentFile);
        if(isset($arr_video->videos)) {

            foreach ($arr_video->videos as $result) {
                $video = array();
                $video['sourceSiteId'] = $sourceId;
                $video['videoId'] = $result->video->id;
                $video['url'] = $result->video->url;
                $video['title'] = substr($result->video->title, 0, 200);
                $video['slug'] = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $video['title']));
                $video['description'] = null;
                $video['image'] = $result->video->thumb;
                $time = explode(':', $result->video->duration);
                if (count($time) > 1) {
                    $video['duration'] = (int) $time[0] * 3600 + (int) $time[1] * 60 + (int) $time[2];
                } else {
                    $video['duration'] = 0;
                }

                $validator = Validator::make($video, Video::$ruleCreate);
                if ($validator->passes()) {
                    $videoModel = new Video($video);
                    $videoModel->save();

                    $videoCatModel = new VideoCategory();
                    $videoCatModel->videoId = $videoModel->id;
                    $videoCatModel->catId = Setting::getOtherCateId();
                    $videoCatModel->save();

                    foreach ($result->video->tags as $tag) {
                        if ($tag) {
                            $this->insertVideoTag($tag, $videoModel->id);
                        }
                    }
                }
                unset($video);
                unset($videoModel);
            }
        }
    }

    private function insertVideoFromXVideo($item, $source_id) {
        $prefix = 'http://search.yahoo.com/mrss/';
        $imElements = $item->children($prefix);
        $video = array();
        $video['sourceSiteId'] = $source_id;
        $video['videoId'] = $item->guid;
        $video['url'] = $item->clip_url;
        $video['title'] = $item->title;
        $video['slug'] = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $video['title']));
        $video['description'] = $item->description;
        $video['image'] = $item->thumb_medium;
        $video['duration'] = $item->clips->duration_secs;
        $video['createdAt'] = date('Y-m-d');
        $video['updatedAt'] = date('Y-m-d');

        $videoModel = Video::where([['videoId', '', $video['videoId']],
                        ['sourceSiteId', '', $video['sourceSiteId']]])->first();
        if (!$videoModel) {
            $videoModel = new Video($video);
            $videoModel->save();

            $catTitles = explode(',', $imElements->rating);
            foreach ($catTitles as $catTitle) {
                if ($catTitle) {
                    $this->insertVideoCategory($catTitle, $videoModel->id);
                }
            }

            $tagTitles = explode(',', $imElements->keywords);
            foreach ($tagTitles as $tagTitle) {
                if ($tagTitle) {
                    $this->insertVideoTag($tagTitle, $videoModel->id);
                }
            }
        }
    }

    private function insertVideoFromURLTube($data = array(), $source_id) {
        $video = array();
        $video['sourceSiteId'] = $source_id;
        $video['videoId'] = $data[0];
        $video['url'] = $data[7];
        $video['title'] = preg_replace('/[^a-zA-Z0-9]+/', '', $data[1]);
        $video['slug'] = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $video['title']));
        $video['description'] = $data[9];
        $video['image'] = $data[12];
        $video['duration'] = $data[10];
        $video['createdAt'] = date('Y-m-d', strtotime($data[4]));
        $video['updatedAt'] = date('Y-m-d', strtotime($data[4]));

        $videoModel = Video::where([['videoId', '', $video['videoId']],
                        ['sourceSiteId', '', $video['sourceSiteId']]])->first();
        if (!$videoModel) {
            $videoModel = new Video($video);
            $videoModel->save();

            $catTitles = explode(',', $data[8]);
            foreach ($catTitles as $catTitle) {
                if ($catTitle) {
                    $this->insertVideoCategory($catTitle, $videoModel->id);
                }
            }

            $tagTitles = explode(',', $data[9]);
            foreach ($tagTitles as $tagTitle) {
                if ($tagTitle) {
                    $this->insertVideoTag($tagTitle, $videoModel->id);
                }
            }
        }
    }

    private function insertVideoCategory($catTitle, $videoId) {
        if (trim($catTitle)) {
            $cat = Category::where('title', trim($catTitle))->first();
            if (!$cat) {
                $cat = new Category;
                $cat->title = trim($catTitle);
                $cat->slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $cat->title));
                $cat->save();
            }
            $videoCatModel = new VideoCategory();
            $videoCatModel->videoId = $videoId;
            $videoCatModel->catId = $cat->id;
            $videoCatModel->save();
        }
    }

    private function insertVideoTag($tagTitle, $videoId) {
        if (trim($tagTitle)) {
            $tag = Tag::where('title', trim($tagTitle))->first();
            if (!$tag) {
                $tag = new Tag;
                $tag->title = trim($tagTitle);
                $tag->save();
            }
            $videoTagModel = new VideoTag();
            $videoTagModel->videoId = $videoId;
            $videoTagModel->tagId = $tag->id;
            $videoTagModel->save();
        }
    }

    public function getImportCSV() {
        $sourceSites = SourceSite::where('isDeleted', '0')->orderBy('title', 'ASC')->get();

        return View($this->template('importcsv'))->with('sourceSites', $sourceSites);
    }

    public function postItemsCSV(Request $req) {
        $all_lines = json_decode($req->all_items);
        $special_char = array("\"", "'");
        for($i = 0; $i < count($all_lines); $i++){
            $line = explode($req->txt_delimiter, $all_lines[$i]);
            $video = array();
            $video['sourceSiteId'] = $req->sourcesite;
            if(isset($req->videoId) && !empty($req->videoId)){
                $video['videoId'] = str_replace($special_char, "", $line[(int)$req->videoId - 1]);
            }
            if(isset($req->url) && !empty($req->url)){
                $video['url'] = str_replace($special_char, "", $line[(int)$req->url - 1]);
            }
            if(isset($req->title) && !empty($req->title)){
                $video['title'] = str_replace($special_char, "", $line[(int)$req->title - 1]);
                $video['slug'] = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $video['title']));
            }
            if(isset($req->description) && !empty($req->description)){
                $video['description'] = str_replace($special_char, "", $line[(int)$req->description - 1]);
            }
            if(isset($req->image) && !empty($req->image)){
                $video['image'] = str_replace($special_char, "", $line[(int)$req->image - 1]);
            }
            if(isset($req->duration) && !empty($req->duration)){
                $str_duration = str_replace($special_char, "", $line[(int)$req->duration - 1]);

                $time = str_replace("h",":", $str_duration);
                $time = str_replace("m",":", $time);
                $time = str_replace("s",":", $time);

                if(strpos($str_duration, ':') !== false){
                    $timeInSeconds = strtotime($time) - strtotime('TODAY');
                    $video['duration'] = $timeInSeconds;
                }else if(is_numeric($req->duration)){
                    $video['duration'] = $str_duration;
                }else{
                    $video['duration'] = 0;
                }
            }
            $validator = Validator::make($video, Video::$ruleCreate);
            if ($validator->passes()) {
                $videoModel = new Video($video);
                $videoModel->save();

                if(isset($req->categories) && !empty($req->categories)){
                    $d_cate = $req->delimiter_cate;
                    if(empty($d_cate)){
                        $d_cate = ";";
                    }
                    $catTitles = explode($d_cate, str_replace($special_char, "", $line[(int)$req->categories - 1]));
                    foreach ($catTitles as $catTitle) {
                        if ($catTitle) {
                            $this->insertVideoCategory($catTitle, $videoModel->id);
                        }
                    }
                }

                if(isset($req->tags) && !empty($req->tags)){
                    $d_tag = $req->delimiter_tag;
                    if(empty($d_tag)){
                        $d_tag = ";";
                    }
                    $tagTitles = explode($d_tag, str_replace($special_char, "", $line[(int)$req->tags - 1]));
                    foreach ($tagTitles as $tagTitle) {
                        if ($tagTitle) {
                            $this->insertVideoTag($tagTitle, $videoModel->id);
                        }
                    }
                }
            }
            unset($video);
            unset($line);
        }

        return response()->json(['isSuccess' => true]);
    }

    public function postUploadCSV() {
        if (isset($_FILES['files']) && isset($_FILES['files']['name'][0]) && $_FILES['files']['name'][0]) {
            $statusUpload = UploadHandler::uploadFile($_FILES['files'], 'imported-files');

            if ($statusUpload['isSuccess']) {
                return response()->json($statusUpload);
            }
        }
        return response()->json(['isSuccess' => false]);
    }

    public function getDelete($id) {
        $model = Video::find($id);
        if (!$model) {
            return redirect('404');
        }
        $model->delete();
        return back();
    }

    public function postDelete(Request $req) {
        if(isset($req->chk_ids) && !empty($req->chk_ids)) {
            $delete_video = DB::delete("DELETE FROM `videos` WHERE id IN (" . implode(',', $req->chk_ids) . ")");
            $delete_video = DB::delete("DELETE FROM `videos_categories` WHERE videoId IN (" . implode(',', $req->chk_ids) . ")");
            $delete_video = DB::delete("DELETE FROM `videos_tags` WHERE videoId IN (" . implode(',', $req->chk_ids) . ")");
            return back()->with('success', 'Delete all selected product is SUCCESSFUL.');
        }
        return back()->with('error', 'Please choose videos that you want to delete');
    }

    public function getUpdate($id) {
        $model = Video::find($id);
        $categories = Category::all();
        if (!$model) {
            return redirect('404');
        }
        $videoCategories = VideoCategory::where('videoId', $id)->get();
        $selectedCat = array();
        foreach ($videoCategories as $vc) {
            $selectedCat[] = $vc->catId;
        }

        $tags = Tag::all();
        $videoTags = VideoTag::where('videoId', $id)->get();
        $selectedTags = array();
        foreach ($videoTags as $vt) {
            $selectedTags[] = $vt->tagId;
        }

        return View($this->template('form'))->with('model', $model)
                        ->with('categories', $categories)
                        ->with('selectedCat', $selectedCat)
                        ->with('tags', $tags)
                        ->with('selectedTags', $selectedTags);
    }

    public function postUpdate($id, Request $req) {
        $validator = Validator::make($req->all(), Video::$rule);
        if ($validator->passes()) {
            $video = Video::find($id);
            if ($video) {
                $video->fill($req->all());
                if (isset($_FILES['file']) && $_FILES['file']['name']) {
                    $statusUpload = UploadHandler::uploadImage($_FILES['file'], 'images');
                    if ($statusUpload['isSuccess']) {
                        $video->image = $statusUpload['data'];
                    } else {
                        $validation->errors()->add('file', $statusUpload['data']);
                        return back()->withErrors($validator);
                    }
                }

                if ($req->hour !== '' && $req->minute !== '' && $req->second !== '') {
                    $video->duration = $req->hour * 60 * 60 + $req->minute * 60 + $req->second;
                }
                $video->slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $video->title));
                if ($video->save()) {
                    VideoCategory::where('videoId', $video->id)->delete();
                    if ($req->category) {
                        foreach ($req->category as $catId) {
                            $videoCatModel = new VideoCategory();
                            $videoCatModel->videoId = $video->id;
                            $videoCatModel->catId = $catId;
                            $videoCatModel->save();
                        }
                    }
                    VideoTag::where('videoId', $video->id)->delete();
                    if ($req->tags) {
                        foreach ($req->tags as $tagId) {
                            if ($tagId) {
                                $videoTagModel = new VideoTag();
                                $videoTagModel->videoId = $video->id;
                                $videoTagModel->tagId = $tagId;
                                $videoTagModel->save();
                            }
                        }
                    }
                    return back()->with('success', 'Update Video is Successfull.');
                }
            } else {
                return redirect('404');
            }
        } else {
            return back()->withErrors($validator);
        }
    }

    public function getCreate() {

        $model = new Video;
        $categories = Category::all();

        $tags = Tag::all();

        return View($this->template('form'))->with('model', $model)
                        ->with('tags', $tags)
                        ->with('categories', $categories);
    }

    public function postCreate(Request $req) {
        $validator = Validator::make($req->all(), Video::$rule);
        if ($validator->passes()) {
            $video = new Video;
            $video->fill($req->all());
            if (isset($_FILES['file']) && $_FILES['file']['name']) {
                $statusUpload = UploadHandler::uploadImage($_FILES['file'], 'images');
                if ($statusUpload['isSuccess']) {
                    $video->image = $statusUpload['data'];
                } else {
                    $validation->errors()->add('file', $statusUpload['data']);
                    return back()->withErrors($validator);
                }
            }
            if ($req->hour !== '' && $req->minute !== '' && $req->second !== '') {
                $video->duration = $req->hour * 60 * 60 + $req->minute * 60 + $req->second;
            }
            $video->slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $video->title));
            if ($video->save()) {
                if ($req->category) {
                    foreach ($req->category as $catId) {
                        $videoCatModel = new VideoCategory();
                        $videoCatModel->videoId = $video->id;
                        $videoCatModel->catId = $catId;
                        $videoCatModel->save();
                    }
                }
                if ($req->tags) {
                    foreach ($req->tags as $tagTitle) {
                        if ($tagTitle) {
                            $tag = Tag::where('title', $tagTitle)->first();
                            if (!$tag) {
                                $tag = new Tag;
                                $tag->title = $tagTitle;
                                $tag->save();
                            }
                            $videoTagModel = new VideoTag();
                            $videoTagModel->videoId = $video->id;
                            $videoTagModel->tagId = $tag->id;
                            $videoTagModel->save();
                        }
                    }
                }
                return redirect(action('Backend\VideoController@index'));
            }
        } else {
            return back()->withErrors($validator)->withInput($req->input());
        }
    }

}
