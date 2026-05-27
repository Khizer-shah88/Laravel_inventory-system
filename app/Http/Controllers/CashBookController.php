<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashBookController extends Controller
{
    
// In your CashBookController
    public function deleteMaster($id)
    {
        try {
            DB::beginTransaction();
            
            // Delete all child records first
            DB::table('tblCashBookSub')
                ->where('CBID', $id)
                ->delete();
                
            // Delete the master record
            DB::table('tblCashBook')
                ->where('CBID', $id)
                ->delete();
                
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Cash book entry deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error deleting cash book: ' . $e->getMessage()
            ], 500);
        }
    }
    
    
    public function edit($id)
    {
        // Assuming the primary key in tblCashBook is 'CBID'
        $cashbook = DB::table('tblCashBook')->where('CBID', $id)->first();
        
        if (!$cashbook) {
            abort(404);
        }
        
        $accounts = DB::table('tblAccounts')->get();
        
        $Openingcashbook = DB::table('tblCashBookSub')
            ->where('CBID', '<', $id)
            ->selectRaw('SUM(Credit - Debit) as balance')
            ->value('balance') ?? 0;
        
        $Openingjournal = DB::table('tblJournalSub')
            ->where('HeaderCode', 501)
            ->selectRaw('SUM(Debit - Credit) as balance')
            ->value('balance') ?? 0;
        
        $openingBal = $Openingcashbook + $Openingjournal;
            
        return view('CashBook.edit', compact('cashbook', 'accounts', 'openingBal'));
    }
    
    public function update(Request $request, $id)
    {
        // Assuming the primary key in tblCashBookSub is 'CBSID'
        DB::table('tblCashBookSub')
            ->where('Id', $id)
            ->update([
                'HeaderCode' => $request->HeaderCode,
                'AccountId' => $request->AccountId,
                'AccountName' => $request->AccountName,
                'Description' => $request->Description,
                'Debit' => $request->Debit ?? 0,
                'Credit' => $request->Credit ?? 0
            ]);
    
        return response()->json(['success' => true]);
    }

    // Show index page
    public function index()
    {
        $cashbooks = DB::table('tblCashBook')->orderBy('CBDate','desc')->get();


        return view('CashBook.index', compact('cashbooks'));
    }

    // Store new cashbook sub
    public function store(Request $request)
    {
        $CBID = $request->CBID;

        // Insert master if CBID is empty
        if (!$CBID) {
            $CBID = DB::table('tblCashBook')->insertGetId([
                'CBDate' => $request->CBDate,
            ]);
        }

        // Insert sub
        DB::table('tblCashBookSub')->insert([
            'CBID' => $CBID,
            'HeaderCode' => $request->HeaderCode,
            'AccountId' => $request->AccountId,
            'AccountName' => $request->AccountName,
            'Description' => $request->Description,
            'Debit' => $request->Debit ?? 0,
            'Credit' => $request->Credit ?? 0
        ]);

        return response()->json(['success' => true, 'CBID' => $CBID]);
    }



    // Delete a sub row
    public function delete($id)
    {
        DB::table('tblCashBookSub')->where('Id', $id)->delete();

        return response()->json(['success' => true]);
    }

    // Get all subs for a CBID
    public function getSubs($CBID)
    {
        $subs = DB::table('tblCashBookSub')
            ->where('CBID', $CBID)
            ->get();

        $totals = [
            'totalDebit' => DB::table('tblCashBookSub')
                ->where('CBID', $CBID)
                ->sum('Debit'),
            'totalCredit' => DB::table('tblCashBookSub')
                ->where('CBID', $CBID)
                ->sum('Credit')
        ];

        return response()->json(['subs' => $subs, 'totals' => $totals]);
    }

    // Get single sub row for edit
    public function getSub($id)
    {
        $sub = DB::table('tblCashBookSub')
            ->where('Id', $id)
            ->first();

        return response()->json($sub ?? []);
    }

    // Optional create page
    public function create()
    {
        $accounts = DB::table('tblAccounts')->get();
        $openingBal = DB::table('tblCashBookSub')
            ->selectRaw('SUM(Credit - Debit) as openingBal')
            ->value('openingBal') ?? 0;

        return view('CashBook.create', compact('accounts', 'openingBal'));
    }
}
