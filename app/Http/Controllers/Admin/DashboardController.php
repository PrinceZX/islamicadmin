<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common;
use App\Models\Album;
use App\Models\Language;
use App;


class DashboardController extends Controller
{
    private $folder = "user";
    private $folder1 = "artist";
    private $folder2 = "video";
    private $folder3 = "package";
    private $folder_album = "album";


    public $common;

    public function __construct()
    {
        $this->common = new Common;
    }

    public function index()
    {
        try{
            $params['UserCount'] = \App\Models\User::count();
            $params['CategoryCount'] = \App\Models\Category::count();
            $params['VideoCount'] = \App\Models\Video::count();
            $params['AlbumCount'] = \App\Models\Album::count();
            $params['PackageCount'] = \App\Models\Package::count();
            $params['LanguageCount'] = \App\Models\Language::count();
            $params['EarningsCount'] = \App\Models\Transaction::sum('amount');
            $params['CurrentMounthCount'] = \App\Models\Transaction::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->sum('amount');



            $params['recent_user'] = \App\Models\User::select('*')->latest()->take(5)->get();

            // Recent User //
            $this->common->imageNameToUrl($params['recent_user'], 'image', $this->folder);

            $params['popular_artist'] =\App\Models\Artist::select('*')->latest()->take(4)->get();

            // Popular Artist //
            $this->common->imageNameToUrl($params['popular_artist'], 'image' ,$this->folder1);
            
            $params['recent_package'] =\App\Models\Package::select('*')->latest()->take(4)->get();

             // Recent Package //
             $this->common->imageNameToUrl($params['recent_package'], 'image' ,$this->folder3);

            $params['most_view_video'] =\App\Models\Video::orderBy('v_view',"DESC")->latest()->first();

            // Latest Video 
            $this->common->imageNameToUrl(array($params['most_view_video']), 'image' ,$this->folder2);
            



            return view('admin.dashboard',$params);

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    
    public function language($id)
    {
        // Language::where('status',1)->update(['status'=>0]);

        // $language =Language::where('id',$id)->first();

        // if(isset($language->id)){
            // $language ->status =1;
            // if($language->save()){
                // App::setLocale($language->name);
                // session()->put('locale',$language->name);

                // return redirect()->back();
            // }
        // }
        // dd($id);

        if($id == 1){
            App::setLocale('Arebic');
                session()->put('locale','Arebic');
                return back()->with('success', __('label.language_change_success'));
        } else if($id ==2){
            App::setLocale('English');
                session()->put('locale','English');
                return back()->with('success', __('label.language_change_success'));

        } else if($id ==3){
            App::setLocale('Hindi');
            session()->put('locale','Hindi');
            return back()->with('success', __('label.language_change_success'));

        }
        
    }
}
