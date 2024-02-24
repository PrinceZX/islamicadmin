<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Package;
use App\Models\User;
use Validator;
use DataTables;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    
    public function index(Request $request)
    {
        try {

            $expiry = Transaction::where('status', 1)->get();
            for ($i = 0; $i < count($expiry); $i++) {
                if ($expiry[$i]['expiry_date'] < date('Y-m-d')) {
                    $expiry[$i]['status'] = 0;
                    $expiry[$i]->save();
                }
            }

            $params['data'] = [];
            if ($request->ajax()) {
                $data = Transaction::select('*')->with('package','user')->latest()->get();
                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        if ($row->status == 1) {
                            return "<button type='button' style='background:#15ca20; font-size:14px; font-weight:bold; border: none;  color: white; padding: 4px 20px; outline: none;'>Active</button>";
                        } else {
                            return "<button type='button' style='background:#0dceec; font-size:14px; font-weight:bold; letter-spacing:0.1px; border: none; color: white; padding: 5px 15px; outline: none;'>Expiry</button>";
                        }
                    })
                    ->addColumn('date', function($row) {
                        $date = date("Y-m-d", strtotime($row->created_at));
                        return $date;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('admin.transaction.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

   
    public function create(Request $request)
    {
        try{
            $params['data'] =[];
            $params['user'] = User::where('id', $request->user_id)->first();
            $params['package'] = Package::get();

            return view('admin.transaction.add',$params);

        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

  
    public function store(Request $request)
    {
        try{
            
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'package_id' => 'required'
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            } else {

                $package = Package::where('id', $request->package_id)->first();

                $expiry_date = date('Y-m-d', strtotime('+' . $package->time . ' ' . strtolower($package->type)));

                $Transction = new Transaction();
                $Transction->user_id =$request->user_id;
                $Transction->package_id =$request->package_id;
                $Transction->description =$package->name;
                $Transction->amount =$package->price;
                $Transction->payment_id = 'admin';
                $Transction->currency_code = currency_code();
                $Transction->expiry_date = $expiry_date;
                $Transction->status = 1;
                if ($Transction->save()) {
                    if ($Transction->id) {
                        return response()->json(array('status' => 200, 'success' => __('label.Transction_Add_Successfully')));
                    } else {
                        return response()->json(array('status' => 400, 'errors' => __('label.Transction_Not_Add')));
                    }
                } else {
                    return response()->json(array('status' => 400, 'errors' => __('label.Transction_Not_Add')));
                }

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
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

   
    public function destroy($id)
    {
        //
    }
}
