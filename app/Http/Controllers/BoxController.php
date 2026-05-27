<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BoxController extends Controller
{
    
    public function getBoxes()
    {
        $boxes = DB::table('tblBox as b')
            ->leftJoin('tblBoxSub as s', 'b.id', '=', 's.BoxId')
            ->select('b.id as BoxID', 'b.BoxName', 's.ItemCode')
            ->get();

        return response()->json(['boxes' => $boxes]);
    }
    
public function items(Request $request)
{
    $box = DB::table('tblBox')->get();

    $items = DB::table('tblItems')
        ->select('Barcode', 'ItemName', 'CompanyName', 'Category','TotalStock','PacketSize','PTypeUrdu')
        ->get();


    $company = DB::table('tblItems')
        ->select('CompanyName')
        ->groupBy('CompanyName')
        ->get();

    $category = DB::table('tblItems')
        ->select('Category')
        ->groupBy('Category')
        ->get();

    // Empty collection by default
    $boxItems = collect();

    // Only build query if the user searched something
    if (
        $request->filled('item_name') ||
        $request->filled('item_code') ||
        $request->filled('company_name') ||
        $request->filled('category') ||
        ($request->filled('start_boxname') && $request->filled('end_boxname'))
    ) {
        $query = DB::table('tblBox')
            ->join('tblBoxSub', 'tblBox.Id', '=', 'tblBoxSub.BoxId')
            ->rightJoin('tblItems', 'tblBoxSub.ItemCode', '=', 'tblItems.Barcode')
            ->select(
                'tblBox.Id as BoxId',
                'tblBoxSub.Id as SubId',
                'tblBox.BoxName',
                'tblItems.Barcode',
                'tblItems.ItemCode',
                'tblItems.ItemName',
                'tblItems.CompanyName',
                'tblItems.Category',
                'tblItems.PacketSize',
                'tblItems.PTypeUrdu',
                'tblItems.TotalStock'
            );

        $searchType = $request->input('search_type', 'box_wise');

        switch ($searchType) {

            case 'item_code':
                $query->where('tblItems.Barcode', '=', $request->input('item_code'));
                break;
                
            case 'item_name':
                $query->where('tblItems.ItemName', 'like', '%' . $request->input('item_name') . '%');
                break;

            case 'company_name':
                $query->where('tblItems.CompanyName', 'like', '%' . $request->input('company_name') . '%');
                break;

            case 'category':
                $query->where('tblItems.Category', 'like', '%' . $request->input('category') . '%');
                break;

            case 'box_wise':
                $query->whereBetween('tblBox.BoxName', [
                    $request->input('start_boxname'),
                    $request->input('end_boxname')
                ]);
                break;
        }

        $query->orderBy('tblBox.BoxName');

        $boxItems = $query->get();

        // Fallback: show item only if no box found
        if ($searchType === 'item_name' && $boxItems->isEmpty() && $request->filled('item_name')) {
            $boxItems = DB::table('tblItems')
                ->select('Barcode', 'ItemName')
                ->where('ItemName', 'like', '%' . $request->input('item_name') . '%')
                ->get();
        }
    }

    return view('box.items', compact('boxItems', 'items', 'company', 'category', 'box'));
}


public function index(Request $request)
{
    // Get all boxes for the datalist
    $box = DB::table('tblBox')->select('Id', 'BoxName')->get();

    // Start base query for boxes
    $query = DB::table('tblBox');

    // Initialize empty collection for boxItems
    $boxItems = collect();

    // If box_id is provided, filter the box and get its items
    if ($request->filled('box_id')) {
        $query->where('Id', $request->box_id);

$boxItems = DB::table('tblBoxSub as sub')
    ->join('tblBox as b', 'b.Id', '=', 'sub.BoxId')
    ->where('sub.BoxId', $request->box_id)
    ->select('sub.*', 'b.BoxName')
    ->get();

    }

    // Get main box result(s)
    $results = $query->get();

    // Return view with all data
    return view('box.index', compact('box', 'results', 'boxItems'));
}


    public function create()
    {
        return view('box.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'BoxName' => 'required|unique:tblBox,BoxName',
        ]);

        DB::table('tblBox')->insert([
            'BoxName'   => strtoupper($request->BoxName),
        ]);

        if ($request->has('save_next')) {
            return redirect()->route('box.create')->with('success', 'Box saved! Add another.');
        }

        return redirect()->route('box.index')->with('success', 'Box saved!');
    }
    
    public function deleteItem($id)
    {
        DB::table('tblBoxSub')->where('Id', $id)->delete();
    
        return redirect()->back()->with('success', 'Item deleted successfully.');
    }

    public function edit($id)
    {
        $box = DB::table('tblBox')->where('Id', $id)->first();
        return view('box.edit', compact('box'));
    }

    public function update(Request $request, $id)
    {
    $request->validate([
        'BoxName' => 'required|unique:tblBox,BoxName,' . $id . ',Id',
    ]);

        DB::table('tblBox')
            ->where('Id', $id)
            ->update([
                'BoxName'   => strtoupper($request->BoxName),
            ]);

        return redirect()->route('box.index')->with('success', 'Box updated!');
    }

    public function destroy($id)
    {
        DB::table('tblBox')->where('Id', $id)->delete();
        return redirect()->route('box.index')->with('success', 'Box deleted!');
    }
    

public function destroyItems($id)
{
    DB::table('tblBoxSub')->where('Id', $id)->delete();

    return redirect()->back()->with('success', 'Box item deleted successfully!');
}
  
}
