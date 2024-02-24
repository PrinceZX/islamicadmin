<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\User;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Storage;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;



class UserController extends Controller
{
    private $folder = "user";
    public $common;

    public function __construct()
    {
        $this->common = new Common;
    }
    
    public function index(Request $request)
    {
        try{
            $params['data'] = [];
            if($request->ajax()) {
                $data =User::select('*')->latest()->get();
                
                $this->common->imageNameToUrl($data, 'image', $this->folder);
                
                return DataTables()::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $delete = ' <form onsubmit="return confirm(\'Are you sure want to delete this data ?\');" method="POST"  action="' . route('user.destroy', [$row->id]) . '">
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn "><img src="' . asset('assets/imgs/trash.png') . '" /></button></form>';

                    $btn = '<div class="d-flex justify-content-around"><a class="btn float-xl-left" href="' . route('user.edit', [$row->id]) . '">';
                    $btn .= '<img src="' . asset('assets/imgs/edit.png') . '" />';
                    $btn .= '</a>';
                    $btn .= $delete;
                    $btn .= '</a></div>';
                    return $btn;
                })
                ->addColumn('date', function($row) {
                    $date = date("Y-m-d", strtotime($row->created_at));
                    return $date;
                })
                ->rawColumns(['action'])
                ->make(true);
            }
            
        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
        return view('admin.user.index');
    }

   
    public function create()
    {
        try{
            $params['data'] = [];
            
            return view('admin.user.add',$params);
        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

   
    public function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'full_name' => 'required|min:2',
                'mobile_number' => 'required',
                'email' => 'required|unique:tbl_user|email',
                'password' => 'required|min:4',
                'date_of_birth' => 'required',
                'gender' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'bio' => 'required',
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

            $user_name = rand(0, 1000);
            $email_array = explode('@', $request->email);
            $requestData['user_name'] = '@' . $email_array[0] . '_' . $user_name;
            $requestData['password'] = Hash::make($requestData['password']);
            $requestData['country_code'] = "";
            $requestData['device_token'] = "";
            $requestData['device_type'] = 0;
            $requestData['type'] = 3;

            $user_data = User::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($user_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('label.user_save')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.user_not_save')));
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
        try{
            $params['data'] = User::where('id', $id)->first();

            $this->common->imageNameToUrl(array($params['data']), 'image', $this->folder);


            if ($params['data'] != null) {
                return view('admin.user.edit', $params);
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
            $validator = Validator::make($request->all(), [
                'full_name' => 'required|min:2',
                'mobile_number' => 'required',
                'email' => 'required|email|unique:tbl_user,email,' . $id,
                'image' => 'image|mimes:jpeg,png,jpg|max:2048',
                'date_of_birth' => 'required',
                'gender' => 'required',
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

            $requestData['bio'] =isset($_REQUEST['bio']) ? $_REQUEST['bio'] : '';

            $User_data = User::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($User_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('label.user_update')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.user_not_update')));
            }

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

 
    public function destroy($id)
    {
        try{
            $data = User::where('id', $id)->first();
            if (isset($data)) {
                $this->common->deleteImageToFolder($this->folder, $data['image']);
                $data->delete();
            }
            return redirect()->back()->with('success', __('label.user_delete'));

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function searchUser(Request $request)
    {
        $name = $request->name;
        $user = User::orWhere('full_name', 'like', '%' . $name . '%')->orWhere('mobile_number', 'like', '%' . $name . '%')->orWhere('email', 'like', '%' . $name . '%')->get();

        $url = (strpos(url()->previous(), 'aiaudio_transaction') !== false) ? url('admin/aiaudio_transaction/create?user_id') : url('admin/transaction/create?user_id');
        $text = '<table width="100%" class="table table-striped category-table text-center table-bordered"><tr style="background: #F9FAFF;"><th>Full Name</th><th>Mobile</th><th>Email</th><th>Action</th></tr>';
        if ($user->count() > 0) {
            foreach ($user as $row) {

                $a = '<a class="btn-link" href="' . $url . '=' . $row->id . '">Select</a>';
                $text .= '<tr><td>' . $row->full_name . '</td><td>' . $row->mobile_number . '</td><td>' . $row->email . '</td><td>' . $a . '</td></tr>';
            }
        } else {
            $text .= '<tr><td colspan="4">User Not Found</td></tr>';
        }
        $text .= '</table>';

        return response()->json(array('status' => 200, 'success' => 'Search User', 'result' => $text));
    }
    
}
