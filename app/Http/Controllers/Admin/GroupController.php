<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Groups;
use App\Models\Offer;
use App\Traits\PhotoTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class GroupController extends Controller
{
    use PhotoTrait;

    public function __construct()
    {
        $this->middleware('adminPermission:Marketing')->except('makeAllGroupsAvailable');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $groups = Groups::latest()->get();
            return Datatables::of($groups)
                ->addColumn('action', function ($group) {
                    return '
                            <button type="button" data-id="' . $group->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $group->id . '" data-title="' . $group->title . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })

                ->escapeColumns([])
                ->make(true);
        }

        return view('Admin.groups.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Admin.groups.parts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:groups',
        ]);

        $data = $request->all();

        Groups::create($data);
        return response()->json(['status' => 200]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Groups $group)
    {
        return view('Admin.groups.parts.edit', compact('group'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|unique:groups,title,'. $id,
        ]);

        $data = $request->all();

        Groups::findOrFail($id)->update($data);
        return response()->json(['status' => 200]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Groups::destroy($request->id);
        return response()->json(['status' => 200]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function makeAllGroupsAvailable()
    {
       return Groups::select('*')->update(['status' => 'available']);
    }



}
