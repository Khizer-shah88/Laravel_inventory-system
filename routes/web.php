<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ReceivingVoucherController;
use App\Http\Controllers\PaymentVoucherController;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Customers;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\SaleInvoiceController;
use App\Http\Controllers\PurchaseInvoiceController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\Reports;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\CashBookController;
use App\Http\Controllers\BoxController;
use App\Http\Controllers\BoxSubController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\Users;
use Illuminate\Support\Facades\File;







  // routes/web.php
Route::get('/item-by-code/{itemCode}', [ItemsController::class, 'getItemByCode']);


Route::middleware(['check.user.access'])->group(function () {

    Route::resource('box', BoxController::class);
    Route::post('/box-items', [BoxController::class, 'items'])->name('box.items.post');
    Route::get('/box-items', [BoxController::class, 'items'])->name('box.items');

});



Route::middleware(['guard'])->group(function(){
    
    Route::get('/Users/add',[Users::class,'open']);
    Route::post('/Users/add',[Users::class,'addNew']);
    Route::get('/Users',[Users::class,'index'])->name('Users.index');
    Route::get('/Users/delete/{id}',[Users::class,'delete'])->name('Users.delete');
    Route::get('/Users/edit/{id}',[Users::class,'edit'])->name('Users.edit');    
    Route::post('/Users/update/{id}',[Users::class,'update']);


    Route::post('/box-sub/store', [BoxSubController::class, 'storeSub'])->name('box.sub.simpleStore');
    Route::delete('/box/item/{id?}', [BoxController::class, 'deleteItem'])->name('box.item.delete');
    Route::post('/check-boxsub', [App\Http\Controllers\BoxSubController::class, 'checkDuplicate'])->name('BoxSub.check');
    Route::resource('box.sub', BoxSubController::class);
    Route::delete('/boxsub/{id}', [BoxSubController::class, 'destroy'])->name('boxsub.destroy');
    Route::delete('/box-items/{id}', [BoxController::class, 'destroyItems'])->name('box-items.destroy');
    
    
    Route::resource('menus', MenuController::class);
    
Route::get('/check-structure', [SaleInvoiceController::class, 'checkTableStructure']);

Route::get('/get-accounts', [Customers::class, 'getAccounts'])->name('get.accounts');




Route::get('/get-items', [ItemsController::class, 'getItem'])->name('get.items');



Route::get('/cashbook', [CashBookController::class, 'index'])->name('cashbook.index');
Route::post('/cashbook/store', [CashBookController::class, 'store'])->name('cashbook.store');
Route::get('/cashbook/create', [CashBookController::class, 'create'])->name('cashbook.create');
Route::post('/cashbook/update/{id}', [CashBookController::class, 'update'])->name('cashbook.update');
Route::delete('/cashbook/delete/{id}', [CashBookController::class, 'delete'])->name('cashbook.delete');
Route::get('/cashbook/get-subs/{CBID}', [CashBookController::class, 'getSubs'])->name('cashbook.getSubs');
Route::get('/cashbook/sub/{id}', [CashBookController::class, 'getSub'])->name('cashbook.getSub');
Route::get('/cashbook/edit/{id}', [CashBookController::class, 'edit'])->name('cashbook.edit');
Route::delete('/cashbook/delete-master/{id}', [CashBookController::class, 'deleteMaster'])->name('cashbook.deleteMaster');


Route::prefix('journal')->group(function () {
    Route::get('/', [JournalController::class, 'index'])->name('journal.index');
    Route::get('/create', [JournalController::class, 'create'])->name('journal.create');
    Route::post('/store', [JournalController::class, 'store'])->name('journal.store');
    Route::get('/edit/{id}', [JournalController::class, 'edit'])->name('journal.edit');
    Route::put('/update/{id}', [JournalController::class, 'update'])->name('journal.update');
    Route::delete('/destroy/{id}', [JournalController::class, 'destroy'])->name('journal.destroy');
});


Route::get('/ledger', [LedgerController::class, 'index'])->name('ledger.index');
Route::post('/ledger', [LedgerController::class, 'show'])->name('ledger.show');


Route::get('/SaleInvoice/create', [SaleInvoiceController::class, 'create'])->name('SaleInvoice.create');
Route::post('/sale-invoice', [SaleInvoiceController::class, 'store']);
Route::get('/SaleInvoice/{InvNo}', [SaleInvoiceController::class, 'show'])->name('SaleInvoice.show');
Route::get('/SaleInvoice/showa4/{invNo}', [App\Http\Controllers\SaleInvoiceController::class, 'showA4'])->name('SaleInvoice.showA4');
Route::get('/SaleInvoice/{invNo}/edit', [SaleInvoiceController::class, 'edit'])->name('SaleInvoice.edit');
Route::put('/SaleInvoice/{invNo}', [SaleInvoiceController::class, 'update'])->name('SaleInvoice.update');
Route::get('/SaleInvoice', [SaleInvoiceController::class, 'index'])->name('SaleInvoice.index');
Route::put('/SaleInvoice/{InvNo}', [SaleInvoiceController::class, 'update'])->name('SaleInvoice.update');
Route::delete('/SaleInvoice/{InvNo}', [SaleInvoiceController::class, 'destroy'])->name('SaleInvoice.destroy');




Route::get('/PurchaseInvoice/create', [PurchaseInvoiceController::class, 'create'])->name('PurchaseInvoice.create');
Route::post('/PurchaseInvoice/store', [PurchaseInvoiceController::class, 'store'])->name('PurchaseInvoice.store');
Route::get('/PurchaseInvoice/{InvNo}', [PurchaseInvoiceController::class, 'show'])->name('PurchaseInvoice.show');
Route::get('/PurchaseInvoice/showa4/{invNo}', [App\Http\Controllers\PurchaseInvoiceController::class, 'showA4'])->name('PurchaseInvoice.showA4');
Route::get('/PurchaseInvoice/{invNo}/edit', [PurchaseInvoiceController::class, 'edit'])->name('PurchaseInvoice.edit');
Route::put('/PurchaseInvoice/{invNo}', [PurchaseInvoiceController::class, 'update'])->name('PurchaseInvoice.update');
Route::get('/PurchaseInvoice', [PurchaseInvoiceController::class, 'index'])->name('PurchaseInvoice.index');



Route::get('reports/balance-sheet', [Reports::class, 'balanceSheet'])->name('balance.sheet');
Route::get('/reports/expense', [Reports::class, 'expense'])->name('reports.expense');
Route::get('/reports/bank', [Reports::class, 'bank'])->name('reports.bank');
Route::get('/reports/personal_loan', [Reports::class, 'personal_loan'])->name('reports.personal_loan');
Route::get('/reports/staff_loan', [Reports::class, 'staff_loan'])->name('reports.staff_loan');
Route::get('/reports/customer_receivables', [Reports::class, 'customer_receivables'])->name('reports.customer_receivables');
Route::get('/reports/company_payables', [Reports::class, 'company_payables'])->name('reports.company_payables');
Route::get('/reports/company_claim', [Reports::class, 'company_claim'])->name('reports.company_claim');



Route::get('/Reports', function () {
    return view('Reports.index');
})->name('Reports.index');

Route::get('/remaining-fees', [DashboardController::class, 'remainingFeesList'])->name('remaining.fees');

Route::resource('Students', StudentController::class);
Route::resource('Teachers', TeacherController::class);

Route::resource('ReceivingVouchers', ReceivingVoucherController::class);
Route::resource('PaymentVouchers', PaymentVoucherController::class);
Route::resource('Accounts', AccountsController::class);



Route::get('/', [DashboardController::class, 'index'])->name('home');



Route::get('/Reports/fifo', [Reports::class, 'fifoReport'])->name('Reports.fifo');



// Items Routes
Route::prefix('items')->group(function () {
    Route::get('/', [ItemsController::class, 'index'])->name('items.index');
    Route::get('/create', [ItemsController::class, 'create'])->name('items.create');
    Route::post('/', [ItemsController::class, 'store'])->name('items.store');
    Route::post('/{id}/images', [ItemsController::class, 'storeImages'])->name('items.images.store');
    Route::put('/images/{imageId}', [ItemsController::class, 'updateImage'])->name('items.images.update');
    Route::delete('/images/{imageId}', [ItemsController::class, 'destroyImage'])->name('items.images.destroy');
    Route::get('/{id}', [ItemsController::class, 'show'])->name('items.show');
    Route::get('/{id}/edit', [ItemsController::class, 'edit'])->name('items.edit');
    Route::put('/{id}', [ItemsController::class, 'update'])->name('items.update');
    Route::delete('/{id}', [ItemsController::class, 'destroy'])->name('items.destroy');
});



});


Route::get('/items/{code}/details/{customerType}', function ($code, $customerType) {
    $item = DB::table('tblItems')->where('Barcode', $code)->first();



    if ($item) {

        // Choose rate based on CustomerType (case insensitive)
        switch (($customerType)) {
            case 'Distributor':
                $rate = $item->DUprice;
                $packet = $item->DPprice;
                break;


            case 'Retail':
                $rate = $item->USalprice;
                $packet = $item->RPprice;
                break;

            case 'Whole Sale':
                $rate = $item->WUprice;
                $packet = $item->WPprice;
                break;

            default:
                $rate = 0; // fallback
                $packet = 0;
        }

        return response()->json([
            'ItemCode'   => $item->ItemCode,
            'ItemName'   => $item->ItemName,
            'PacketSize' => $item->PacketSize,
            'Prate' => $item->UPurprice,
            'Rate'       => $rate,
            'packetRate'       => $packet
        ]);
    }


    return response()->json([], 404);
});


Route::prefix('items')->group(function () {
    Route::get('/image/{path}', function ($path) {
        $imagePath = rawurldecode($path);
        $cleanPath = ltrim(trim($imagePath), '/');

        $candidatePaths = array_values(array_unique([
            base_path($cleanPath),
            public_path($cleanPath),
            storage_path('app/' . $cleanPath),
            storage_path('app/public/' . $cleanPath),
        ]));

        foreach ($candidatePaths as $fullPath) {
            if (File::exists($fullPath)) {
                return response()->file($fullPath);
            }
        }

        abort(404);
    })
        ->where('path', '.*')
        ->name('items.image');
    Route::get('/{id}/share', function ($id) {
        $item = DB::table('tblItems')
            ->where('ItemCode', $id)
            ->first();

        $latestImage = DB::table('item_images')
            ->where('ItemCode', $id)
            ->orderByDesc('id')
            ->first();

        if (!$item) {
            abort(404, 'Item not found');
        }

        $placeholderImage = 'data:image/svg+xml;base64,' . base64_encode(
            '<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="800" viewBox="0 0 1200 800"><rect width="1200" height="800" fill="#f2f4f7"/><rect x="60" y="60" width="1080" height="680" rx="24" fill="#ffffff" stroke="#d0d7de" stroke-dasharray="18 14"/><text x="600" y="380" text-anchor="middle" font-family="Arial, sans-serif" font-size="42" fill="#98a2b3">No image available</text></svg>'
        );

        $imagePath = $latestImage?->image_path;
        $shareImage = $imagePath
            ? route('items.image', ['path' => ltrim($imagePath, '/')])
            : $placeholderImage;

        return view('items.share', compact('item', 'shareImage', 'placeholderImage'));
    })->name('items.share');
    Route::get('/{id}/share-pdf', function ($id) {
        $item = DB::table('tblItems')
            ->where('ItemCode', $id)
            ->first();

        if (!$item) {
            abort(404, 'Item not found');
        }

        $printUrl = route('items.share', $item->ItemCode);
        $message = "Item: {$item->ItemName}\nView/Print: {$printUrl}\nUse browser Print > Save as PDF.";

        return redirect()->away('https://wa.me/?text=' . urlencode($message));
    })->name('items.sharePdf');
});




Route::get('/items/names', [ItemsController::class, 'getItemNames']); 






Route::get('/logout', [Customers::class, 'logout'])->name('logout');

// 🔐 Login Page (GET)
Route::get('/login', [Customers::class, 'showLoginForm'])->name('login_x');

// 🔑 Handle Login (POST)
Route::post('/login', [Customers::class, 'login'])->name('login.post');


//Route::post('/login', [Customers::class,'login'])->name('login.submit');







