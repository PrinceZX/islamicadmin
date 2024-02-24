<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        try {
            $params['data'] = [];
            if ($request->ajax()) {
                $data = Comment::with('user')->with('video')->latest()->get();

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        if ($row->status == 1) {
                            return "<button type='button' id='$row->id' onclick='change_status($row->id, $row->status)' style='background:#15ca20; font-size:14px; font-weight:bold; border: none;  color: white; padding: 4px 20px; outline: none;'>Show</button>";
                        } else {
                            return "<button type='button' id='$row->id' onclick='change_status($row->id, $row->status)' style='background:#0dceec; font-size:14px; font-weight:bold; letter-spacing:0.1px; border: none; color: white; padding: 5px 15px; outline: none;'>Hide</button>";
                        }
                    })
                    ->addColumn('date', function ($row) {
                        $date = date("Y-m-d", strtotime($row->created_at));
                        return $date;
                    })

                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('admin.comment.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function show($id)
    {
        try {
            if (Auth::guard('admin')->user()->type != 1) {
                return response()->json(array('status' => 400, 'errors' => __('Label.You have no right to add, edit, and delete')));
            } else {
                $data = Comment::where('id', $id)->first();
                if ($data->status == '0') {
                    $data->status = '1';
                } elseif ($data->status == '1') {
                    $data->status = '0';
                } else {
                    $data->status = '0';
                }
                $data->save();
                return response()->json(array('status' => 200, 'success' => 'Status Changed', 'id' => $data->id, 'Status_Code' => $data->status));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
