<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;


class PurchaseInvoiceController extends Controller
{
    

    
    
    public function edit($InvNo)
    {
        try {
            $invoiceNumber = (int)$InvNo;
            
            // Get the sale invoice from correct table
            $saleInvoice = DB::table('tblPurchaseInvoice')
                ->where('InvNo', $invoiceNumber)
                ->first();
    
            if (!$saleInvoice) {
                return redirect()->route('PurchaseInvoice.index')->with('error', 'Invoice not found');
            }
    
            // Get invoice items from correct sub table
            $items = DB::table('tblPurchaseInvoiceSub')
                ->where('InvNo', $invoiceNumber)
                ->get();
    
            // Get accounts and items for dropdowns
            $accounts = DB::table('tblAccounts')->get();
            $allItems = DB::table('tblItems')->get();
    
            return view('PurchaseInvoice.edit', compact('saleInvoice', 'items', 'accounts', 'allItems'));
    
        } catch (\Exception $e) {
            return redirect()->route('PurchaseInvoice.index')->with('error', 'Error loading invoice: ' . $e->getMessage());
        }
    }

    
    public function update(Request $request, $InvNo)
    {
        try {
            $invoiceNumber = (int)$InvNo;
            
            \Log::info('Updating invoice: ' . $invoiceNumber);
    
            // Validate only master fields
            $validated = $request->validate([
                'InvDate'     => 'required|date',
                'AccountName' => 'required|string|max:255',
            ]);
    
            DB::transaction(function () use ($request, $validated, $invoiceNumber) {
                
                // Update master invoice
                DB::table('tblPurchaseInvoice')
                    ->where('InvNo', $invoiceNumber)
                    ->update([
                        'InvDate'     => $validated['InvDate'],
                        'AccountName' => $validated['AccountName'],
                        'HeaderCode'  => $request->HeaderCode,
                        'AccountId'   => $request->AccountId,
                        'Town'        => $request->Town,
                        'CashDiscount' => $request->CashDiscount ?? 0,
                        'CashReceived' => $request->CashReceived ?? 0,
                        'updated_at'  => now(),
                    ]);
    
                // Delete existing items
                DB::table('tblPurchaseInvoiceSub')
                    ->where('InvNo', $invoiceNumber)
                    ->delete();
    
                // Insert new items if present
                if ($request->has('items') && is_array($request->items)) {
                    foreach ($request->items as $row) {
                        if (empty($row['ItemCode']) || empty($row['Qty']) || empty($row['Rate'])) continue;
    
                        DB::table('tblPurchaseInvoiceSub')->insert([
                            'InvNo'     => $invoiceNumber,
                            'ItemCode'  => $row['ItemCode'],
                            'ItemName'  => $row['ItemName'] ?? null,
                            'Qty'       => $row['Qty'],
                            'Rate'      => $row['Rate'],
                            'DiscPer'   => $row['DiscPer'] ?? 0,
                            'Disc'      => $row['Disc'] ?? 0,
                            'created_at'=> now(),
                            'updated_at'=> now(),
                        ]);
                    }
                }
            });
    
            return redirect()->route('PurchaseInvoice.index')->with('success', 'Invoice updated successfully.');
    
        } catch (\Exception $e) {
            \Log::error('Update error: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }
    
    
    public function getItemDetails($itemCode)
    {
        try {
            $item = DB::table('tblItems') // CORRECT TABLE NAME
                ->where('ItemCode', $itemCode)
                ->first();
    
            if ($item) {
                return response()->json([
                    'ItemCode' => $item->ItemCode,
                    'ItemName' => $item->ItemName,
                    'SalePrice' => $item->SalePrice,
                    'PurPrice' => $item->PurPrice,
                ]);
            }
    
            return response()->json(['error' => 'Item not found'], 404);
    
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching item'], 500);
        }
    }


    public function create()
    {
        // Fetch all items
        $items = DB::table('tblItems')
                    ->select('ItemCode','ItemName','SalePrice','PurPrice')
                    ->get();
    
        // Fetch all accounts
        $accounts = DB::table('tblAccounts')
                      ->select('AccountName','HeaderCode','AccountId','Town')
                      ->where('HeaderCode','=',201)
                      ->get();
                      
                      
                      return $accounts;
    
        // Pass items and accounts to the create view
        return view('PurchaseInvoice.create', compact('items', 'accounts'));
    }
    
    
    public function show($InvNo)
    {
        // Get invoice header with CashDiscount
        $invoice = DB::table('tblPurchaseInvoice')
            ->where('InvNo', $InvNo)
            ->select('InvNo', 'InvDate', 'AccountName', 'CashDiscount')
            ->first();
    
        // Get invoice items
        $items = DB::table('tblPurchaseInvoiceSub')
            ->where('InvNo', $InvNo)
            ->select('ItemName', 'Rate', 'Qty', 'Disc', DB::raw('(Qty * Rate) - Disc as Amt'))
            ->get();
    
        // Totals
        $gross = $items->sum(function ($item) {
            return $item->Rate * $item->Qty;
        });
    
        $discount = $items->sum('Disc');
        $cashDiscount = $invoice->CashDiscount ?? 0;
        $net = $gross - $discount - $cashDiscount;
    
        return view('PurchaseInvoice.show', compact('invoice', 'items', 'gross', 'discount', 'cashDiscount', 'net'));
    }
    
    
    public function showA4($invNo)
    {
        // Fetch invoice master record
        $invoice = DB::table('tblPurchaseInvoice')
            ->where('InvNo', $invNo)
            ->first();
    
        if (!$invoice) {
            abort(404, "Invoice not found");
        }
    
        // Fetch invoice items
        $items = DB::table('tblPurchaseInvoiceSub')
            ->where('InvNo', $invNo)
            ->get();
    
        return view('PurchaseInvoice.showa4', compact('invoice', 'items'));
    }


    public function index(Request $request)
    {
        $query = DB::table('tblPurchaseInvoice as inv')
            ->leftJoin('tblPurchaseInvoiceSub as sub', 'inv.InvNo', '=', 'sub.InvNo') // LEFT JOIN instead of JOIN
            ->select(
                'inv.InvNo',
                'inv.InvDate',
                'inv.HeaderCode',
                'inv.AccountId',
                'inv.AccountName',
                'inv.CashDiscount',
                DB::raw('COALESCE(SUM(sub.Qty * sub.Rate), 0) as Gross'),
                DB::raw('COALESCE(SUM(sub.Disc), 0) as Disc')
            )
            ->groupBy('inv.InvNo', 'inv.InvDate', 'inv.HeaderCode', 'inv.AccountId', 'inv.AccountName', 'inv.CashDiscount');
    
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $searchBy = $request->get('search_by', 'InvNo'); // Default InvNo
    
            if ($searchBy === 'InvNo') {
                $query->where('inv.InvNo', 'LIKE', "%{$search}%");
            } elseif ($searchBy === 'AccountName') {
                $query->where('inv.AccountName', 'LIKE', "%{$search}%");
            } elseif ($searchBy === 'InvDate') {
                $query->whereDate('inv.InvDate', '=', date('Y-m-d', strtotime($search)));
            }
        }
    
        // Paginate (10 per page)
        $invoices = $query->paginate(10)->appends($request->all());
    
        // Calculate totals for current page
        $totals = [
            'Gross' => $invoices->sum('Gross'),
            'Disc'  => $invoices->sum('Disc'),
            'Net'   => $invoices->sum(function($inv) {
                return $inv->Gross - $inv->Disc - ($inv->CashDiscount ?? 0);
            }),
        ];
    
        return view('PurchaseInvoice.index', compact('invoices', 'totals'));
    }




        /**
         * Store Sale Invoice with its Sub Items
         */
    public function store(Request $request)
    {
        // Validate only master fields
        $validated = $request->validate([
            'InvDate'     => 'required|date',
            'AccountName' => 'required|string|max:255',
        ]);
    
        try {
            DB::transaction(function () use ($request, $validated) {
                // Insert master invoice
                $invoiceId = DB::table('tblPurchaseInvoice')->insertGetId([
                    'InvDate'     => $validated['InvDate'],
                    'AccountName' => $validated['AccountName'],
                    'HeaderCode'  => $request->HeaderCode,
                    'AccountId'   => $request->AccountId,
                    'Town'        => $request->Town,
                    'CashDiscount'        => $request->CashDiscount,
                    'CashReceived'        => $request->CashReceived,
                'UserId'       => session('user_id'),    // From session
                'UserName'     => session('user_name'),  // From session
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
    
                // Insert sub items if present
                if ($request->has('items') && is_array($request->items)) {
                    foreach ($request->items as $row) {
                        if (empty($row['ItemCode']) || empty($row['Qty']) || empty($row['Rate'])) continue;
    
                        DB::table('tblPurchaseInvoiceSub')->insert([
                            'InvNo'   => $invoiceId,
                            'ItemCode'  => $row['ItemCode'],
                            'ItemName'  => $row['ItemName'] ?? null,
                            'Qty'       => $row['Qty'],
                            'Rate'      => $row['Rate'],
                            'DiscPer'   => $row['DiscPer'] ?? 0,
                            'Disc'      => $row['Disc'] ?? 0,
                            'created_at'=> now(),
                            'updated_at'=> now(),
                        ]);
                    }
                }
            });
    
            return redirect()->back()->with('success', 'Sale Invoice created successfully.');
        } catch (\Exception $e) {
            // Catch any DB errors and send to Blade
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }






}
