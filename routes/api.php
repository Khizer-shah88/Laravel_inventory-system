<?php



use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoxController;



Route::get('/boxes', function (Request $request) {

    $itemCode = $request->query('ItemCode');

    $query = DB::table('tblBox as b')
        ->join('tblBoxSub as s', 'b.Id', '=', 's.BoxId')
        ->select('b.Id', 'b.BoxName', 's.ItemCode', 's.ItemName');


    return response()->json($query->get());

});

Route::get('/get-boxes', [BoxController::class, 'getBoxes']);


Route::post('/insert-items', function (Request $request) {
    try {
        $data = $request->input('items', []);

        DB::table('tblItems')->truncate();
        
        foreach ($data as $item) {
            DB::table('tblItems')->insert([
                'Barcode' => $item['ItemId'],
                'ItemName' => $item['ItemName'],
                'ItemNameUrdu' => $item['ItemNameUrdu'],
                'UPurprice' => $item['PurPrice'],
                'USalprice' => $item['SalPrice'],
                'CompanyName' => $item['CompanyName'],
                'Category' => $item['Category'],
                'WUprice' => $item['SalePrice2'],
                'WPprice' => $item['SalePrice3'],
                'PacketSize' => $item['CartoonSize'],

                // 🆕 Newly added columns
                'PPurprice' => $item['CartoonPrice'],
                'EPprice' => $item['ExpenseRate'],
                'DPprice' => $item['DistributorPacketPrice'],
                'DUprice' => $item['DistributorPerPrice'],
                'RPprice' => $item['RetailPacketPrice'],
                'PType' => $item['ItemType'],
                'PTypeUrdu' => $item['ItemTypeUrdu'],
                'UType' => $item['ItemType2'],
                'UTypeUrdu' => $item['ItemTypeUrdu2'],
                'TotalStock' => $item['TotalStock'],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'count' => count($data)
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }

    
    
    
});




