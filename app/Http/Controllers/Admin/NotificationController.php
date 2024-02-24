<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\General_Setting;
use App\Models\Notification;
use DataTables;
use Illuminate\Http\Request;
use Storage;
use Validator;

class NotificationController extends Controller
{
   
    private $folder = "notification";
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
                $data = Notification::where('type',4)->latest()->get();

                $this->common->imageNameToUrl($data, 'image', $this->folder);

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $delete = ' <form method="POST"  action="' . route('notification.destroy', [$row->id]) . '">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn "><img src="' . asset('assets/imgs/trash.png') . '" /></button></form>';
                        $btn = $delete;
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('admin.notification.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

   
    public function create()
    {
        try {
            $params['data'] = [];
            return view('admin.notification.add', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

  
  
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'message' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif,gif|max:2048',
            ]);

            if ($validator->fails()) {

                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();

            $requestData['from_user_id'] =0;
            $requestData['user_id'] =0;
            $requestData['video_id'] =0;
            $requestData['type'] =4;


            if (isset($requestData['image']) && $requestData['image'] != 'undefined') {
                $files = $requestData['image'];
                $requestData['image'] = $this->common->saveImage($files, $this->folder);

                  // Image Name to URL
                  $notificationImageURL = $this->common->imageNameToUrl(array($requestData), 'image', $this->folder);
            } else {
                $requestData['image'] = "";
            }

            $notification_data = Notification::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($notification_data->id)) {
                
                // Notification Send App
                   $noty = General_Setting::where('key', 'onesignal_apid')->orWhere('key', 'onesignal_rest_key')->get();
                   $notification = [];
                   foreach ($noty as $row) {
                       $notification[$row->key] = $row->value;
                   }
                   
                   $ONESIGNAL_APP_ID = $notification['onesignal_apid'];
                   $ONESIGNAL_REST_KEY = $notification['onesignal_rest_key'];
   
                   $content = array(
                       "en" => $request->message,
                   );
                   
                   $fields = array(
                       'app_id' => $ONESIGNAL_APP_ID,
                       'included_segments' => array('All'),
                       'data' => array("foo" => "bar"),
                       'headings' => array("en" => $request->title),
                       'contents' => $content,
                   );
   
                   $fields = json_encode($fields);
   
                   $ch = curl_init();
                   curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
                   curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                       'Content-Type: application/json; charset=utf-8',
                       'Authorization: Basic ' . $ONESIGNAL_REST_KEY,
                   ));
                   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                   curl_setopt($ch, CURLOPT_HEADER, false);
                   curl_setopt($ch, CURLOPT_POST, true);
                   curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   
                   $response = curl_exec($ch);
                   curl_close($ch);
                   
                // End Notification //
                return response()->json(array('status' => 200, 'success' => __('label.notification_save')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.notification_not_save')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

   
    public function show($id)
    {
        //
    }

   
    public function edit($id)
    {
        //
    }

   
    public function update(Request $request, $id)
    {
        //
    }

   
    public function destroy($id)
    {
        try {
            $data = Notification::where('id', $id)->first();
            if (isset($data)){
                $this->common->deleteImageToFolder($this->folder, $data['image']);
                $data->delete();
            }
            return redirect()->route('notification.index')->with('success', __('label.notification_delete'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function setting()
    {
        try {
            $setting = General_Setting::select('*')->get();

            foreach ($setting as $row) {
                $data[$row->key] = $row->value;
            }
            if ($data) {

                return view('admin.notification.setting', ['result' => $data]);
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function settingsave(Request $request)
    {
        try {
            $data = $request->all();
            $data["onesignal_apid"] = isset($data['onesignal_apid']) ? $data['onesignal_apid'] : '';
            $data["onesignal_rest_key"] = isset($data['onesignal_rest_key']) ? $data['onesignal_rest_key'] : '';

            foreach ($data as $key => $value) {
                $setting = General_Setting::where('key', $key)->first();
                if (isset($setting->id)) {
                    $setting->value = $value;
                    $setting->save();
                }
            }
            return response()->json(array('status' => 200, 'success' => __('label.notification_setting_update')));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
