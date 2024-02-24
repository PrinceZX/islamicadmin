<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Common;
use App\Models\Video;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Storage;
use Validator;

class CategoryController extends Controller
{
    
    private $folder = "category";
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
                $data = Category::select('*')->latest()->get();

                $this->common->imageNameToUrl($data, 'image', $this->folder);

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $delete = ' <form onsubmit="return confirm(\'Are you sure want to delete this data ?\');" method="POST"  action="' . route('category.destroy', [$row->id]) . '">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn "><img src="' . asset('assets/imgs/trash.png') . '" /></button></form>';

                        $btn = '<div class="d-flex justify-content-around"><a class="btn" href="' . route('category.edit', [$row->id]) . '">';
                        $btn .= '<img src="' . asset('assets/imgs/edit.png') . '" />';
                        $btn .= '</a>';
                        $btn .= $delete;
                        $btn .= '</a></div>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('admin.category.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

   
    public function create()
    {
        try {
            $params['data'] = [];
            return view('admin.category.add', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    
    public function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();

            if (isset($requestData['image'])) {
                $files = $requestData['image'];
                $requestData['image'] = $this->common->saveImage($files, $this->folder);
            }
            $requestData['status'] = 1;

            $category_data = Category::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($category_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('label.category_save')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.category_not_save')));
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
            $params['data'] = Category::where('id', $id)->first();

            $this->common->imageNameToUrl(array($params['data']), 'image', $this->folder);

            if ($params['data'] != null) {
                return view('admin.category.edit', $params);
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
                'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();
         
            if (isset($requestData['image'])) {
                $files = $requestData['image'];
                $requestData['image'] = $this->common->saveImage($files, $this->folder);

                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_image']));
            }
            $requestData = Arr::except($requestData, ['old_image']);

            $category_data = Category::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($category_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('label.category_update')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.category_not_update')));
            }

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

   
    public function destroy($id)
    {
        try{
            $data =Category::where('id',$id)->first();
            $video = Video::where('category_id',$id)->first();

            if($video !==null) {
                return back()->with('error', __('label.use_someone_category'));
            }else{
                if (isset($data)) {
                    $this->common->deleteImageToFolder($this->folder, $data['image']);
                    $data->delete();
                }

                return redirect()->back()->with('success', __('label.category_delete'));

            }
            

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
