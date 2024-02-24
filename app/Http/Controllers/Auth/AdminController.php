<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Validator;
use URL;



class AdminController extends Controller
{
    protected $redirectTo = 'admin/login';

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function get_login()
    {
        try{
            return view('auth.login');

        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }

    }

    public function post_login(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|min:4',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData =$request->all();
            if($token =Auth()->guard('admin')->attempt(['email' =>$requestData['email'], 'password' =>$requestData['password']])) {
                $user =auth()->guard('admin')->user();

                if($user->type ==1) {
                    $this->middleware('checkadmin');
                }
                return response()->json(array('status' => 200, 'success' => __('label.success_login')));

            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.error_login')));

            }
            
        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function logout()
    {
        try{
            Auth()->guard('admin')->logout();
            return redirect(route('admin.index'))->with('success', __('label.logout_success'));
        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    // Sign Up Page Functionality
    public function get_signup()
    {
        try{
            return view('auth.signup');

        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function post_signup(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|min:4',
                'user_name' => 'required',
            ]);

            
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }
            
            if(isset($request->user_name)){
                $username = Admin::where('user_name',$request->user_name)->first();
                $email    = Admin::where('email',$request->email)->first();
                if($username){
                    $errs[] = "This user name is already exist.";
                    return response()->json(array('status' => 400, 'errors' => $errs));
                }elseif($email){
                    $errs[] = "This email is already exist.";
                    return response()->json(array('status' => 400, 'errors' => $errs));
                }else{
                    Admin::insert([
                        'user_name' => $request->user_name,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                        'type' => 1
                    ]);
                    return response()->json(array('status' => 200, 'success' => 'Succesfully User Created.'));
                }
            }
            
        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function index(Request $request){
        try{
            $params['data'] = [];
            if($request->ajax()) {
                $data =Admin::select('*')->latest()->get();
                
                return DataTables()::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $delete = ' <form onsubmit="return confirm(\'Are you sure want to delete this data ?\');" method="POST"  action="' . route('admins.destroy', [$row->id]) . '">
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn "><img src="' . asset('assets/imgs/trash.png') . '" /></button></form>';

                    $btn = '<div class="d-flex justify-content-around"><a class="btn float-xl-left" href="' . route('admins.edit', [$row->id]) . '">';
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
        return view('admin.admins.index');
    }

    public function create()
    {
        try{
            $params['data'] = [];
            
            return view('admin.admins.add',$params);
        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function store(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'user_name' => 'required',
                'email' => 'required|email',
                'permissions_role' => 'required',
                'password' => 'required|min:4',
            ]);

            
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }
            
            if(isset($request->user_name)){
                $username = Admin::where('user_name',$request->user_name)->first();
                $email    = Admin::where('email',$request->email)->first();
                if($username){
                    $errs[] = "This user name is already exist.";
                    return response()->json(array('status' => 400, 'errors' => $errs));
                }elseif($email){
                    $errs[] = "This email is already exist.";
                    return response()->json(array('status' => 400, 'errors' => $errs));
                }else{
                    $user_data = Admin::updateOrCreate(['id' => $request->id],[
                        'user_name' => $request->user_name,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                        'permissions_role' => $request->permissions_role,
                        'type' => '1',
                    ]);
                    if($user_data->id){
                        return response()->json(array('status' => 200, 'success' => 'Succesfully Admin User Save.'));
                    }else{
                        return response()->json(array('status' => 400, 'errors' => 'Error In Admin User Save'));
                    }
                }
            }
            
        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function destroy($id)
    {
        try{
            $data = Admin::where('id', $id)->delete();
            return redirect()->back()->with('success', 'Admin delete successfully');

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function edit($id) {
        try{
            $params['data'] = Admin::where('id', $id)->first();

            if ($params['data'] != null) {
                return view('admin.admins.edit', $params);
            } else {
                return redirect()->back()->with('error', __('label.page_not_found'));
            }

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function update(Request $request, $id){
        try{

            $check_validation = [
                'email' => 'required|email',
                'user_name' => 'required',
            ];
            if(isset($request->password)){
                $check_validation['password'] = 'min:4';
            }

            $validator = Validator::make($request->all(), $check_validation);

            
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }
            
            if(isset($request->id)){
                $admin = Admin::find($request->id);
                if(isset($request->password)){$password = Hash::make($request->password);}else{$password = $admin->password;}
                $user_data = Admin::updateOrCreate(['id' => $request->id],[
                    'user_name' => $request->user_name,
                    'email' => $request->email,
                    'password' => $password,
                    'type' => 1,
                    'permissions_role' => $request->permissions_role
                ]);
                if($user_data->id){
                    return response()->json(array('status' => 200, 'success' => 'Succesfully Admin User Save.'));
                }else{
                    return response()->json(array('status' => 400, 'errors' => 'Error In Admin User Save'));
                }
            }
            
        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }

    }

    public function Page()
    {
        try{
            $currentURL = URL::current();

            if (str_contains($currentURL, 'about-us')) {
                $data = Page::select('*')->where('page_name', 'about-us')->first();
                return view('page', ['result' => $data]);
            } elseif (str_contains($currentURL, 'privacy-policy')) {
                $data = Page::select('*')->where('page_name', 'privacy-policy')->first();
                return view('page', ['result' => $data]);
            } elseif (str_contains($currentURL, 'terms-and-conditions')) {
                $data = Page::select('*')->where('page_name', 'terms-and-conditions')->first();
                return view('page', ['result' => $data]);
            } elseif (str_contains($currentURL, 'refund-policy')) {
                $data = Page::select('*')->where('page_name', 'refund-policy')->first();
                return view('page', ['result' => $data]);
            } elseif(str_contains($currentURL, 'remaining')){
                $data = Page::select('*')->where('page_name', 'remaining')->first();
                return view('page', ['result' => $data]);
            } else {
                abort(404);
            }

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
