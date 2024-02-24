<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Category;
use App\Models\Video;
use App\Models\Artist;
use App\Models\Album;
use App\Models\User;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Storage;
use Validator;

class VideoController extends Controller
{
    private $folder ="video";
    private $folder_category ="category";
    private $folder_artist ="artist";

    public $common;

    public function __construct()
    {
        $this->common = new Common;
    }

    
    public function index(Request $request)
    {
        
        try{
            $data = Video::select('*')->with('artist')->latest()->paginate(7);

            $this->common->imageNameToUrl($data, 'image', $this->folder);

            foreach ($data as $key => $value) {
                $userdetails =User::where('id',$value['user_id'])->first();
                if($userdetails){
                    $data[$key]['username'] = $userdetails['full_name'];
                }
                if ($value['video_type'] == "server_video") {
                    $this->common->imageNameToUrl(array($value), 'url', $this->folder);
                }
            }
            $params['data'] = $data;
            
            return view('admin.video.index',$params);
        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }

    }

   
    public function create()
    {
        $category = Category::select('*')->get();
        $artist =Artist::select('*')->get();
        $user =User::select('*')->get();

        return view('admin.video.add',['category' =>$category,'artist'=>$artist,'user'=>$user]);
    }

  
    public function store(Request $request)
    {
        try{
            if($request->video_type == "server_video"){
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'artist_id' => 'required',
                    'category_id' => 'required',
                    'mp3_file_name' => 'required',
                    'description' => 'required',
                    'image' => 'required',
    
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'artist_id' => 'required',
                    'category_id' => 'required',
                    'url' => 'required',
                    'description' => 'required',
                    'image' => 'required',
    
                ]);
            }
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();

            if (isset($requestData['image'])) {
                $files = $requestData['image'];
                $requestData['image'] = $this->common->saveImage($files, $this->folder);
            }

            if($requestData['video_type'] == "server_video") {
                if ($requestData['mp3_file_name'] != null) {
                    $requestData['url'] = $requestData['mp3_file_name'];
                }
            }

            if($requestData['video_type'] == "url" || $requestData['video_type'] == "youtube" || $requestData['video_type'] == "vimeo"){
                $requestData['url'] =$requestData['url'];
            }

            $requestData['user_id'] =isset($requestData['user_id']) ? $requestData['user_id'] : 0;

            $requestData['is_created_by_admin'] =1;
            $requestData['is_approved'] =1;
            $video_data = Video::updateOrCreate(['id' => $requestData['id']], $requestData);

            if(isset($video_data->id)){

                // Send Notification  
                $imageURL= $this->common->imageNameToUrl(array($video_data), 'image', $this->folder);
                $noti_array = array(
                    'id' =>$video_data->id,
                    'name' =>$video_data->name,
                    'image' =>$imageURL,
                    'video_type' =>$video_data->video_type,
                    'description' =>string_cut($video_data->description, 90),
                );

                $this->common->sendnotification($noti_array);
                return response()->json(array('status' => 200, 'success' => __('label.video_save')));
            }else{
                return response()->json(array('status' => 400, 'errors' => __('label.video_not_save')));
            }


        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function detail(Request $request,$id)
    {
        try{
            $detail =Video::where('id',$id)->first();

            $category =Category::where('id',$detail->category_id)->first();
            $artist=Artist::where('id',$detail->artist_id)->first();
            $user =User::where('id',$detail->user_id)->first();
            
            $this->common->imageNameToUrl(array($detail), 'image', $this->folder);

            if($detail->video_type =="server_video"){
                $this->common->imageNameToUrl(array($detail), 'url', $this->folder);

            }

            return view('admin.video.detail',['detail'=>$detail,'category'=>$category,'artist'=>$artist,'user'=>$user]);

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }

    }
   

    public function edit($id)
    {
        try{
            $params['data'] =Video::where('id',$id)->first();
            $params['category'] = Category::select('*')->get();
            $params['artist'] = Artist::select('*')->get();
            $params['user'] = User::select('*')->get();
    
            $this->common->imageNameToUrl(array($params['data']), 'image', $this->folder);
            
            
            if($params['data']->video_type == "server_video"){
                $this->common->imageNameToUrl(array($params['data']), 'url', $this->folder);
            }
    
            if ($params['data'] != null) {
                return view('admin.video.edit', $params);
            } else {
                return redirect()->back()->with('error', __('label.page_not_found'));
            }

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

   
    public function update(Request $request, $id)
    {
        try{
            if($request->video_type == "server_video"){
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'artist_id' => 'required',
                    'category_id' => 'required',
                    'description' => 'required',
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'artist_id' => 'required',
                    'category_id' => 'required',
                    'url' => 'required',
                    'description' => 'required',
                ]);
            }
           
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();

           
            if (isset($requestData['image'])) {
                $files = $requestData['image'];
                $requestData['image'] = $this->common->saveImage($files, $this->folder);
                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_thumbnail']));
            }
            $requestData = Arr::except($requestData, ['old_thumbnail']);

            

            if($requestData['video_type'] =="server_video"){
                if ($requestData['old_video_url'] != null) {
                    if(isset($request->video_url)){
                        $requestData['url'] = $request->video_url;
                        $this->common->deleteImageToFolder($this->folder, basename($requestData['old_video_url']));
                    } else {
                        $requestData['url'] = basename($requestData['old_video_url']);
                    }
                    
                }
            }else{
                $requestData['url'] =$request->url;
                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_video_url']));
            }

            $requestData = Arr::except($requestData, ['old_video_url']);

            $requestData['user_id'] =isset($requestData['user_id']) ? $requestData['user_id'] : 0;

            $video_data = Video::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($video_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('label.video_update')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.video_not_update')));
            }

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function show($id)
    {
        try{
            $data =Video::where('id',$id)->first();
            if (isset($data)) {
                
                if($data['video_type'] =="server_video"){
                    $this->common->deleteImageToFolder($this->folder, $data['url']);
                }

                $this->common->deleteImageToFolder($this->folder, $data['image']);
                $data->delete();

               
               
            }
            return redirect()->back()->with('success', __('label.video_delete'));

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    } 

    public function saveChunk()
    {

        @set_time_limit(5 * 60);

        $targetDir = base_path('storage/app/public/video');

        //$targetDir = 'uploads';

        $cleanupTargetDir = true; // Remove old files

        $maxFileAge = 5 * 3600; // Temp file age in seconds

        // Create target dir
        if (!file_exists($targetDir)) {
            @mkdir($targetDir);
        }

        // Get a file name
        if (isset($_REQUEST["name"])) {
            $fileName = $_REQUEST["name"];
        } elseif (!empty($_FILES)) {
            $fileName = $_FILES["file"]["name"];
        } else {
            $fileName = uniqid("file_");
        }
        $category_image = $fileName;
        $filePath = $targetDir . DIRECTORY_SEPARATOR . $category_image;
        // Chunking might be enabled

        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
        // Remove old temp files

        if ($cleanupTargetDir) {
            if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
            }

            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;
                // If temp file is current file proceed to the next
                if ($tmpfilePath == "{$filePath}.part") {
                    continue;
                }

                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }

        // Open temp file

        if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }

        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
            }

            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        }

        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }

        @fclose($out);
        @fclose($in);
        // Check if file has been uploaded
        if (!$chunks || $chunk == $chunks - 1) {
            // Strip the temp .part suffix off
            rename("{$filePath}.part", $filePath);
        }
        // Return Success JSON-RPC response
        die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
    }

    public function approvevideo(Request $request){
        try{
            $requestData = $request->all();
            $videoid = isset($requestData['videoid'])?$requestData['videoid']:'';
            $type = isset($requestData['type'])?$requestData['type']:0;
            if($videoid > 0){
                $prams = array();
                $prams['is_approved'] = $type;
                $result = Video::updateOrCreate(['id' => $videoid], $prams);
                if (isset($result->id)) {
                    return response()->json(array('status' => 200, 'success' => 'Approved Successfully.'));
                } else {
                    return response()->json(array('status' => 400, 'errors' => 'Something went wrong.'));
                }
            }else{
                return response()->json(array('status' => 400, 'errors' => 'Something went wrong.'));
            }
        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
