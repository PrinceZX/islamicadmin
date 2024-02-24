<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Common;
use App\Models\General_Setting;
use App\Models\Smtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\Subscribe;
use Storage;
use Config;
use Validator;


class SettingController extends Controller
{
    private $folder = "app_setting";
    public $common;

    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try{
            $setting = General_Setting::select('*')->get();
            $admin = Admin::select('*')->first();
            $smtp = Smtp::select('*')->first();

            foreach ($setting as $row) {
                $data[$row->key] = $row->value;
            }

            $data['app_logo']=$this->common->SettimgImagePath($this->folder, $data['app_logo']);
            

            

            if ($data && $admin) {
                return view('admin.setting.index', ['result' => $data, 'admin' => $admin, 'smtp' => $smtp]);
            }
        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }

    }

    public function app(Request $request)
    {
        try{
            if (Auth::guard('admin')->user()->type != 1) {
                return response()->json(array('status' => 400, 'errors' => __('label.you_have_no_right_to_add_edit_and_delete')));
            } else {
                $data = $request->all();
                $data["app_name"] = isset($data['app_name']) ? $data['app_name'] : '';
                $data["host_email"] = isset($data['host_email']) ? $data['host_email'] : '';
                $data["app_version"] = isset($data['app_version']) ? $data['app_version'] : '';
                $data["Author"] = isset($data['Author']) ? $data['Author'] : '';
                $data["email"] = isset($data['email']) ? $data['email'] : '';
                $data["contact"] = isset($data['contact']) ? $data['contact'] : '';
                $data["app_desripation"] = isset($data['app_desripation']) ? $data['app_desripation'] : '';
                $data["instrucation"] = isset($data['instrucation']) ? $data['instrucation'] : '';
                $data["privacy_policy"] = isset($data['privacy_policy']) ? $data['privacy_policy'] : '';
                $data["website"] = isset($data['website']) ? $data['website'] : '';

                if (isset($data['app_logo'])) {
                    $files = $data['app_logo'];
                    $data['app_logo'] = $this->common->saveImage($files, $this->folder);

                    $this->common->deleteImageToFolder($this->folder, basename($data['old_app_logo']));
                }

                foreach ($data as $key => $value) {
                    $setting = General_Setting::where('key', $key)->first();
                    if (isset($setting->id)) {
                        $setting->value = $value;
                        $setting->save();
                    }
                }
                return response()->json(array('status' => 200, 'success' => __('label.save_setting')));
            }

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function currency(Request $request)
    {
        try{
            if (Auth::guard('admin')->user()->type != 1) {
                return response()->json(array('status' => 400, 'errors' => __('label.you_have_no_right_to_add_edit_and_delete')));
            } else {
                $data = $request->all();
                $data["currency"] = isset($data['currency']) ? $data['currency'] : '';
                $data["currency_code"] = isset($data['currency_code']) ? $data['currency_code'] : '';

                foreach ($data as $key => $value) {
                    $setting = General_Setting::where('key', $key)->first();
                    if (isset($setting->id)) {
                        $setting->value = $value;
                        $setting->save();
                    }
                }
                return response()->json(array('status' => 200, 'success' => __('label.save_setting')));
            }

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function changepassword(Request $request)
    {
        try{
            if (Auth::guard('admin')->user()->type != 1) {
                return response()->json(array('status' => 400, 'errors' => __('label.you_have_no_right_to_add_edit_and_delete')));
            } else {
                $validator = Validator::make($request->all(), [
                    'password' => 'required|min:4',
                    'confirm_password' => 'required|min:4|same:password',
                ]);
                if ($validator->fails()) {
                    $errs = $validator->errors()->all();
                    return response()->json(array('status' => 400, 'errors' => $errs));
                } else{
                    $data = Admin::where('id', $request->admin_id)->first();

                    if (isset($data->id)) {
                        $data->password = Hash::make($request->password);
                        if ($data->save()) {
                            return response()->json(array('status' => 200, 'success' => __('label.success_change_pass')));
                        } else {
                            return response()->json(array('status' => 400, 'errors' => __('label.error_change_pass')));
                        }
                    } else {
                        return response()->json(array('status' => 400, 'errors' => "errors"));
                    }
                }

            }

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }

    }

    public function admob_android(Request $request)
    {
        try {

            if (Auth::guard('admin')->user()->type != 1) {
                return response()->json(array('status' => 400, 'errors' => __('label.you_have_no_right_to_add_edit_and_delete')));
            } else {
                $data = $request->all();
                $data["banner_adid"] = isset($data['banner_adid']) ? $data['banner_adid'] : '';
                $data["interstital_adid"] = isset($data['interstital_adid']) ? $data['interstital_adid'] : '';
                $data["reward_adid"] = isset($data['reward_adid']) ? $data['reward_adid'] : '';
                $data["interstital_adclick"] = isset($data['interstital_adclick']) ? $data['interstital_adclick'] : '';
                $data["reward_adclick"] = isset($data['reward_adclick']) ? $data['reward_adclick'] : '';

                foreach ($data as $key => $value) {
                    $setting = General_Setting::where('key', $key)->first();
                    if (isset($setting->id)) {
                        $setting->value = $value;
                        $setting->save();
                    }
                }
                return response()->json(array('status' => 200, 'success' => 'Setting Save'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function admob_ios(Request $request)
    {
        try {

            if (Auth::guard('admin')->user()->type != 1) {
                return response()->json(array('status' => 400, 'errors' => __('label.Yyou_have_no_right_to_add_edit_and_delete')));
            } else {
                $data = $request->all();
                $data["ios_banner_adid"] = isset($data['ios_banner_adid']) ? $data['ios_banner_adid'] : '';
                $data["ios_interstital_adid"] = isset($data['ios_interstital_adid']) ? $data['ios_interstital_adid'] : '';
                $data["ios_reward_adid"] = isset($data['ios_reward_adid']) ? $data['ios_reward_adid'] : '';
                $data["ios_interstital_adclick"] = isset($data['ios_interstital_adclick']) ? $data['ios_interstital_adclick'] : '';
                $data["ios_reward_adclick"] = isset($data['ios_reward_adclick']) ? $data['ios_reward_adclick'] : '';

                foreach ($data as $key => $value) {
                    $setting = General_Setting::where('key', $key)->first();
                    if (isset($setting->id)) {
                        $setting->value = $value;
                        $setting->save();
                    }
                }
                return response()->json(array('status' => 200, 'success' => 'Setting Save'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function smtpindex()
    {
        try {
            $smtp = Smtp::select('*')->first();
            
            return view('admin.setting.smtp', ['smtp' => $smtp]);



        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function smtp(Request $request)
    {
        try {

            if (Auth::guard('admin')->user()->type != 1) {
                return response()->json(array('status' => 400, 'errors' => __('label.you_have_no_right_to_add_edit_and_delete')));
            } else {

                $requestData = $request->all();


                $smtp = Smtp::where('id', $request->id)->first();
                if($smtp !=null){
                    if (isset($smtp->id)) {

                        $requestData['protocol'] =isset($requestData['protocol']) ? $requestData['protocol'] : '';
                        $requestData['host'] =isset($requestData['host']) ? $requestData['host'] : '';
                        $requestData['port'] =isset($requestData['port']) ? $requestData['port'] : '';
                        $requestData['user'] =isset($requestData['user']) ? $requestData['user'] : '';
                        $requestData['pass'] =isset($requestData['pass']) ? $requestData['pass'] : '';
                        $requestData['from_name'] =isset($requestData['from_name']) ? $requestData['from_name'] : '';
                        $requestData['from_email'] =isset($requestData['from_email']) ? $requestData['from_email'] : '';
                        $requestData['status'] =isset($requestData['status']) ? $requestData['status'] : 0;

                        $data = Smtp::updateOrCreate(['id' => $requestData['id']], $requestData);

                        if(isset($data->id)){
                            return response()->json(array('status' => 200, 'success' => __('label.save_setting')));
                        }else{
                            return response()->json(array('status' => 400, 'errors' => __('label.data_not_updated')));
                        }

                        
                    }
                }else{
                        $requestData['protocol'] =isset($requestData['protocol']) ? $requestData['protocol'] : '';
                        $requestData['host'] =isset($requestData['host']) ? $requestData['host'] : '';
                        $requestData['port'] =isset($requestData['port']) ? $requestData['port'] : '';
                        $requestData['user'] =isset($requestData['user']) ? $requestData['user'] : '';
                        $requestData['pass'] =isset($requestData['pass']) ? $requestData['pass'] : '';
                        $requestData['from_name'] =isset($requestData['from_name']) ? $requestData['from_name'] : '';
                        $requestData['from_email'] =isset($requestData['from_email']) ? $requestData['from_email'] : '';
                        $requestData['status'] =isset($requestData['status']) ? $requestData['status'] : 0;

                        

                        $smtp_data = Smtp::updateOrCreate(['id' => $requestData['id']], $requestData);

                        if(isset($smtp_data->id)){
                            return response()->json(array('status' => 200, 'success' => __('label.save_setting')));
                        }else{
                            return response()->json(array('status' => 400, 'errors' => __('label.data_not_updated')));
                        }
                }

                   
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

   

}
