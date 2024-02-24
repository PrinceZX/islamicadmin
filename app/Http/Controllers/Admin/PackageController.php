<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Package;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Storage;
use Validator;

class PackageController extends Controller
{
    
    private $folder = "package";
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
                $data = Package::select('*')->latest()->get();

                $this->common->imageNameToUrl($data, 'image', $this->folder);


                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $delete = ' <form onsubmit="return confirm(\'Are you sure want to delete this data ?\');" method="POST"  action="' . route('package.destroy', [$row->id]) . '">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn "><img src="' . asset('assets/imgs/trash.png') . '" /></button></form>';

                        $btn = '<div class="d-flex justify-content-around"><a class="btn float-xl-left" href="' . route('package.edit', [$row->id]) . '">';
                        $btn .= '<img src="' . asset('assets/imgs/edit.png') . '" />';
                        $btn .= '</a>';
                        $btn .= $delete;
                        $btn .= '</a></div>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('admin.package.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

   
    public function create()
    {
        try {
            $params['data'] = [];
            return view('admin.package.add', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

   
    public function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'price' => 'required',
                'type' => 'required',
                'time' => 'required',
                'android_product_package' => 'required',
                'ios_product_package' => 'required',
            ]);

            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();

            if (isset($requestData['image']) && $requestData['image'] != 'undefined') {
                $files = $requestData['image'];
                $requestData['image'] = $this->common->saveImage($files, $this->folder);
            } else {
                $requestData['image'] = "";

            }

            $requestData['currency_type'] = currency_code();
            $requestData['status'] =1;

            $package_data = Package::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($package_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('label.package_save')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.package_not_save')));
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
            $params['data'] = Package::where('id', $id)->first();

            $this->common->imageNameToUrl(array($params['data']), 'image', $this->folder);

            return view('admin.package.edit', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'price' => 'required',
                'type' => 'required',
                'time' => 'required',
                'android_product_package' => 'required',
                'ios_product_package' => 'required',
            ]);

            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();

            if (isset($requestData['image']) && $requestData['image'] != 'undefined') {
                $files = $requestData['image'];
                $requestData['image'] = $this->common->saveImage($files, $this->folder);
                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_image']));
            } else {
                $requestData['image'] = "";

            }

            $requestData = Arr::except($requestData, ['old_image']);

            $requestData['currency_type'] = currency_code();
            $requestData['status'] =1;
           
            $package_data = Package::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($package_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('label.package_update')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.package_not_update')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

  
    public function destroy($id)
    {
        try {
            $data = Package::where('id', $id)->first();
            if (isset($data)){
                $this->common->deleteImageToFolder($this->folder, $data['image']);
                $data->delete();
            }
            return redirect()->route('package.index')->with('success', __('label.package_delete'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
