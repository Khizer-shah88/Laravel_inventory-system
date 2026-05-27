<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JournalController extends Controller
{
    
 public function edit($id)
    {
        $journal = DB::table('tblJournal')->where('CBID', $id)->first();
        $accounts = DB::table('tblAccounts')
    ->orderBy('AccountName', 'asc')
    ->get();

        $journalSubs = DB::table('tblJournalSub')->where('CBID', $id)->get();
        return view('Journal.edit', compact('journal', 'accounts', 'journalSubs'));
    }

    // Update journal entry
public function update(Request $request, $id)
{
    // Update master record (tblJournal)
    DB::table('tblJournal')
        ->where('CBID', $id)
        ->update([
            'CBDate' => $request->CBDate
        ]);

    // Delete existing child rows
    DB::table('tblJournalSub')->where('CBID', $id)->delete();

    // Insert updated child rows
    $rows = count($request->AccountName);
    for ($i = 0; $i < $rows; $i++) {
        DB::table('tblJournalSub')->insert([
            'CBID'       => $id,
            'HeaderCode' => $request->HeaderCode[$i],
            'AccountId'  => $request->AccountId[$i],
            'AccountName'=> $request->AccountName[$i],
            'Description'=> $request->Description[$i],
            'Debit'      => $request->Debit[$i] ?? 0,
            'Credit'     => $request->Credit[$i] ?? 0,
        ]);
    }

    return redirect()->route('journal.index')->with('success', 'Journal updated successfully.');
}
    
    /**
     * Show all journals
     */
    public function index()
    {
        $journals = DB::table('tblJournal')
            ->orderBy('CBID', 'desc')
            ->get();

        return view('Journal.index', compact('journals'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        // Fetch all accounts for datalist
        $accounts = DB::table('tblAccounts')
    ->orderBy('AccountName', 'asc')
    ->get();
        return view('Journal.create', compact('accounts'));
    }

    /**
     * Store a new journal entry (master + child)
     */
    public function store(Request $request)
    {
        $request->validate([
            'CBDate' => 'required|date',
            'AccountName.*' => 'required',
            'HeaderCode.*' => 'required',
            'AccountId.*' => 'required',
            'Debit.*' => 'nullable|numeric',
            'Credit.*' => 'nullable|numeric',
        ]);

        DB::beginTransaction();
        try {
            // Insert master
            $masterId = DB::table('tblJournal')->insertGetId([
                'CBDate' => $request->CBDate,
            ]);

            // Insert children
            $count = count($request->AccountName);
            for ($i = 0; $i < $count; $i++) {
                DB::table('tblJournalSub')->insert([
                    'CBID' => $masterId,
                    'HeaderCode' => $request->HeaderCode[$i],
                    'AccountId' => $request->AccountId[$i],
                    'AccountName' => $request->AccountName[$i],
                    'Description' => $request->Description[$i] ?? null,
                    'Debit' => $request->Debit[$i] ?? 0,
                    'Credit' => $request->Credit[$i] ?? 0,
                ]);
            }

            DB::commit();
            return redirect()->route('journal.index')->with('success', 'Journal saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show a single journal with its child records
     */
    public function show($id)
    {
        $journal = DB::table('tblJournal')->where('CBID', $id)->first();
        $journalSubs = DB::table('tblJournalSub')->where('CBID', $id)->get();

        return view('journal.show', compact('journal', 'journalSubs'));
    }
}
