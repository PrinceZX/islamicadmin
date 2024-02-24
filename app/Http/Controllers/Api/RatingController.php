<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Common;
use App\Models\Notification;
use App\Models\User;
use App\Models\Video;
use App\Models\Audio;
use App\Models\Like;
use App\Models\View;
use App\Models\Follow;
use App\Models\Bookmark;
use App\Models\Download;
use App\Models\General_Setting;
use Illuminate\Http\Request;
use Validator;

class RatingController extends Controller
{
    public $common;

    private $folder2 = "user";
    private $folder_video = "video";
    private $folder_audio = "audio";
    private $folder_artist = "artist";

    public $user;
    public $video;


    public function __construct()
    {
        try {

            $this->common = new Common();
            $this->user = new User();
            $this->video = new Video();
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function add_comment(Request $request)
    {
        try{

            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                    'video_id' => 'required|numeric',
                    'comment' => 'required',
                    'rating' => 'required',
                    'type' => 'required',


                ],
                [
                    'user_id.required' => __('api_msg.please_enter_required_fields'),
                    'video_id.required' => __('api_msg.please_enter_required_fields'),
                    'comment.required' => __('api_msg.please_enter_required_fields'),
                    'rating.required' => __('api_msg.please_enter_required_fields'),
                    'type.required' => __('type is required'),

                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->all();
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                }
                return $data;
            }

            $user_id = $request['user_id'];
            $video_id = $request['video_id'];
            $comment = $request['comment'];
            $rating = $request['rating'];
            $type = $request['type'];

            $comment_data =Comment::where('user_id',$user_id)->where('type',$type)->where('video_id',$video_id)->first();

            if (isset($comment_data['id'])) {

                    $data['user_id'] = $user_id;
                    $data['video_id'] = $video_id;
                    $data['comment'] = $comment;
                    $data['rating'] =$rating;

    
                   Comment::where('id', $comment_data['id'])->update($data);

                return $this->common->API_Response(200,"Comment Update Succesfully" ,array($data));

            }else{
                $data['user_id'] = $user_id;
                $data['video_id'] = $video_id;
                $data['comment'] = $comment;
                $data['rating'] =$rating;
                $data['type'] =$type;
    
                $added_id = Comment::insertGetId($data);
                

                if($type == 'audio'){
                    $Video = Audio::where('id', $video_id)->where('is_aiaudiobook',"0")->first();
                }else if($type == 'aiaudio'){
                    $Video = Audio::where('id', $video_id)->where('is_aiaudiobook',"1")->first();
                }else{
                    $Video = Video::where('id', $video_id)->first();
                }
               

                $User = User::where('id', $user_id)->first();
    
                if (isset($Video) && isset($User) && $Video['user_id'] != $user_id) {
                    if($type == 'audio'){
                        $title = $User['full_name'] . ' Commented on your Audio.';
                    }else if($type == 'aiaudio'){
                        $title = $User['full_name'] . ' Commented on your AI Audio.';
                    }else{
                        $title = $User['full_name'] . ' Commented on your Video.';
                    }

                    $this->save_notification($User['id'], $Video['user_id'], $title, $video_id, 2);
    
                    return $this->common->API_Response(200, __('api_msg.comment_add') ,array($data));
    
                } else{
                    return $this->common->API_Response(200, __('api_msg.video_not_found'));
                }
            }


           
        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }   
    }

    public function edit_comment(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'comment_id' => 'required|numeric',
                    'user_id' => 'required|numeric',
                    'video_id' => 'required|numeric',
                    'comment' => 'required',
                ],
                [
                    'comment_id.required' => __('api_msg.comment_id_is_required'),
                    'user_id.required' => __('api_msg.user_id_is_required'),
                    'video_id.required' => __('api_msg.video_id_is_required'),
                    'comment.required' => __('api_msg.comment_is_required'),
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->first('comment_id');
                $errors1 = $validation->errors()->first('user_id');
                $errors2 = $validation->errors()->first('video_id');
                $errors3 = $validation->errors()->first('comment');
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                } elseif ($errors1) {
                    $data['message'] = $errors1;
                } elseif ($errors2) {
                    $data['message'] = $errors2;
                } elseif ($errors3) {
                    $data['message'] = $errors3;
                }
                return $data;
            }

            $comment_id = $request['comment_id'];
            $user_id = $request['user_id'];
            $video_id = $request['video_id'];
            $comment = $request['comment'];

            $data['comment'] = $comment;
            $data['user_id'] = $user_id;
            $data['video_id'] = $video_id;
            $data['status'] = 1;

            Comment::where('id', $comment_id)->update($data);

            return $this->common->API_Response(200, __('api_msg.successfully_update'));

        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function view_comment(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'video_id' => 'required|numeric',
                    'type' => 'required',
                ],
                [
                    'video_id.required' => __('api_msg.video_id_is_required'),
                    'type' => __('type is required'),
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->first('video_id');
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                }
                return $data;
            }

            $video_id = $request['video_id'];
            $type = $request['type'];

            $page_size = 0;
            $current_page = 0;
            $more_page = false;
            $page_limit = env('PAGE_LIMIT');

            $data = Comment::where('video_id', $video_id)->where('status', 1)->where('type', $type)->with('user')->orderBy('created_at', 'desc');

            $total_rows = $data->count();

            $total_page = $page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request->page_no ?? 1;
            $offset = $current_page * $total_page - $total_page;
            $data->take($total_page)->offset($offset);

            $more_page = $this->common->more_page($current_page, $page_size);

            $data = $data->get();

            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            foreach ($data as $row) {

                $row->full_name = "";
                $row->user_name = "";
                $row->profile_img = asset('/assets/imgs/users.png');
                if (isset($row->user) && $row->user != null) {
                    $row->full_name = $row->user['full_name'];
                    $row->user_name = $row->user['user_name'];
                    $row->profile_img = $this->common->getImagePath($this->folder2, $row->user['image']);
                }
                unset($row->user);
            }

            return $this->common->API_Response(200, "Comment Record Get Succesfully", $data, $pagination);

        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function delete_comment(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'comment_id' => 'required|numeric',
                ],
                [
                    'comment_id.required' => __('api_msg.comment_id_is_required'),
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->first('comment_id');
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                }
                return $data;
            }

            Comment::where('id', $request['comment_id'])->delete();

            return $this->common->API_Response(200, __('api_msg.record_delete_successfully'));
           
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    // Like Dislike
    public function like_dislike(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                    'video_id' => 'required|numeric',
                    'type' => 'required|string',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
                    'video_id.required' => __('api_msg.video_id_is_required'),
                    'type.required' => __('Type is required'),
                ]
            );
            if ($validation->fails()) {
 
                $errors = $validation->errors()->first('user_id');
                $errors1 = $validation->errors()->first('video_id');
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                } elseif ($errors1) {
                    $data['message'] = $errors1;
                } elseif ($errors2) {
                    $data['message'] = $errors2;
                }
                return $data;
            }
 
            $user_id = $request['user_id'];
            $video_id = $request['video_id'];
            $type = $request['type'];
 
            $ratings_result = Like::where('user_id', $user_id)->where('video_id', $video_id)->where('type', $type)->first();
            
            if (isset($ratings_result['id'])) {

                if($ratings_result->status ==0){

                    $data['status'] = "1";
                    Like::where('id', $ratings_result['id'])->update($data);

                    if($type == 'audio'){
                        $Video = Audio::where('id', $video_id)->where('is_aiaudiobook',"0")->first();
                    }else if($type == 'aiaudio'){
                        $Video = Audio::where('id', $video_id)->where('is_aiaudiobook',"1")->first();
                    }else{
                        $Video = Video::where('id', $video_id)->first();
                    }
                    

                    $User = User::where('id', $user_id)->first();
 
               
                    if (isset($Video) && isset($User) && $Video['user_id'] != $user_id) {
                        if($type == 'audio'){
                            $title = $User['full_name'] . ' Liked Your Audio.';
                        }else if($type == 'aiaudio'){
                            $title = $User['full_name'] . ' Liked Your AI Audio.';
                        }else{
                            $title = $User['full_name'] . ' Liked Your Video.';
                        } 
                        
                        $this->save_notification($User['id'], $Video['user_id'], $title, $video_id, 1);
                    }

                    return $this->common->API_Response(200, "Like Video");
     
                }else{  
                    $data['status'] = "0";
                    Like::where('id', $ratings_result['id'])->update($data);
     
                    return $this->common->API_Response(200, "DisLike Video");
                }
 
 
            } else {
 
                $data['video_id'] = $video_id;
                $data['user_id'] = $user_id;
                $data['type'] = $type;
                $data['status'] = 1;

                $added_id = Like::insertGetId($data);

                if($type == 'audio'){
                    $Video = Audio::where('id', $video_id)->where('is_aiaudiobook',"0")->first();
                }else if($type == 'aiaudio'){
                    $Video = Audio::where('id', $video_id)->where('is_aiaudiobook',"1")->first();
                }else{
                    $Video = Video::where('id', $video_id)->first();
                }

                $User = User::where('id', $user_id)->first();
 
               
                if (isset($Video) && isset($User) && $Video['user_id'] != $user_id) {
                    if($type == 'audio'){
                        $title = $User['full_name'] . ' Liked Your Audio.';
                    }else if($type == 'aiaudio'){
                        $title = $User['full_name'] . ' Liked Your AI Audio.';
                    }else{
                        $title = $User['full_name'] . ' Liked Your Video.';
                    }
                    $this->save_notification($User['id'], $Video['user_id'], $title, $video_id, 1);
                }

                return $this->common->API_Response(200, "Like Video");

            }
 
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function add_view(Request $request)
    {
        try{
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                    'video_id' => 'required|numeric',
                    'type' => 'required',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_required'),
                    'video_id.required' => __('api_msg.video_id_required'),
                    'type.required' => __('type is required'),
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->all();
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                }
                return $data;
            }

            $user_id =$request->user_id;
            $video_id =$request->video_id;
            $type =$request->type;
            //echo $type;exit;
            $data = View::where('user_id',$user_id)->where('video_id',$video_id)->where('type',$type)->first();
            if(empty($data)){

                $SaveData = new View();
                $SaveData->user_id = $user_id;
                $SaveData->video_id = $video_id;
                $SaveData->type = $type;

                if($SaveData->save()){
                    if($type == 'audio'){
                        $Plus = Audio::where('id', $SaveData->video_id)->where('is_aiaudiobook',"0")->first();
                    }else if($type == 'aiaudio'){
                        $Plus = Audio::where('id', $SaveData->video_id)->where('is_aiaudiobook',"1")->first();
                    }else{
                        $Plus = Video::where('id', $SaveData->video_id)->first();
                    }
                    
                    if (!empty($Plus)) {
                        $Plus->v_view = $Plus->v_view + 1;
                        $Plus->save();
                    }

                    return $this->common->API_Response(200, __('api_msg.video_view_add') ,array($SaveData));
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_save'));
                }

            } else {
                return $this->common->API_Response(200, __('api_msg.propertie_view_already'));
            }

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function addRemoveDownload(Request $request)
    {
        try {

            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                    'video_id' => 'required|numeric',
                ],
                [
                    'user_id.required' => __('api_msg.please_enter_required_fields'),
                    'video_id.required' => __('api_msg.please_enter_required_fields'),
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->all();
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                } 
                
                return $data;
            }

            $user_id = $request->user_id;
            $video_id = $request->video_id;

            $Data = Download::where('user_id', $user_id)->where('video_id', $video_id)->first();
            if (!empty($Data)) {
                $status = $Data->status;

                if ($status == '1') {

                    $Data->status = '0';
                    if ($Data->save()) {
                        return $this->common->API_Response(200, "Remove video");
                    }
                } else if ($status == '0') {

                    $Data->status = '1';
                    if ($Data->save()) {
                        return $this->common->API_Response(200, "Add Downlad Video");
                    }
                }
            } else {

                $insert = new Download();
                $insert->user_id = $user_id;
                $insert->video_id = $video_id;
                $insert->status =1;

                if ($insert->save()) {
                    return $this->common->API_Response(200, "Add Downlad Video");
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_save'));
                }
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function getDownloadVideo(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_required'),
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->all();
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                }
                return $data;
            }

            $user_id = $request['user_id'];

            $user_Ids = Download::select('video_id')->where('user_id', $user_id)->where('status',1)->get();

            $ids = [];
            foreach ($user_Ids as $value) {
                $ids[] = $value->video_id;
            }

            $page_size = 0;
            $current_page = 0;
            $more_page = false;
            $page_limit = env('PAGE_LIMIT');

            $data = Video::whereIn('id', $ids)->with('category','user','artist')->orderBy('v_view', 'desc');

            $total_rows = $data->count();

            $total_page = $page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request->page_no ?? 1;
            $offset = $current_page * $total_page - $total_page;
            $data->take($total_page)->offset($offset);

            $more_page = $this->common->more_page($current_page, $page_size);

            $data = $data->get()->toArray();

            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $dataarray = [];
            foreach ($data as $ra) {

                $datas = $this->common->get_all_count_for_video($ra['id'], $user_id);
                $ra = (object) array_merge((array) $ra, $datas);

                $ra->image = $this->common->getImagePath($this->folder_video, $ra->image);

                if($ra->video_type =="server_video"){
                    $ra->url = $this->common->getImagePath($this->folder_video, $ra->url);

                }

                $ra->is_like = "0";
                if ($user_id != 0) {
                    $ra->is_like = $this->common->is_like($request['user_id'], $ra->id);
                }

                $ra->category_name = "";
                if (isset($ra->category['name'])) {
                    $ra->category_name = $ra->category['name'];
                }

                $ra->artist_name = "";
                if (isset($ra->artist['name'])) {
                    $ra->artist_name = $ra->artist['name'];
                    $ra->artist_image = $this->common->getImagePath($this->folder_artist, $ra->artist['image']);

                }

                $ra->full_name = "";
                $ra->user_name = "";
                $ra->profile_img = asset('/assets/imgs/users.png');
                if (isset($ra->user)) {
                    $ra->full_name = $ra->user['full_name'];
                    $ra->user_name = $ra->user['user_name'];
                    $ra->profile_img = $this->common->getImagePath($this->folder2, $ra->user['image']);
                }

                $dataarray[] = $ra;
                unset($ra->category, $ra->user, $ra->artist);
               
            }

            return $this->common->API_Response(200, __('api_msg.video_record_get'), $dataarray, $pagination);

        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function add_favorite(Request $request)
    {
        try {
            if (isset($request['user_id']) && $request['video_id'] && $request['type']) {
                $user_id = $request['user_id'];
                $video_id = $request['video_id'];
                $type = $request['type'];

                $resultr_con = Bookmark::where('user_id', $user_id)->where('type', $type)->where('video_id', $video_id)->first();

                if (isset($resultr_con['id'])) {

                    Bookmark::where('user_id', $user_id)->where('video_id', $video_id)->where('type', $type)->delete();
                    return $this->common->API_Response(200, "Remove Favorite");

                } else {

                    $data = array(
                        'user_id' => $user_id,
                        'video_id' => $video_id,
                        'type' => $type,
                    );
                    Bookmark::insertGetId($data);

                    return $this->common->API_Response(200, "Add Favorite");
                }
            } else {
                return $this->common->API_Response(200, __('api_msg.please_enter_required_fields'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function favorite_list(Request $request)
    {
        try {
            if (isset($request['user_id']) && isset($request['type'])) {

                $user_id = $request['user_id'];
                $type = $request['type'];

                $page_size = 0;
                $current_page = 0;
                $more_page = false;
                $page_limit = env('PAGE_LIMIT');

                $result = Bookmark::select('video_id')->where('type', $type)->where('user_id', $user_id)->get();
                //echo "<pre>";print_r($result);exit;
                $videoarray = array();
                foreach ($result as $row) {
                    $videoarray[] = $row->video_id;
                }
                $video_ids = implode(',', array_map('intval', $videoarray));

                $Ids = explode(',', $video_ids);
                if($type == 'video'){
                    $data = Video::whereIn('id', $Ids)->with('category')->orderBy('created_at', 'desc');
                }else if($type == 'aiaudio'){
                    $data = Audio::whereIn('id', $Ids)->where('is_aiaudiobook',"1")->with('category')->orderBy('created_at', 'desc');
                }else{
                    $data = Audio::whereIn('id', $Ids)->where('is_aiaudiobook',"0")->with('category')->orderBy('created_at', 'desc');
                }
                

                $total_rows = $data->count();

                $total_page = $page_limit;
                $page_size = ceil($total_rows / $total_page);
                $current_page = $request->page_no ?? 1;
                $offset = $current_page * $total_page - $total_page;
                $data->take($total_page)->offset($offset);

                $more_page = $this->common->more_page($current_page, $page_size);

                $data = $data->get()->toArray();

                $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

                $dataarray = [];
                foreach ($data as $ra) {
                    if($type == 'video'){
                        $datas = $this->common->get_all_count_for_video($ra['id'], $user_id);
                        $ra = (object) array_merge((array) $ra, $datas);
                        $ra->image = $this->common->getImagePath($this->folder_video, $ra->image);
                        if($ra->video_type =="server_video"){
                            $ra->url = $this->common->getImagePath($this->folder_video, $ra->url);
                        }
                    }else if($type == 'aiaudio'){
                        $datas = $this->common->get_all_count_for_ai_audio($ra['id'], $user_id);
                        $ra = (object) array_merge((array) $ra, $datas);
                        $ra->image = $this->common->getImagePath($this->folder_audio, $ra->image);
                        if($ra->video_type =="server_video"){
                            $ra->url = $this->common->getImagePath($this->folder_audio, $ra->url);
                        }
                    }else{
                        $datas = $this->common->get_all_count_for_audio($ra['id'], $user_id);
                        $ra = (object) array_merge((array) $ra, $datas);
                        $ra->image = $this->common->getImagePath($this->folder_audio, $ra->image);
                        if($ra->video_type =="server_video"){
                            $ra->url = $this->common->getImagePath($this->folder_audio, $ra->url);
                        }
                    }
                    
    
                    $ra->is_like = "0";
                    if ($user_id != 0) {
                        $ra->is_like = $this->common->is_like($request['user_id'], $ra->id);
                    }
    
                    $ra->category_name = "";
                    if (isset($ra->category['name'])) {
                        $ra->category_name = $ra->category['name'];
                    }
    
                    $ra->artist_name = "";
                    if (isset($ra->artist['name'])) {
                        $ra->artist_name = $ra->artist['name'];
                        $ra->artist_image = $this->common->getImagePath($this->folder_artist, $ra->artist['image']);
    
                    }
    
                    $ra->full_name = "";
                    $ra->user_name = "";
                    $ra->profile_img = asset('/assets/imgs/users.png');
                    if (isset($ra->user)) {
                        $ra->full_name = $ra->user['full_name'];
                        $ra->user_name = $ra->user['user_name'];
                        $ra->profile_img = $this->common->getImagePath($this->folder2, $ra->user['image']);
                    }
    
                    $dataarray[] = $ra;
                    unset($ra->category, $ra->user, $ra->artist);
                   
                }

                if($type == 'video'){
                    return $this->common->API_Response(200, __('api_msg.video_record_get'), $dataarray, $pagination);
                }else if($type == 'aiaudio'){
                    return $this->common->API_Response(200, __('AI Audio favorite list Record Get successully.'), $dataarray, $pagination);
                }else{
                    return $this->common->API_Response(200, __('Audio Record favorite list Get successully.'), $dataarray, $pagination);
                }
            } else {
                return $this->common->API_Response(200, __('api_msg.please_enter_required_fields'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    // Follow

    public function follow(Request $request)
    {
        try{
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                    'artist_id' => 'required|numeric',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_required'),
                    'artist_id.required' => "Artist Id Feild IS Required",
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->all();
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                } elseif ($errors1) {
                    $data['message'] = $errors1;
                }
                return $data;
            }

            $user_id =$request['user_id'];
            $artist_id =$request['artist_id'];

            $ratings_result = Follow::where('user_id', $user_id)->where('artist_id', $artist_id)->first();

            if(isset($ratings_result['id'])){
                
                Follow::where('id', $ratings_result['id'])->delete();

                return $this->common->API_Response(200, __('api_msg.unfollow_successfully'));

            }else{
                $data['user_id'] = $user_id;
                $data['artist_id'] = $artist_id;

                $added_id = Follow::insertGetId($data);
                $data['id'] = $added_id;

                // $VideoUSer = User::where('id', $user_id)->first();

                // $title = $VideoUSer['full_name'] . ' Start following you.';
                // $this->save_notification($user_id, $to_user_id, $title, 0, 3);

                return $this->common->API_Response(200, __('api_msg.follow_successfully'));
            }


        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function follow_videos(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_required'),
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->all();
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                }
                return $data;
            }

            $user_id = $request['user_id'];

            $user_Ids = Follow::where('user_id', $user_id)->get();

            $ids = [];
            foreach ($user_Ids as $value) {
                $ids[] = $value->to_user_id;
            }

            $page_size = 0;
            $current_page = 0;
            $more_page = false;
            $page_limit = env('PAGE_LIMIT');

            $data = Video::whereIn('user_id', $ids)->with('category','user','artist')->orderBy('v_view', 'desc');

            $total_rows = $data->count();

            $total_page = $page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request->page_no ?? 1;
            $offset = $current_page * $total_page - $total_page;
            $data->take($total_page)->offset($offset);

            $more_page = $this->common->more_page($current_page, $page_size);

            $data = $data->get()->toArray();

            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $dataarray = [];
            foreach ($data as $ra) {

                $datas = $this->common->get_all_count_for_video($ra['id'], $user_id);
                $ra = (object) array_merge((array) $ra, $datas);

                $ra->image = $this->common->getImagePath($this->folder_video, $ra->image);

                if($ra->video_type =="server_video"){
                    $ra->url = $this->common->getImagePath($this->folder_video, $ra->url);

                }

                $ra->is_like = "0";
                if ($user_id != 0) {
                    $ra->is_like = $this->common->is_like($request['user_id'], $ra->id);
                }

                $ra->category_name = "";
                if (isset($ra->category['name'])) {
                    $ra->category_name = $ra->category['name'];
                }

                $ra->artist_name = "";
                if (isset($ra->artist['name'])) {
                    $ra->artist_name = $ra->artist['name'];
                    $ra->artist_image = $this->common->getImagePath($this->folder_artist, $ra->artist['image']);

                }

                $ra->full_name = "";
                $ra->user_name = "";
                $ra->profile_img = asset('/assets/imgs/users.png');
                if (isset($ra->user)) {
                    $ra->full_name = $ra->user['full_name'];
                    $ra->user_name = $ra->user['user_name'];
                    $ra->profile_img = $this->common->getImagePath($this->folder2, $ra->user['image']);
                }

                $dataarray[] = $ra;
                unset($ra->category, $ra->user, $ra->artist);
               
            }

            return $this->common->API_Response(200, __('api_msg.video_record_get'), $dataarray, $pagination);

        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function following_list(Request $request)
    {
        try{

            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->all();
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                }
                return $data;
            }

            $user_id =$request['user_id'];

            $page_size = 0;
            $current_page = 0;
            $more_page = false;
            $page_limit = env('PAGE_LIMIT');

            $result = Follow::select('to_user_id')->where('user_id', $user_id)->get();
            $userarray = array();

            foreach ($result as $row) {
                $userarray[] = $row->to_user_id;
            }
            $user_ids = implode(',', array_map('intval', $userarray));

            $Ids = explode(',', $user_ids);

            $data = User::whereIn('id', $Ids)->orderBy('created_at', 'desc');

            $total_rows = $data->count();

            $total_page = $page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request->page_no ?? 1;
            $offset = $current_page * $total_page - $total_page;
            $data->take($total_page)->offset($offset);

            $more_page = $this->common->more_page($current_page, $page_size);

            $data = $data->get();

            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $result = [];
            foreach ($data as $key => $value) {
                $value['image'] = $this->common->getImagePath($this->folder2, $value['image']);
                unset($value['password']);
                $result[] = $value;
            }
            return $this->common->API_Response(200, __('api_msg.get_follow_list'), $result, $pagination);
            

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function follow_list(Request $request)
    {
        try{
            $validation = Validator::make(
                $request->all(),
                [
                    'artist_id' => 'required|numeric',
                ],
                [
                    'artist_id.required' => __('api_msg.user_id_required'),
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->all();
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                }
                return $data;
            }

            $artist_id = $request['artist_id'];

            $page_size = 0;
            $current_page = 0;
            $more_page = false;
            $page_limit = env('PAGE_LIMIT');

            $result = Follow::select('user_id')->where('artist_id', $artist_id)->get();
            $userarray = array();
            
            foreach ($result as $row) {
                $userarray[] = $row->user_id;
            }
            $user_ids = implode(',', array_map('intval', $userarray));
            
            $Ids = explode(',', $user_ids);

            $data = User::whereIn('id', $Ids)->orderBy('created_at', 'desc');

            $total_rows = $data->count();

            $total_page = $page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request->page_no ?? 1;
            $offset = $current_page * $total_page - $total_page;
            $data->take($total_page)->offset($offset);

            $more_page = $this->common->more_page($current_page, $page_size);

            $data = $data->get();

            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $result = [];
            foreach ($data as $key => $value) {
                $value['image'] = $this->common->getImagePath($this->folder2, $value['image']);
                unset($value['password']);
                $result[] = $value;
            }
            return $this->common->API_Response(200, __('api_msg.get_follow_list'), $result, $pagination);

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
 
    // Notification
    public function save_notification($from_user_id = 0, $user_id = 0, $title = null, $video = 0, $type = 1)
    {
        try {
            $data['title'] = $title;
            $data['from_user_id'] = $from_user_id;
            $data['user_id'] = $user_id;
            $data['video_id'] = $video;
            $data['type'] = $type; // 1-Like, 2-Comment, 3-Following, 4-Admin

            $data['message'] = "";
            $data['image'] = "";
            Notification::insertGetId($data);
  
            $toUser = [];
            $user = User::where('id', $user_id)->first();

            
            if(isset($user['device_token'])){

                $toUser[] = $user['device_token'];

                $setting = settingData();
                $ONESIGNAL_APP_ID = $setting['onesignal_apid'];
                $ONESIGNAL_REST_KEY = $setting['onesignal_rest_key'];

            
                // Device type (1 = Android, 2 = IOS)
                $fields = array();
                if ($user['device_type'] == 1) {
                    $fields = array(
                        'app_id' => $ONESIGNAL_APP_ID,
                        'include_android_reg_ids' => $toUser,
                        "isAndroid" => true,
                        "channel_for_external_user_ids" => "push",
                        'headings' => array("en" => $title),
                        'contents' => array("en" => $title),
                    );
                } elseif ($user['device_type'] == 2) {
                    $fields = array(
                        'app_id' => $ONESIGNAL_APP_ID,
                        'include_player_ids' => $toUser,
                        "isIos" => true,
                        "channel_for_external_user_ids" => "push",
                        'headings' => array("en" => $title),
                        'contents' => array("en" => $title),
                    );
                }

                $fields = json_encode($fields);

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json; charset=utf-8',
                    'Authorization: Basic ' . $ONESIGNAL_REST_KEY
                ));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_HEADER, FALSE);
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

                $response = curl_exec($ch);


                curl_close($ch);
                return true;
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
 
    
}
