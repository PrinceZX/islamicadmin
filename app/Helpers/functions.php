<?php


function settingData()
{
    $setting = \App\Models\General_Setting::get();
    $data = [];
    foreach ($setting as $value) {
        $data[$value->key] = $value->value;
    }
    return $data;
}

function setting() {
    $setting =\App\Models\General_Setting::get();
    
    $data =[];

    foreach($setting as $row){
        $data[$row->key] =$row->value;
       
    }
    return $data;
}



function language()
{
    $language =\App\Models\Language::select('*')->get();

    return $language;
}

function adminData()
{
    $user =auth()->guard('admin')->user();
    return $emails = \App\Models\Admin::select('user_name','email')->where('email',$user->email)->first();
}

function currency_code()
{
    $setting = \App\Models\General_Setting::get();
    $data = [];
    foreach ($setting as $value) {
        $data[$value->key] = $value->value;
    }
    return $data['currency_code'];
}

function TimeToMilliseconds($str)
{

    $time = explode(":", $str);

    $hour = (int) $time[0] * 60 * 60 * 1000;
    $minute = (int) $time[1] * 60 * 1000;
    $sec = (int) $time[2] * 1000;
    $result = $hour + $minute + $sec;
    return $result;
}

function MillisecondsToTime($str)
{
    $Seconds = (int) $str / 1000;
    $Seconds = round($Seconds);

    $Format = sprintf('%02d:%02d:%02d', ((int) $Seconds / 3600), ((int) $Seconds / 60 % 60), ((int) $Seconds) % 60);
    return $Format;
}

function no_format($num)
{
    if ($num > 1000) {
        $x = round($num);
        $x_number_format = number_format($x);
        $x_array = explode(',', $x_number_format);
        $x_parts = array('k', 'm', 'b', 't');
        $x_count_parts = count($x_array) - 1;
        $x_display = $x;
        $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
        $x_display .= $x_parts[$x_count_parts - 1];

        return $x_display;
    }
    return $num;
}

function string_cut($string, $len)
{
    if (strlen($string) > $len) {
        $string = mb_substr(strip_tags($string), 0, $len, 'utf-8') . '...';
        // $string = substr(strip_tags($string),0,$len).'...';
    }
    return $string;
}

function curl($url)
{
    $some_data = array();
    $curl = curl_init();
    // We POST the data
    curl_setopt($curl, CURLOPT_POST, 1);
    // Set the url path we want to call
    curl_setopt($curl, CURLOPT_URL, $url);
    // Make it so the data coming back is put into a string
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    // Insert the data
    curl_setopt($curl, CURLOPT_POSTFIELDS, $some_data);

    $result = curl_exec($curl);
    // Free up the resources $curl is using
    curl_close($curl);

    return (array) json_decode($result);
}


function setting_app_name()
{
    $setting = \App\Models\General_Setting::get();
    $data = [];
    foreach ($setting as $value) {
        $data[$value->key] = $value->value;
    }
    return $data['app_name'];
}















