<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BoxSubController extends Controller
{
    
    public function checkDuplicate(Request $request)
{
    $exists = DB::table('tblBoxSub')
        ->where('BoxId', $request->SubId)
        ->where('ItemCode', $request->ItemCode)
        ->exists();

    return response()->json(['exists' => $exists]);
}


    
    public function storeSub(Request $request)
{


    DB::table('tblBoxSub')->insert([
        'BoxId'     => $request['SubId'],
        'ItemCode'  => $request['ItemCode'],
        'ItemName'  => $request['ItemName']
    ]);

    return redirect()->back()->with('success', 'Item added successfully.');
}


public function destroy($id)
    {
        $deleted = DB::table('tblBoxSub')->where('id', $id)->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Item deleted successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Item not found'
        ], 404);
    }


    
    
    /**
     * Display all items inside a box.
     */
    public function index($boxId)
    {
        $box = DB::table('tblBox')->where('Id', $boxId)->first();

        if (!$box) {
            abort(404, "Box not found");
        }

        $items = DB::table('tblBoxSub')
            ->where('BoxId', $boxId)
            ->get();

        return view('boxsub.index', compact('box', 'items'));
    }

    /**
     * Show form to add new item in a box.
     */
    public function create($boxId)
    {
        $items = DB::table('tblItems')->get();
        $box = DB::table('tblBox')->where('Id', $boxId)->first();

        if (!$box) {
            abort(404, "Box not found");
        }
        $boxsub = DB::table('tblBoxSub')->where('BoxId',$boxId)->get();

        return view('boxsub.create', compact('box','boxsub','items'));
    }

    /**
     * Store a new item inside box.
     */
public function store(Request $request, $boxId)
{
    $box = DB::table('tblBox')->where('Id', $boxId)->first();

    if (!$box) {
        return back()->with('error', 'Box not found.');
    }

    $request->validate([
        'ItemCode' => 'required|string|max:100',
        'ItemName' => [
            'required',
            'string',
            'max:255',
            function ($attribute, $value, $fail) use ($box) {
                $exists = DB::table('tblBoxSub')
                    ->join('tblBox', 'tblBoxSub.BoxId', '=', 'tblBox.Id')
                    ->where('tblBox.BoxName', $box->BoxName)
                    ->where('tblBoxSub.ItemName', $value)
                    ->exists();

                if ($exists) {
                    $fail("Item '{$value}' already exists in Box '{$box->BoxName}'.");
                }
            },
        ],
    ]);

    DB::table('tblBoxSub')->insert([
        'BoxId'    => $boxId,
        'ItemCode' => strtoupper($request->ItemCode),
        'ItemName' => $request->ItemName,
    ]);

    return back()->with('success', 'Item added successfully.');
}




    /**
     * Show form to edit an item.
     */
    public function edit($boxId, $id)
    {
        $box = DB::table('tblBox')->where('Id', $boxId)->first();
        $item = DB::table('tblBoxSub')->where('Id', $id)->first();

        if (!$box || !$item) {
            abort(404, "Data not found");
        }

        return view('boxsub.edit', compact('box', 'item'));
    }

    /**
     * Update an item inside box.
     */
    public function update(Request $request, $boxId, $id)
    {
        $request->validate([
            'ItemCode' => 'required|string|max:100',
            'ItemName' => 'required|string|max:255',
        ]);

        DB::table('tblBoxSub')
            ->where('Id', $id)
            ->update([
                'ItemCode' => strtoupper($request->ItemCode),
                'ItemName' => $request->ItemName,
            ]);

        return redirect()->route('box.sub.index', $boxId)
                         ->with('success', 'Item updated successfully.');
    }

    /**
     * Delete an item from a box.
     */

}
