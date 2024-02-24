<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment_Option;
use DataTables;
use Illuminate\Http\Request;
use Validator;


class PaymentController extends Controller
{
    
    public function index(Request $request)
    {
        try {
            $params['data'] = [];
            if ($request->ajax()) {
                $data = Payment_Option::orderBy('id','desc')->get();
                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $btn = '<a class="btn" href="' . route('payment.edit', [$row->id]) . '">';
                        $btn .= '<img src="' . asset('assets/imgs/edit.png') . '" />';
                        $btn .= '</a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('admin.payment.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    
    public function create()
    {
        //
    }

  
    public function store(Request $request)
    {
        //
    }

   
    public function show($id)
    {
        //
    }

    
    public function edit($id)
    {
        try {
            $params['data'] = Payment_Option::where('id', $id)->first();
            return view('admin.payment.edit', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'visibility' => 'required',
                'is_live' => 'required',
            ]);

            if ($validator->fails()) {

                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $payment_option = Payment_Option::where('id', $id)->first();

            $data = $request->all();
            $payment_option->live_key_1 = isset($data['live_key_1']) ? $data['live_key_1'] : '';
            $payment_option->live_key_2 = isset($data['live_key_2']) ? $data['live_key_2'] : '';
            $payment_option->live_key_3 = isset($data['live_key_3']) ? $data['live_key_3'] : '';
            $payment_option->test_key_1 = isset($data['test_key_1']) ? $data['test_key_1'] : '';
            $payment_option->test_key_2 = isset($data['test_key_2']) ? $data['test_key_2'] : '';
            $payment_option->test_key_3 = isset($data['test_key_3']) ? $data['test_key_3'] : '';

            if (isset($payment_option->id)) {

                $payment_option->visibility = $request->visibility;
                $payment_option->is_live = $request->is_live;

                if ($payment_option->save()) {
                    return response()->json(array('status' => 200, 'success' => __('label.payment_update')));
                } else {
                    return response()->json(array('status' => 400, 'errors' => __('label.payment_not_update')));
                }
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

  
    public function destroy($id)
    {
        //
    }
}
