<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\User;
use App\Models\Category;
use App\Models\Artist;
use App\Models\Album;
use App\Models\Page;
use App\Models\Package;
use App\Models\Video;
use App\Models\Audio;
use App\Models\Follow;
use App\Models\Like;
use App\Models\Comment;
use App\Models\General_Setting;
use App\Models\User_Notification_Tracking;
use App\Models\Transaction;
use App\Models\Payment_Option;

use App\Models\Notification;
use Illuminate\Http\Request;
use Validator;
use Storage;
use Config;

class HomeController extends Controller
{
    private $folder = "user";
    private $folder1 = "app_setting";
    private $folder2 = "category";
    private $folder4 = "package";
    private $folder5 = "video";
    private $folder6 = "notification";
    private $folder_artist = "artist";
    private $folder_album = "album";
    public $user;
    public $video;



    public $common;

    public function __construct()
    {
        $this->common = new Common;
        $this->user = new User();
        $this->video = new Video();
    }

    public function GeneralSetting(Request $request)
    {
        try {
            $list = General_Setting::select('id', 'key', 'value')->get();
            foreach ($list as $key => $value) {
                if ($value['key'] == 'app_logo') {
                    $appName = Config::get('app.image_url');
                    if (Storage::disk('public')->exists($this->folder1 . '/' . $value['value'])) {
                        $value['value'] = $appName . $this->folder1 . '/' . $value['value'];
                    } else {
                        $value['value'] = asset('assets/imgs/no_img.png');
                    }
                }
            }
            return $this->common->API_Response(200, __('api_msg.general_setting_record_get'), $list);
           
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function Get_pages(Request $request)
    {
        try{
            $data = Page::get();

            $return['status'] = 200;
            $return['message'] = __('api_msg.record_get_succesfully');
            $return['result'] = [];

            for ($i=0; $i < count($data); $i++) { 

                $return['result'][$i]['page_name'] = $data[$i]['page_name'];
                $return['result'][$i]['url'] = env('APP_URL') .'/public/'. $data[$i]['page_name'];
            }
            return $return;

        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function Get_payment_option(Request $request)
    {
        try {
            $Option_data = Payment_Option::get();

            $return['status'] = 200;
            $return['message'] = __('api_msg.record_get_succesfully');
            $return['result'] =[];

            foreach ($Option_data as $key => $value) {

                $return['result'][$value['name']] = $value;
            }

            return $return;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function subscription_list(Request $request)
    {
        try {

            $all_data = Transaction::get();
            for ($i = 0; $i < count($all_data); $i++) {
                if ($all_data[$i]['expiry_date'] <= date("Y-m-d")) {
                    $all_data[$i]->status = '0';
                    $all_data[$i]->save();
                }
            }

            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                ],
                [
                    'user_id.required' => __('api_msg.please_enter_required_fields'),
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->first('user_id');
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                }
                return $data;
            }

            $user_id = $request->user_id;
            $result = Transaction::where('user_id', $user_id)->with('package')->latest()->get();

            foreach ($result as $key => $value) {
                if ($value['package'] != null) {
                    $value['package_name'] = $value['package']['name'];
                    $value['package_price'] = $value['package']['price'];
                } else {
                    $value['package_name'] = "";
                    $value['package_price'] = 0;
                }

                $value['data'] = $value['created_at']->format('Y-m-d');

                unset($value['package']);
            }

            return $this->common->API_Response(200, __('api_msg.record_get_succesfully'), $result);

        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function User_List(Request $request)
    {
        try{
            $page_size = 0;
            $current_page = 0;
            $more_page = false;
            $page_limit = env('PAGE_LIMIT');

            $data = User::orderBy('id', 'DESC');

            $total_rows = $data->count();

            $total_page = $page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request->page_no ?? 1;
            $offset = $current_page * $total_page - $total_page;
            $data->take($total_page)->offset($offset);

            $more_page = $this->common->more_page($current_page, $page_size);

            $data = $data->get();

            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);
            
            // Image Name to URL
            $this->common->imageNameToUrl($data, 'image', $this->folder);

            return $this->common->API_Response(200, __('api_msg.user_record_get'), $data, $pagination);
           

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function Get_Category(Request $request)
    {
        try{
            $page_size =0;
            $current_page = 0;
            $more_page = false;
            $page_limit = env('PAGE_LIMIT');

            $data = Category::orderBy('id','DESC');  

            $total_rows = $data->count();
            $total_page = $page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request->page_no ?? 1;
            $offset = $current_page * $total_page - $total_page;
            $data->take($total_page)->offset($offset);

            $more_page = $this->common->more_page($current_page, $page_size);

            $data = $data->get();

            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

                // Image Name to URL
                $this->common->imageNameToUrl($data, 'image', $this->folder2);

                return $this->common->API_Response(200, __('api_msg.category_record_get'), $data, $pagination);

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }


    public function Get_Package(Request $request)
    {
        try {
            $user_id = isset($request->user_id) ? $request->user_id : 0;

            $page_size = 0;
            $current_page = 0;
            $more_page = false;
            $page_limit = env('PAGE_LIMIT');

            $data = Package::orderBy('id', 'DESC');

            $total_rows = $data->count();

            $total_page = $page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request->page_no ?? 1;
            $offset = $current_page * $total_page - $total_page;
            $data->take($total_page)->offset($offset);

            $more_page = $this->common->more_page($current_page, $page_size);

            $data = $data->get();

            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            // Image Name to URL
                $this->common->imageNameToUrl($data, 'image', $this->folder4);

                for ($i = 0; $i < count($data); $i++) {
                    $data[$i]['is_buy'] = $this->common->is_buy($user_id,$data[$i]['id']);
                }
                
                return $this->common->API_Response(200, __('api_msg.package_record_get'), $data, $pagination);

        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function latest_video(Request $request)
    {
        try{
            $user_id = isset($request['user_id']) ? $request['user_id'] : 0;

            $page_size = 0;
            $current_page = 0;
            $more_page = false;
            $page_limit = env('PAGE_LIMIT');

            $data = Video::with('category','artist','user')->latest();

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

                $data1 = $this->common->get_all_count_for_video($ra['id'], $user_id, $ra['user_id']);
                $ra = (object) array_merge((array) $ra, $data1);

                $ra->is_like = "0";
                if ($user_id != 0) {
                    $ra->is_like = $this->common->is_like($request['user_id'], $ra->id);
                }

                if($ra->video_type =="server_video"){
                    $ra->url = $this->common->getImagePath($this->folder5, $ra->url);

                }
                $ra->image = $this->common->getImagePath($this->folder5, $ra->image);

                $ra->category_name = "";
                if (isset($ra->category['name'])) {
                    $ra->category_name = $ra->category['name'];
                }

                $ra->artist_name = "";
                if (isset($ra->artist['name'])) {
                    $ra->artist_name = $ra->artist['name'];
                }

               
                $ra->full_name = "";
                $ra->user_name = "";
                $ra->profile_img = asset('/assets/imgs/users.png');
                if (isset($ra->user)) {
                    $ra->full_name = $ra->user['full_name'];
                    $ra->user_name = $ra->user['user_name'];
                    $ra->profile_img = $this->common->getImagePath($this->folder, $ra->user['image']);
                }

                $dataarray[] = $ra;
                unset($ra->category, $ra->user, $ra->artist);
            }

            return $this->common->API_Response(200, __('api_msg.video_record_get'), $dataarray, $pagination);

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function latest_audio(Request $request)
    {
        try{
            $user_id = isset($request['user_id']) ? $request['user_id'] : 0;

            $page_size = 0;
            $current_page = 0;
            $more_page = false;
            $page_limit = env('PAGE_LIMIT');

            $data = Audio::with('category','artist','user')->latest();

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

                $ra['audio'] = url('audio').'/'.$ra['audio'];

                $data1 = $this->common->get_all_count_for_video($ra['id'], $user_id, $ra['user_id']);
                $ra = (object) array_merge((array) $ra, $data1);

                $ra->is_like = "0";
                if ($user_id != 0) {
                    $ra->is_like = $this->common->is_like($request['user_id'], $ra->id);
                }

                if($ra->video_type =="server_video"){
                    $ra->url = $this->common->getImagePath($this->folder5, $ra->url);

                }
                //$ra->image = $this->common->getImagePath($this->folder5, $ra->image);

                $ra->category_name = "";
                if (isset($ra->category['name'])) {
                    $ra->category_name = $ra->category['name'];
                }

                $ra->artist_name = "";
                if (isset($ra->artist['name'])) {
                    $ra->artist_name = $ra->artist['name'];
                }

               
                $ra->full_name = "";
                $ra->user_name = "";
                $ra->profile_img = asset('/assets/imgs/users.png');
                if (isset($ra->user)) {
                    $ra->full_name = $ra->user['full_name'];
                    $ra->user_name = $ra->user['user_name'];
                    $ra->profile_img = $this->common->getImagePath($this->folder, $ra->user['image']);
                }

                $dataarray[] = $ra;
                unset($ra->category, $ra->user, $ra->artist);
            }

            return $this->common->API_Response(200, 'Audio list get succcesfully', $dataarray, $pagination);

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function video_list(Request $request)
    {
        try{
            $user_id = isset($request['user_id']) ? $request['user_id'] : 0;

            $page_size = 0;
            $current_page = 0;
            $more_page = false;
            $page_limit = env('PAGE_LIMIT');

            $data = Video::with('category','artist','user')->where('is_approved', 1)->latest();

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

                $data1 = $this->common->get_all_count_for_video($ra['id'], $user_id, $ra['user_id']);
                $ra = (object) array_merge((array) $ra, $data1);

                $ra->is_like = "0";
                if ($user_id != 0) {
                    $ra->is_like = $this->common->is_like($request['user_id'], $ra->id);
                }

                if($ra->video_type =="server_video"){
                    $ra->url = $this->common->getImagePath($this->folder5, $ra->url);

                }
                $ra->image = $this->common->getImagePath($this->folder5, $ra->image);

                $ra->category_name = "";
                if (isset($ra->category['name'])) {
                    $ra->category_name = $ra->category['name'];
                }

                $ra->artist_name = "";
                if (isset($ra->artist['name'])) {
                    $ra->artist_name = $ra->artist['name'];
                }

               
                $ra->full_name = "";
                $ra->user_name = "";
                $ra->profile_img = asset('/assets/imgs/users.png');
                if (isset($ra->user)) {
                    $ra->full_name = $ra->user['full_name'];
                    $ra->user_name = $ra->user['user_name'];
                    $ra->profile_img = $this->common->getImagePath($this->folder, $ra->user['image']);
                }

                $dataarray[] = $ra;
                unset($ra->category, $ra->user, $ra->artist);
            }

            return $this->common->API_Response(200, __('api_msg.video_record_get'), $dataarray, $pagination);

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function audio_list(Request $request)
    {
        try{
            $user_id = isset($request['user_id']) ? $request['user_id'] : 0;

            $page_size = 0;
            $current_page = 0;
            $more_page = false;
            $page_limit = env('PAGE_LIMIT');

            $data = Audio::with('category','artist','user')->where('is_aiaudiobook', 0)->where('is_approved', 1)->latest();

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

                $ra['audio'] = url('audio').'/'.$ra['audio'];
                $data1 = $this->common->get_all_count_for_video($ra['id'], $user_id, $ra['user_id']);
                $ra = (object) array_merge((array) $ra, $data1);

                $ra->is_like = "0";
                if ($user_id != 0) {
                    $ra->is_like = $this->common->is_like($request['user_id'], $ra->id);
                }

                if($ra->video_type =="server_video"){
                    $ra->url = $this->common->getImagePath($this->folder5, $ra->url);

                }

                //$ra->image = $this->common->getImagePath($this->folder5, $ra->image);

                $ra->category_name = "";
                if (isset($ra->category['name'])) {
                    $ra->category_name = $ra->category['name'];
                }

                $ra->artist_name = "";
                if (isset($ra->artist['name'])) {
                    $ra->artist_name = $ra->artist['name'];
                }

               
                $ra->full_name = "";
                $ra->user_name = "";
                $ra->profile_img = asset('/assets/imgs/users.png');
                if (isset($ra->user)) {
                    $ra->full_name = $ra->user['full_name'];
                    $ra->user_name = $ra->user['user_name'];
                    $ra->profile_img = $this->common->getImagePath($this->folder, $ra->user['image']);
                }

                $dataarray[] = $ra;
                unset($ra->category, $ra->user, $ra->artist);
            }

            return $this->common->API_Response(200, 'Audio list get succcesfully', $dataarray, $pagination);

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function video_by_id(Request $request)
    {
        try{
            $validation = Validator::make(
                $request->all(),
                [
                    'video_id' => 'required|numeric',
                ],
                [
                    'video_id.required' => __('api_msg.please_enter_required_fields'),
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

            $video_id =$request['video_id'];
            $user_id = isset($request->user_id) ? $request->user_id : 0;

            $data = Video::where('id',$video_id)->with('user','category','artist')->get()->toArray();
            $rk = array();

            foreach ($data as $ra) {


                $data1 = $this->common->get_all_count_for_video($ra['id'], $user_id, $ra['user_id']);

                $ra = (object) array_merge((array) $ra, $data1);

                $ra->is_like = "0";
                if ($user_id != 0) {
                    $ra->is_like = $this->common->is_like($request['user_id'], $ra->id);
                }

                    $ra->is_buy = $this->common->check_is_buy($request['user_id']);

                // $ra->is_follw = "0";
                // if ($user_id != 0) {
                //     $ra->is_follw = $this->common->is_follw($request['user_id']);
                // }

                if($ra->video_type =="server_video"){
                    $ra->url = $this->common->getImagePath($this->folder5, $ra->url);

                }
                $ra->image = $this->common->getImagePath($this->folder5, $ra->image);

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
                    $ra->profile_img = $this->common->getImagePath($this->folder, $ra->user['image']);
                }

               
                

                $rk[] = $ra;
                unset($ra->category, $ra->user, $ra->artist);
            }

            if (sizeof($rk) > 0) {
                return $this->common->API_Response(200, __('api_msg.video_record_get'), $rk);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }

        
        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function audio_by_id(Request $request)
    {
        try{
            $validation = Validator::make(
                $request->all(),
                [
                    'audio_id' => 'required|numeric',
                ],
                [
                    'aduio_id.required' => __('api_msg.please_enter_required_fields'),
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->first('audio_id');
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                }
                return $data;
            }

            $video_id =$request['audio_id'];
            $user_id = isset($request->user_id) ? $request->user_id : 0;

            $data = Audio::where('id',$video_id)->with('user','category','artist')->get()->toArray();
            $rk = array();

            foreach ($data as $ra) {


                $data1 = $this->common->get_all_count_for_video($ra['id'], $user_id, $ra['user_id']);

                $ra = (object) array_merge((array) $ra, $data1);

                $ra->is_like = "0";
                if ($user_id != 0) {
                    $ra->is_like = $this->common->is_like($request['user_id'], $ra->id);
                }

                    $ra->is_buy = $this->common->check_is_buy($request['user_id']);

                // $ra->is_follw = "0";
                // if ($user_id != 0) {
                //     $ra->is_follw = $this->common->is_follw($request['user_id']);
                // }

                if($ra->video_type =="server_video"){
                    $ra->url = $this->common->getImagePath($this->folder5, $ra->url);

                }
                //$ra->image = $this->common->getImagePath($this->folder5, $ra->image);

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
                    $ra->profile_img = $this->common->getImagePath($this->folder, $ra->user['image']);
                }

               
                

                $rk[] = $ra;
                unset($ra->category, $ra->user, $ra->artist);
            }

            if (sizeof($rk) > 0) {
                return $this->common->API_Response(200, 'Audio list get succcesfully', $rk);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }

        
        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    
    public function Most_viewed_Video(Request $request)
    {
        try{
            $user_id = isset($request['user_id']) ? $request['user_id'] : 0;

            $page_size = 0;
            $current_page = 0;
            $more_page = false;
            $page_limit = env('PAGE_LIMIT');

            $data = Video::orderBy('v_view','DESC')->with('category','artist','user')->latest();

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

                $data1 = $this->common->get_all_count_for_video($ra['id'], $user_id, $ra['user_id']);

                $ra = (object) array_merge((array) $ra, $data1);

                $ra->is_like = "0";
                if ($user_id != 0) {
                    $ra->is_like = $this->common->is_like($request['user_id'], $ra->id);
                }

                if($ra->video_type =="server_video"){
                    $ra->url = $this->common->getImagePath($this->folder5, $ra->url);

                }
                $ra->image = $this->common->getImagePath($this->folder5, $ra->image);

                $ra->category_name = "";
                if (isset($ra->category['name'])) {
                    $ra->category_name = $ra->category['name'];
                }

                $ra->artist_name = "";
                if (isset($ra->artist['name'])) {
                    $ra->artist_name = $ra->artist['name'];
                }

               
                $ra->full_name = "";
                $ra->user_name = "";
                $ra->profile_img = asset('/assets/imgs/users.png');
                if (isset($ra->user)) {
                    $ra->full_name = $ra->user['full_name'];
                    $ra->user_name = $ra->user['user_name'];
                    $ra->profile_img = $this->common->getImagePath($this->folder, $ra->user['image']);
                }

                $dataarray[] = $ra;
                unset($ra->category, $ra->user, $ra->artist);
            }

            return $this->common->API_Response(200, __('api_msg.video_record_get'), $dataarray, $pagination);

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function Premium_Video(Request $request)
    {
        try{
            $user_id = isset($request['user_id']) ? $request['user_id'] : 0;

            $page_size = 0;
            $current_page = 0;
            $more_page = false;
            $page_limit = env('PAGE_LIMIT');

            $data = Video::where('is_paid',"1")->with('category','artist','user')->latest();

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

                $data1 = $this->common->get_all_count_for_video($ra['id'], $user_id, $ra['user_id']);

                $ra = (object) array_merge((array) $ra, $data1);

                $ra->is_like = "0";
                if ($user_id != 0) {
                    $ra->is_like = $this->common->is_like($request['user_id'], $ra->id);
                }

                if($ra->video_type =="server_video"){
                    $ra->url = $this->common->getImagePath($this->folder5, $ra->url);

                }
                $ra->image = $this->common->getImagePath($this->folder5, $ra->image);

                $ra->category_name = "";
                if (isset($ra->category['name'])) {
                    $ra->category_name = $ra->category['name'];
                }

                $ra->artist_name = "";
                if (isset($ra->artist['name'])) {
                    $ra->artist_name = $ra->artist['name'];
                }

               
                $ra->full_name = "";
                $ra->user_name = "";
                $ra->profile_img = asset('/assets/imgs/users.png');
                if (isset($ra->user)) {
                    $ra->full_name = $ra->user['full_name'];
                    $ra->user_name = $ra->user['user_name'];
                    $ra->profile_img = $this->common->getImagePath($this->folder, $ra->user['image']);
                }

                $dataarray[] = $ra;
                unset($ra->category, $ra->user, $ra->artist);
            }

            return $this->common->API_Response(200, __('api_msg.video_record_get'), $dataarray, $pagination);

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function Video_by_category(Request $request)
    {
        try {

            $validation = Validator::make(
                $request->all(),
                [
                    'category_id' => 'required|numeric',
                ],
                [
                    'category_id.required' => __('api_msg.please_enter_required_fields'),
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->first('category_id');
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                }
                return $data;
            }

            $category_id =$request->category_id;
            $user_id = isset($request->user_id) ? $request->user_id : 0;
            

            $page_size = 0;
            $current_page = 0;
            $more_page = false;
            $page_limit = env('PAGE_LIMIT');

            $data = Video::where('category_id',$category_id)->with('user','category','artist')->latest();

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

                $data1 = $this->common->get_all_count_for_video($ra['id'], $user_id, $ra['user_id']);

                $ra = (object) array_merge((array) $ra, $data1);

                $ra->is_like = "0";
                if ($user_id != 0) {
                    $ra->is_like = $this->common->is_like($request['user_id'], $ra->id);
                }

                if($ra->video_type =="server_video"){
                    $ra->url = $this->common->getImagePath($this->folder5, $ra->url);

                }
                $ra->image = $this->common->getImagePath($this->folder5, $ra->image);

                $ra->category_name = "";
                if (isset($ra->category['name'])) {
                    $ra->category_name = $ra->category['name'];
                }

                $ra->artist_name = "";
                if (isset($ra->artist['name'])) {
                    $ra->artist_name = $ra->artist['name'];
                }

               
                $ra->full_name = "";
                $ra->user_name = "";
                $ra->profile_img = asset('/assets/imgs/users.png');
                if (isset($ra->user)) {
                    $ra->full_name = $ra->user['full_name'];
                    $ra->user_name = $ra->user['user_name'];
                    $ra->profile_img = $this->common->getImagePath($this->folder, $ra->user['image']);
                }

                $dataarray[] = $ra;
                unset($ra->category, $ra->user, $ra->artist);
            }

            return $this->common->API_Response(200, __('api_msg.video_record_get'), $dataarray, $pagination);
        
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function Video_by_artist(Request $request)
    {
        try {

            $validation = Validator::make(
                $request->all(),
                [
                    'artist_id' => 'required|numeric',
                ],
                [
                    'artist_id.required' => __('api_msg.please_enter_required_fields'),
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->first('category_id');
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                }
                return $data;
            }

            $artist_id = $request->artist_id;
            $user_id = isset($request->user_id) ? $request->user_id : 0;

            $page_size = 0;
            $current_page = 0;
            $more_page = false;
            $page_limit = env('PAGE_LIMIT');

            $data = Video::where('artist_id',$artist_id)->with('user','artist','category')->latest();

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

                $follow =Follow::where('artist_id',$artist_id)->get()->count();

                $ra['follower_count'] = $follow;

                $data1 = $this->common->get_all_count_for_video($ra['id'], $user_id, $ra['user_id']);

                $ra = (object) array_merge((array) $ra, $data1);

                $ra->is_like = "0";
                if ($user_id != 0) {
                    $ra->is_like = $this->common->is_like($request['user_id'], $ra->id);
                }

                if($ra->video_type =="server_video"){
                    $ra->url = $this->common->getImagePath($this->folder5, $ra->url);

                }
                $ra->image = $this->common->getImagePath($this->folder5, $ra->image);

                $ra->category_name = "";
                if (isset($ra->category['name'])) {
                    $ra->category_name = $ra->category['name'];
                }

                $ra->artist_name = "";
                if (isset($ra->artist['name'])) {
                    $ra->artist_name = $ra->artist['name'];
                }

               
                $ra->full_name = "";
                $ra->user_name = "";
                $ra->profile_img = asset('/assets/imgs/users.png');
                if (isset($ra->user)) {
                    $ra->full_name = $ra->user['full_name'];
                    $ra->user_name = $ra->user['user_name'];
                    $ra->profile_img = $this->common->getImagePath($this->folder, $ra->user['image']);
                }

                $dataarray[] = $ra;
                unset($ra->category, $ra->user, $ra->artist);
            }

            return $this->common->API_Response(200, __('api_msg.video_record_get'), $dataarray, $pagination);


        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function artist_profile(Request $request)
    {
        try{

            $validation = Validator::make(
                $request->all(),
                [
                    'artist_id' => 'required|numeric',
                ],
                [
                    'artist_id.required' => "Artist Feild Is Required",
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

            $artist_id = $request->artist_id;
            $user_id = isset($request->user_id) ? $request->user_id : 0;


            $data = Artist::where('id',$artist_id)->first();

            if(!empty($data)){
                $artist_id =$data->id;

                $path = $this->common->getImagePath($this->folder_artist, $data['image']);
                $data['image'] = $path;

                $video =Video::where('artist_id',$artist_id)->count();
                

                $data['artist_video_count'] =$video;

                $data->is_follow = "0";
                if ($user_id != 0) {
                    $data->is_follow = $this->common->is_follow($request['user_id']);
                }

               


                return $this->common->API_Response(200, "Artist Profile Record Get", array($data));
            
            }else{
                return $this->common->API_Response(200, "Record Not Found");
                
            }

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }

    }
    
    public function Search_Video(Request $request)
    {
        try{
            $validation = Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                ],
                [
                    'name.required' => __('api_msg.Name Feild Requried'),
                ]
            );
            if ($validation->fails()) {
                $errors = $validation->errors()->first('name');
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                }
                return $data;
            }

            $video_name = $request['name'];
            $user_id = isset($request['user_id']) ? $request['user_id'] : 0;

            $resultr = $this->common->video_search($video_name);
            $data = array();

            foreach ($resultr as $ra) {

                $data1 = $this->common->get_all_count_for_video($ra['id'], $user_id, $ra['user_id']);

                $ra = (object) array_merge((array) $ra, $data1);

                $ra->is_like = "0";
                if ($user_id != 0) {
                    $ra->is_like = $this->common->is_like($request['user_id'], $ra->id);
                }

                $ra->image = $this->common->getImagePath($this->folder5, $ra->image);

                if($ra->video_type =="server_video"){
                    $ra->url = $this->common->getImagePath($this->folder5, $ra->url);
                    
                }

                $ra->category_name = "";
                if (isset($ra->category['name'])) {
                    $ra->category_name = $ra->category['name'];
                }

                $ra->artist_name = "";
                if (isset($ra->artist['name'])) {
                    $ra->artist_name = $ra->artist['name'];
                }

            

                $ra->full_name = "";
                $ra->user_name = "";
                $ra->profile_img = asset('/assets/imgs/users.png');
                if (isset($ra->user)) {
                    $ra->full_name = $ra->user['full_name'];
                    $ra->user_name = $ra->user['user_name'];
                    $ra->profile_img = $this->common->getImagePath($this->folder, $ra->user['image']);
                }

                $data[] = $ra;
                unset($ra->category, $ra->user, $ra->artist);

                

            }

            if (sizeof($data) > 0) {
                return $this->common->API_Response(200, __('api_msg.video_record_get'), $data);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function Get_Artist(Request $request)
    {
        try{
            $page_size =0;
            $current_page = 0;
            $more_page = false;
            $page_limit = env('PAGE_LIMIT');

            $data = Artist::orderBy('id','DESC');  

            $total_rows = $data->count();
            $total_page = $page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request->page_no ?? 1;
            $offset = $current_page * $total_page - $total_page;
            $data->take($total_page)->offset($offset);

            $more_page = $this->common->more_page($current_page, $page_size);

            $data = $data->get();

            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);
            
            // Image Name to URL
                $this->common->imageNameToUrl($data, 'image', $this->folder_artist);

                return $this->common->API_Response(200, __('api_msg.artist_record_get'), $data, $pagination);

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function Get_Album(Request $request)
    {
        try{
            $page_size =0;
            $current_page = 0;
            $more_page = false;
            $page_limit = env('PAGE_LIMIT');

            $data = Album::orderBy('id','DESC');  

            $total_rows = $data->count();
            $total_page = $page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request->page_no ?? 1;
            $offset = $current_page * $total_page - $total_page;
            $data->take($total_page)->offset($offset);

            $more_page = $this->common->more_page($current_page, $page_size);

            $data = $data->get();

            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

                for ($i=0; $i < count($data); $i++) {
                    $C_Ids = explode(',', $data[$i]['video_id']);

                    $data[$i]['video_name'] = $this->common->GetVidoeNameByIds($data[$i]['video_id']);


                    if(!empty($data[$i]['image'])){
                        
                        $path = $this->common->getImagePath($this->folder_album, $data[$i]['image']);
                        $data[$i]['image'] = $path;

                    }else{
                        $data[$i]['image'] = asset('/assets/imgs/no_img.png');
                    }
                }

                return $this->common->API_Response(200, __('api_msg.album_record_get'), $data, $pagination);

          

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function Get_Album_By_Video(Request $request)
    {
        try {

            $validation = Validator::make(
                $request->all(),
                [
                    'album_id' => 'required|numeric',
                ],
                [
                    'album_id.required' => __('api_msg.please_enter_required_fields'),
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

            $album_id = $request->album_id;
            $user_id = isset($request->user_id) ? $request->user_id : 0;
            $data = array();

            $page_size = 0;
            $current_page = 0;
            $more_page = false;
            $page_limit = env('PAGE_LIMIT');

            $result = album::where('id',$album_id)->get();

            if(!empty(sizeof($result))){
                foreach ($result as $key => $value) {
                    $C_Ids = explode(',', $value['video_id']);
                }
                $data = Video::whereIn('id', $C_Ids)->with('category','artist','user')->orderBy('created_at', 'desc');
    
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
    
                    $ra->image = $this->common->getImagePath($this->folder5, $ra->image);
    
                    if($ra->video_type =="server_video"){
                        $ra->url = $this->common->getImagePath($this->folder5, $ra->url);
    
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
                        $ra->profile_img = $this->common->getImagePath($this->folder, $ra->user['image']);
                    }
    
                    $dataarray[] = $ra;
                    unset($ra->category, $ra->user, $ra->artist);
                   
                }
    
                return $this->common->API_Response(200, __('api_msg.video_record_get'), $dataarray, $pagination); 
            }else{
                return $this->common->API_Response(200, __('api_msg.video_record_get')); 

            }

              

        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function Get_Notification(Request $request)
    {
        try{
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
                $errors = $validation->errors()->first('user_id');
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                }
                return $data;
            }

            $user_id = $request->user_id;
            $arr = [];

            $all_noti = Notification::all();
            $user_noti = User_Notification_Tracking::where('user_id', $user_id)->get();

            for ($i = 0; $i < count($user_noti); $i++) {
                $arr[] = $user_noti[$i]->notification_id;
            }

            $page_size = 0;
            $current_page = 0;
            $more_page = false;
            $page_limit = env('PAGE_LIMIT');

            $data = Notification::select('*')->where('user_id',$request->user_id)->whereNotIn('id', $arr)->orderBy('id', 'desc');
            $total_rows = $data->count();
            $total_page = $page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request->page_no ?? 1;
            $offset = $current_page * $total_page - $total_page;
            $data->take($total_page)->offset($offset);

            $more_page = $this->common->more_page($current_page, $page_size);

            $data = $data->get();

            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $return = [];
            foreach ($data as $key => $value) {

                if ($value['type'] == 3) {

                    $post_user = User::where('id', $user_id)->first();

                    $value['post_user_image'] = asset('/assets/imgs/users.png');
                    if (isset($post_user['id'])) {
                        $value['profile_image'] = $this->common->getImagePath($this->folder, $post_user['image']);
                    }

                } else {

                    $post_video = Video::where('id', $value['video_id'])->first();

                    $value['post_user_image'] = asset('/assets/imgs/no_img.png');;
                    if (isset($post_video['id'])) {
                        $value['profile_image'] = $this->common->getImagePath($this->folder5, $post_video['image']);
                    }
                }

                $value['full_name'] = "";
                $value['profile_img'] = asset('/assets/imgs/users.png');
                
                $return[] = $value;
            }

            return $this->common->API_Response(200, __('api_msg.notification_record_get'), $return, $pagination);
           


        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function Read_Notification(Request $request)
    {
        try{
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                    'notification_id' => 'required|numeric',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_required'),
                    'notification_id.required' => __('api_msg.notification_id_required'),
                ]
            );
            if ($validation->fails()) {
                $errors = $validation->errors()->first('user_id');
                $errors1 = $validation->errors()->first('notification_id');
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                } elseif ($errors1) {
                    $data['message'] = $errors1;
                }
                return $data;
            }

            $user_id =$request->user_id;
            $notification_id =$request->notification_id;

            $insert =new User_Notification_Tracking();
            $insert->user_id =$user_id;
            $insert->notification_id =$notification_id;

            if ($insert->save()) {

                return $this->common->API_Response(200, __('api_msg.notification_read'),array($insert));
            } else {
                return $this->common->API_Response(200, __('api_msg.notification_not_read'));
            }

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function Add_transaction(Request $request)
    {
        try{
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                    'package_id' => 'required|numeric',
                    'amount' => 'required|numeric',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_required'),
                    'package_id.required' => __('api_msg.package_id_required'),
                    'amount.required' => __('api_msg.amount_required'),
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->first('user_id');
                $errors1 = $validation->errors()->first('package_id');
                $errors2 = $validation->errors()->first('amount');
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

            $user_id =$request->user_id;
            $package_id =$request->package_id;
            $amount =$request->amount;
            
            $description = isset($request->description) ? $request->description : "";
            $payment_id = isset($request->payment_id) ? $request->payment_id : "";
            $currency_code = isset($request->currency_code) ? $request->currency_code : currency_code();

            $Pdata = Package::where('id',$package_id)->where('status','1')->first();
            if(!empty($Pdata)){
                $Edate =date("Y-m-d" ,strtotime("$Pdata->time $Pdata->type"));
            } else {
                return $this->common->API_Response(400, __('api_msg.please_enter_right_package_id'));
            }

            $insert = new Transaction();
            $insert->user_id =$user_id;
            $insert->package_id =$package_id;
            $insert->description = $description;
            $insert->amount = $amount;
            $insert->payment_id = $payment_id;
            $insert->currency_code = $currency_code;
            $insert->expiry_date = $Edate;
            $insert->status = '1';

            if($insert->save()){
                return $this->common->API_Response(200, __('api_msg.transaction_succesfully'));

            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_save'));
            }

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function get_like_video(Request $request)
    {
        try {
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

                $errors = $validation->errors()->first('user_id');
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                }
                return $data;
            }

            $user_id = isset($request['user_id']) ? $request['user_id'] : 0;

            $page_size = 0;
            $current_page = 0;
            $more_page = false;
            $page_limit = env('PAGE_LIMIT');
            
            $result = Like::select('video_id')->where('user_id', $user_id)->get();

            $videoarray = array();
            foreach ($result as $row) {
                $videoarray[] = $row->video_id;
            }
            $video_ids = implode(',', array_map('intval', $videoarray));

            $Ids = explode(',', $video_ids);

            $data = Video::whereIn('id', $Ids)->with('user','artist','category')->orderBy('created_at', 'desc');

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

                $data1 = $this->common->get_all_count_for_video($ra['id'], $user_id, $ra['user_id']);

                $ra = (object) array_merge((array) $ra, $data1);

                $ra->is_like = "0";
                if ($user_id != 0) {
                    $ra->is_like = $this->common->is_like($request['user_id'], $ra->id);
                }

                if($ra->video_type =="server_video"){
                    $ra->url = $this->common->getImagePath($this->folder5, $ra->url);

                }
                $ra->image = $this->common->getImagePath($this->folder5, $ra->image);

                $ra->category_name = "";
                if (isset($ra->category['name'])) {
                    $ra->category_name = $ra->category['name'];
                }

                $ra->artist_name = "";
                if (isset($ra->artist['name'])) {
                    $ra->artist_name = $ra->artist['name'];
                }

               
                $ra->full_name = "";
                $ra->user_name = "";
                $ra->profile_img = asset('/assets/imgs/users.png');
                if (isset($ra->user)) {
                    $ra->full_name = $ra->user['full_name'];
                    $ra->user_name = $ra->user['user_name'];
                    $ra->profile_img = $this->common->getImagePath($this->folder, $ra->user['image']);
                }

                $dataarray[] = $ra;
                unset($ra->category, $ra->user, $ra->artist);
            }

            return $this->common->API_Response(200, __('api_msg.get_record_successfully'), $dataarray, $pagination);

        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function get_comment_video(Request $request)
    {
        try {
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

                $errors = $validation->errors()->first('user_id');
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                }
                return $data;
            }

            $user_id = isset($request['user_id']) ? $request['user_id'] : 0;

            $page_size = 0;
            $current_page = 0;
            $more_page = false;
            $page_limit = env('PAGE_LIMIT');

            $result = Comment::select('video_id')->where('user_id', $user_id)->get();

            $videoarray = array();
            foreach ($result as $row) {
                $videoarray[] = $row->video_id;
            }
            $video_ids = implode(',', array_map('intval', $videoarray));

            $Ids = explode(',', $video_ids);

            $data = Video::whereIn('id', $Ids)->with('user','category','artist')->orderBy('created_at', 'desc');

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

                $data1 = $this->common->get_all_count_for_video($ra['id'], $user_id, $ra['user_id']);

                $ra = (object) array_merge((array) $ra, $data1);

                $ra->is_like = "0";
                if ($user_id != 0) {
                    $ra->is_like = $this->common->is_like($request['user_id'], $ra->id);
                }

                $ra->image = $this->common->getImagePath($this->folder5, $ra->image);

                if($ra->video_type =="server_video"){
                    $ra->url = $this->common->getImagePath($this->folder5, $ra->url);

                }

                $ra->category_name = "";
                if (isset($ra->category['name'])) {
                    $ra->category_name = $ra->category['name'];
                }

                $ra->artist_name = "";
                if (isset($ra->artist['name'])) {
                    $ra->artist_name = $ra->artist['name'];
                }


                $ra->full_name = "";
                $ra->user_name = "";
                $ra->profile_img = asset('/assets/imgs/users.png');
                if (isset($ra->user)) {
                    $ra->full_name = $ra->user['full_name'];
                    $ra->user_name = $ra->user['user_name'];
                    $ra->profile_img = $this->common->getImagePath($this->folder, $ra->user['image']);
                }

                $dataarray[] = $ra;
                unset($ra->category, $ra->user, $ra->artist);
            }

            return $this->common->API_Response(200, __('api_msg.get_record_successfully'), $dataarray, $pagination);

        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function uploadAudio(Request $request)
    {
        try{
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                    'name' => 'required',
                    'artist_id' => 'required|numeric',
                    'category_id' => 'required|numeric',
                    'is_paid' => 'required|numeric',
                    'audio' => 'required',
                    'image' => 'required',
                    'description' => 'required',
                ]
            );
            if ($validation->fails()) {
                $errors = $validation->errors()->first();
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                }
                return $data;
            }

            $params = $request->all();
            if (isset($requestData['image'])) {
                $files = $requestData['image'];
                $params['image'] = $this->common->saveImage($files, $this->folder);
            }


            $audioFile = $request->file('audio');
            $fileName = time() . '.' . $audioFile->getClientOriginalExtension();
            $audioFile->move(public_path('audio'), $fileName);

            $params['audio'] = $fileName;
            //$requestData['user_id'] =isset($requestData['user_id']) ? $requestData['user_id'] : 0;

            //$requestData['v_view'] =0;
            //echo "<pre>";print_r($params);exit;
            $video_data = Audio::updateOrCreate(['id' => ''], $params);

            return $this->common->API_Response(200, 'record Get succcesfully', '', '');
           


        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    
    }

    public function ai_audio_book_list(Request $request)
    {
        try{
            $user_id = isset($request['user_id']) ? $request['user_id'] : 0;

            $page_size = 0;
            $current_page = 0;
            $more_page = false;
            $page_limit = env('PAGE_LIMIT');
            
            $data = Audio::with('category','artist','user')->where('is_aiaudiobook', 1)->where('is_approved', 1)->latest();
            
            $total_rows = $data->count();
            $total_page = $page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request->page_no ?? 1;
            $offset = $current_page * $total_page - $total_page;
            $data->take($total_page)->offset($offset);

            $more_page = $this->common->more_page($current_page, $page_size);

            $data = $data->get()->toArray();
            //echo "<pre>";print_r($data);exit;

            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $dataarray = [];
            foreach ($data as $ra) {

                $ra['audio'] = url('audio').'/'.$ra['audio'];
                $data1 = $this->common->get_all_count_for_video($ra['id'], $user_id, $ra['user_id']);
                $ra = (object) array_merge((array) $ra, $data1);

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
                }

               
                $ra->full_name = "";
                $ra->user_name = "";
                $ra->profile_img = asset('/assets/imgs/users.png');
                if (isset($ra->user)) {
                    $ra->full_name = $ra->user['full_name'];
                    $ra->user_name = $ra->user['user_name'];
                    $ra->profile_img = $this->common->getImagePath($this->folder, $ra->user['image']);
                }

                $dataarray[] = $ra;
                unset($ra->category, $ra->user, $ra->artist);
            }

            return $this->common->API_Response(200, 'AI Audio book list get succcesfully', $dataarray, $pagination);

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function get_category_list(Request $request)
    {
        try{
            $data = Category::orderBy('id','DESC'); 
            $data = $data->get()->toArray();            
            if(!empty($data)){
                // Image Name to URL
                foreach ($data as $o=>$record) {
                    $data[$o]['image'] = $this->common->getImagePath($this->folder2, $record['image']);
                }  
            } 
            return $this->common->API_Response(200, __('api_msg.category_record_get'), $data);

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    
    public function get_artist_list(Request $request)
    {
        try{
            $data = Artist::orderBy('id','DESC'); 
            $data = $data->get()->toArray();
            if(!empty($data)){
                // Image Name to URL
                foreach ($data as $o=>$record) {
                    $data[$o]['image'] = $this->common->getImagePath($this->folder_artist, $record['image']);
                }  
            }    
            return $this->common->API_Response(200, __('api_msg.artist_record_get'), $data);
        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    
    // Upload Video API :: 02-10-2023
    public function uploadVideo(Request $request)
    {
        try{
            if($request->video_type == "server_video"){
                $validation = Validator::make(
                    $request->all(),
                    [
                        'user_id' => 'required|numeric',
                        'name' => 'required',
                        'artist_id' => 'required|numeric',
                        'category_id' => 'required|numeric',
                        'is_paid' => 'required|numeric',
                        'video' => 'required|mimes:mp4,mov,avi|max:10240', // Max size in kilobytes (10 MB)
                        'image' => 'required',
                        'description' => 'required',
                    ]
                );
            }else{
                $validator = Validator::make($request->all(), [
                    'user_id' => 'required|numeric',
                    'name' => 'required',
                    'artist_id' => 'required',
                    'category_id' => 'required',
                    'is_paid' => 'required|numeric',
                    'url' => 'required',
                    'description' => 'required',
                    'image' => 'required',
                ]);
            }

            if ($validation->fails()) {
                $errors = $validation->errors()->first();
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                }
                return $data;
            }
            
            $requestData = $request->all();
            //echo "<pre>";print_r($requestData);exit;
            if (isset($requestData['image'])) {
                $files = $requestData['image'];
                $requestData['image'] = $this->common->saveImage($files, $this->folder5);
            }

            if($requestData['video_type'] == "server_video") {
                $videoFile = $request->file('video');
                $VideofileName = time() . '_' . $videoFile->getClientOriginalName();
                $videoFile->move(public_path('video'), $VideofileName);
                $requestData['url'] = $VideofileName;
            }

            if($requestData['video_type'] == "url" || $requestData['video_type'] == "youtube" || $requestData['video_type'] == "vimeo"){
                $requestData['url'] = $requestData['url'];
            }
            $requestData['id'] = isset($requestData['id'])?$requestData['id']:0;
            $requestData['user_id'] =isset($requestData['user_id']) ? $requestData['user_id'] : 0;
            $requestData['v_view'] =1;
            
            //echo "<pre>";print_r($params);exit;
            $video_data = Video::updateOrCreate(['id' => $requestData['id']], $requestData);
            if(isset($video_data->id)){
                return $this->common->API_Response(200, 'Video uploaded successfully', '', '');
            }else{
                return $this->common->API_Response(401, 'Something went wrong to upload video', '', '');
            }
        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    
}

