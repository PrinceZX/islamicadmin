<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Common;
use App\Models\Video;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Storage;
use Validator;
;

class AlbumController extends Controller
{
    private $folder = "album";
    public $common;

    public function __construct()
    {
        $this->common = new Common;
    }
  
    public function index(Request $request)
    {
        try {
            $params['data'] = [];
            if ($request->ajax()) {
                $data = Album::select('*')->latest()->get();

                for($i=0; $i<count($data); $i++){
                    $id =explode(",",$data[$i]['video_id']);
                        $video =Video::select('*')->whereIn('id',$id)->get();
                        if(!empty(sizeof($video))){
                            foreach ($video as $key => $value) {
                                $IDs = implode(",", (array)$value['name']);
                            }
                            $data[$i]['video_name'] =$IDs;
                        }else{
                            $data[$i]['video_name'] ="";
                        }
                }
                
                $this->common->imageNameToUrl($data, 'image', $this->folder);

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $delete = ' <form onsubmit="return confirm(\'Are you sure want to delete this data ?\');" method="POST"  action="' . route('album.destroy', [$row->id]) . '">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn "><img src="' . asset('assets/imgs/trash.png') . '" /></button></form>';

                        $btn = '<div class="d-flex justify-content-around"><a class="btn" href="' . route('album.edit', [$row->id]) . '">';
                        $btn .= '<img src="' . asset('assets/imgs/edit.png') . '" />';
                        $btn .= '</a>';
                        $btn .= $delete;
                        $btn .= '</a></div>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('admin.album.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

   
    public function create()
    {
        try {
            $params['data'] = [];

            $video =Video::select('*')->get();
            return view('admin.album.add', $params ,['video'=>$video]);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

   
    public function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2',
                'video_id' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();


            $video_id = implode(',', $requestData['video_id']);

            if (isset($requestData['image'])) {
                $files = $requestData['image'];
                $requestData['image'] = $this->common->saveImage($files, $this->folder);
            }
            $requestData['video_id'] =$video_id;

            $album_data = Album::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($album_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('label.album_save')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.album_not_save')));
            }


        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    
    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        try {
            $params['data'] = Album::where('id', $id)->first();

            $video =Video::select('*')->get();

            $this->common->imageNameToUrl(array($params['data']), 'image', $this->folder);

            if ($params['data'] != null) {
                return view('admin.album.edit', $params ,['video'=>$video]);
            } else {
                return redirect()->back()->with('error', __('label.page_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    
    public function update(Request $request, $id)
    {
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2',
                'video_id' => 'required',
            ]);

            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();


            $video_id = implode(',', $requestData['video_id']);

            if (isset($requestData['image'])) {
                $files = $requestData['image'];
                $requestData['image'] = $this->common->saveImage($files, $this->folder);
            }

            $requestData['video_id'] =$video_id;

            $album_data = Album::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($album_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('label.album_update')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.album_not_update')));
            }


        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function destroy($id)
    {
        try{
            $data =Album::where('id',$id)->first();

                if (isset($data)) {
                    $this->common->deleteImageToFolder($this->folder, $data['image']);
                    $data->delete();
                }

                return redirect()->back()->with('success', __('label.album_delete'));

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
