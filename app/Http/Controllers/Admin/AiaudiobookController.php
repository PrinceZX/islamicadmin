<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use Illuminate\Http\Request;
use App\Models\Audio;
use App\Models\Category;
use App\Models\Artist;
use App\Models\Album;
use App\Models\User;
use DataTables;
use Illuminate\Support\Arr;
use Storage;
use Validator;
class AiaudiobookController extends Controller
{
    private $folder ="audio";
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
            $data = Audio::select('*')->with('artist')->where('is_aiaudiobook', '!=', 0)->latest()->paginate(7);  
            if(!empty($data)){
                foreach($data as $k=>$record){
                    $this->common->imageNameToUrl(array($record), 'image', $this->folder);
                }
            }
            $params['data'] = $data;
            //echo "<pre>";print_r($params);exit;
            return view('admin.aiaudiobook.index',$params);
        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }

    }

    public function create()
    {
        $category = Category::select('*')->get();
        $artist =Artist::select('*')->get();
        $user =User::select('*')->get();

        return view('admin.aiaudiobook.add',['category' =>$category,'artist'=>$artist,'user'=>$user]);
    }

    public function store(Request $request)
    {
        try{
            $requestData = $request->all();
            if($request->isAudioTab == 1){
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'artist_id' => 'required',
                    'category_id' => 'required',
                    'description' => 'required',    
                    'audio' => 'required',   
                ]);
            }else{
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'artist_id' => 'required',
                    'category_id' => 'required',
                    'description' => 'required',    
                    'audio' => [
                        'required',
                        'file',
                        'max:10000', // Set a reasonable maximum file size (in kilobytes)
                        'mimes:mpga,wav,mp3',
                    ],   
                ]);
            }
            
          
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }
            
            if (isset($requestData['image'])) {
                $files = $requestData['image'];
                $requestData['image'] = $this->common->saveImage($files, $this->folder);
            }

            if($request->isAudioTab == 0){
                $audioFile = $request->file('audio');
                $fileName = time() . '.' . $audioFile->getClientOriginalExtension();
                $audioFile->move(public_path('audio'), $fileName);
                $requestData['audio'] = $fileName;
            }

            $requestData['user_id'] =isset($requestData['user_id']) ? $requestData['user_id'] : 0;

            $requestData['is_created_by_admin'] =1;
            $requestData['is_aiaudiobook'] = 1;
            //echo "<pre>";print_r($requestData);exit;
            $audio_data = Audio::updateOrCreate(['id' => $requestData['id']], $requestData);
            if(isset($audio_data->id)){
                return response()->json(array('status' => 200, 'success' => __('label.aiaudio_save')));
            }else{
                return response()->json(array('status' => 400, 'errors' => __('label.aiaudio_not_save')));
            }
        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }


    public function edit($id)
    {
        try{
            $params['data'] =Audio::where('id',$id)->first();
            $params['category'] = Category::select('*')->get();
            $params['artist'] = Artist::select('*')->get();
            $params['user'] = User::select('*')->get();
    
            $this->common->imageNameToUrl(array($params['data']), 'image', $this->folder);
            //echo "<pre>";print_r($params);exit;
            if ($params['data'] != null) {
                return view('admin.aiaudiobook.edit', $params);
            } else {
                return redirect()->back()->with('error', __('label.page_not_found'));
            }

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function show($id)
    {
        try{
            $data =Audio::where('id',$id)->first();
            if (isset($data)) {
                $data->delete();
            }
            return redirect()->back()->with('success', __('label.aiaudio_delete'));

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    } 

    public function update(Request $request, $id)
    {
        try{
            $requestData = $request->all();
            if($request->isAudioTab == 0){
                if ($request->hasFile('audio') && $request->file('audio')->isValid()) {
                    $audioFile = $request->file('audio');
                    $fileName = time() . '.' . $audioFile->getClientOriginalExtension();
                    $audioFile->move(public_path('audio'), $fileName);
                    $requestData['audio'] = $fileName;
                }else{
                    $AudioData = Audio::where('id',$id)->first();
                    if($AudioData['isAudioTab'] == 1){
                        return response()->json(array('status' => 400, 'errors' => 'Audio is required.'));
                    }else{
                        $requestData['audio'] = isset($AudioData['audio'])?$AudioData['audio']:'';
                    }
                }

                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'artist_id' => 'required',
                    'category_id' => 'required',
                    'description' => 'required',      
                ]);
            }else{
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'artist_id' => 'required',
                    'category_id' => 'required',
                    'description' => 'required',    
                    'audio' => 'required',   
                ]);
            }
           
           
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            if (isset($requestData['image'])) {
                $files = $requestData['image'];
                $requestData['image'] = $this->common->saveImage($files, $this->folder);
            }


            $requestData['user_id'] =isset($requestData['user_id']) ? $requestData['user_id'] : 0;

            $video_data = Audio::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($video_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('label.aiaudio_update')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.aiaudio_not_update')));
            }

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function getconvertedaudio(Request $request)
    {
        // Your custom logic here
        try{
            $requestData = $request->all();
           // $requestData = json_decode( $requestData);
           $voice_name = isset($requestData['voice_name'])?$requestData['voice_name']:'';
           $text = isset($requestData['text'])?$requestData['text']:'';
            // The URL of the HTTP API endpoint
            $apiUrl = 'http://85.31.234.223:5000/text';

            // JSON data to send
            $data = [
                'voice_name' => $voice_name,
                'text' => $text,
                // Add more key-value pairs as needed
            ];

            // Convert the data array to JSON
            $jsonData = json_encode($data);

            // Create a new cURL resource
            $ch = curl_init();

            // Set cURL options
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true); // Set the request method to POST
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData); // Set the JSON payload
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json', // Specify JSON content type
                'Content-Length: ' . strlen($jsonData) // Set the content length
            ]);

            // Execute cURL session and store the response
            $response = curl_exec($ch);

            // Check for cURL errors
            if (curl_errno($ch)) {
                return response()->json(array('status' => 400, 'errors' => curl_error($ch)));
            }

            // Close cURL session
            curl_close($ch);

            // Process the API response (e.g., print it)
            $filename = 'audio_file_'.time().'.mp3';
            $file = fopen(public_path('audio').'/'.$filename, 'w');
            fwrite($file, $response);
            fclose($file);

            return response()->json(array('status' => 200, 'filename' => $filename));
           
        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function detail(Request $request,$id)
    {
        try{
            $detail =Audio::where('id',$id)->where('is_aiaudiobook', '!=', 0)->first();

            $category =Category::where('id',$detail->category_id)->first();
            $artist=Artist::where('id',$detail->artist_id)->first();
            $user =User::where('id',$detail->user_id)->first();
            
            $this->common->imageNameToUrl(array($detail), 'image', $this->folder);

            if($detail->video_type =="server_video"){
                $this->common->imageNameToUrl(array($detail), 'url', $this->folder);

            }

            return view('admin.aiaudiobook.detail',['detail'=>$detail,'category'=>$category,'artist'=>$artist,'user'=>$user]);

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }

    }
}
