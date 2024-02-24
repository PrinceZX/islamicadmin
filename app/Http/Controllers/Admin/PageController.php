<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use DataTables;
use Illuminate\Http\Request;
use Validator;

class PageController extends Controller
{
   
    public function index(Request $request)
    {
        try {
            $params['data'] = [];
            if ($request->ajax()) {
                $data = Page::select('*')->get();

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $btn = '<a class="btn" href="' . route('page.edit', [$row->id]) . '">';
                        $btn .= '<img src="' . asset('assets/imgs/edit.png') . '" />';
                        $btn .= '</a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('admin.page.index', $params);
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
            $params['data'] = Page::where('id', $id)->first();

            if ($params['data'] != null) {
                return view('admin.page.edit', $params);
            } else {
                return redirect()->back()->with('error', __('label.page_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
            ]);

            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            } else {

                $page = Page::where('id', $request->id)->first();

                if (isset($page->id)) {

                    $page->title = $request->title;
                    $page->description = $request->description;
                    $page->status = '1';

                    if ($page->save()) {
                        return response()->json(array('status' => 200, 'success' => __('label.page_update')));
                    } else {
                        return response()->json(array('status' => 400, 'errors' => __('label.page_not_update')));
                    }
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
