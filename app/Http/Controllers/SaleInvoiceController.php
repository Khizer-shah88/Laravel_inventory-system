<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;


class SaleInvoiceController extends Controller
{
    
    
    public function create()
    {
        $items = DB::table('tblItems')->get();
        // Fetch all accounts
        $accounts = DB::table('tblAccounts')
                      ->select('AccountName','HeaderCode','AccountId','Town','CustomerType')
                      ->where('HeaderCode','=',101)
                      ->get();
    
        // Pass items and accounts to the create view
        return view('SaleInvoice.create', compact('items', 'accounts'));
    }
    
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'invDate' => 'required|date',
            'headerCode' => 'required|string',
            'accountId' => 'required|string',
            'accountName' => 'required|string',
            'town' => 'nullable|string',
            'cashDiscount' => 'nullable|numeric',
            'cashReceived' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'items' => 'required|array',
            'items.*.itemCode' => 'required|string',
            'items.*.itemName' => 'required|string',
            'items.*.packetSize' => 'required|integer',
            'items.*.packetQty' => 'required|integer',
            'items.*.qty' => 'required|integer',
            'items.*.rate' => 'required|numeric',
            'items.*.packetRate' => 'nullable|numeric', // ✅ add this
            'invoiceId' => 'nullable|integer', // For updates
        ]);
        
        // Start database transaction
        DB::beginTransaction();
        
        try {
            $invoiceId = $validated['invoiceId'] ?? null;
            
            if ($invoiceId) {
                // Update existing invoice
                DB::table('tblSaleInvoice')
                    ->where('InvNo', $invoiceId)
                    ->update([
                        'InvDate' => $validated['invDate'],
                        'HeaderCode' => $validated['headerCode'],
                        'AccountId' => $validated['accountId'],
                        'AccountName' => $validated['accountName'],
                        'Town' => $validated['town'] ?? null,
                        'CashDiscount' => $validated['cashDiscount'] ?? 0,
                        'CashReceived' => $validated['cashReceived'] ?? 0,
                        'Notes' => $validated['notes'] ?? null,
                        'updated_at' => now(),
                    ]);
                
                // Delete existing items
                DB::table('tblSaleInvoiceSub')
                    ->where('InvNo', $invoiceId)
                    ->delete();
                    
                $invNo = $invoiceId;
            } else {
                // Create new invoice
                $invNo = DB::table('tblSaleInvoice')->insertGetId([
                    'InvDate' => $validated['invDate'],
                    'HeaderCode' => $validated['headerCode'],
                    'AccountId' => $validated['accountId'],
                    'AccountName' => $validated['accountName'],
                    'Town' => $validated['town'] ?? null,
                    'CashDiscount' => $validated['cashDiscount'] ?? 0,
                    'CashReceived' => $validated['cashReceived'] ?? 0,
                    'Notes' => $validated['notes'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // Prepare items for insertion
            $itemsData = [];
            foreach ($validated['items'] as $item) {
                $itemsData[] = [
                    'InvNo' => $invNo,
                    'ItemCode' => $item['itemCode'],
                    'ItemName' => $item['itemName'],
                    'Qty' => $item['qty'],
                    'Rate' => $item['rate'],
                    'PacketSize' => $item['packetSize'],
                    'PacketRate' => $item['packetRate'],
                    'PacketQty' => $item['packetQty'],
                    'TotalQty' => ($item['packetSize'] * $item['packetQty']) + $item['qty'],
                ];
            }
            
            // Insert items
            DB::table('tblSaleInvoiceSub')->insert($itemsData);
            
            // Commit transaction
            DB::commit();
            
            return response()->json([
                'success' => true,
                'invNo' => $invNo,
                'message' => $invoiceId ? 'Invoice updated successfully' : 'Invoice created successfully'
            ]);
            
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error saving invoice: ' . $e->getMessage()
            ], 500);
        }
    }


    public function edit($InvNo)
    {
        try {
            $invoiceNumber = (int)$InvNo;
            
            // Get the sale invoice from correct table
            $saleInvoice = DB::table('tblSaleInvoice')
                ->where('InvNo', $invoiceNumber)
                ->first();
    
            if (!$saleInvoice) {
                return redirect()->route('SaleInvoice.index')->with('error', 'Invoice not found');
            }
    
            // Get invoice items from correct sub table
            $items = DB::table('tblSaleInvoiceSub')
                ->where('InvNo', $invoiceNumber)
                ->get();
    
            // Get accounts and items for dropdowns
            $accounts = DB::table('tblAccounts')->get();
            $allItems = DB::table('tblItems')->get();
            
            
    //return $saleInvoice;
            return view('SaleInvoice.edit', compact('saleInvoice', 'items', 'accounts', 'allItems'));
    
        } catch (\Exception $e) {
            return redirect()->route('SaleInvoice.index')->with('error', 'Error loading invoice: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $InvNo)
    {
        try {
            // Debug: Check what data is coming in
            \Log::info('=== UPDATE METHOD STARTED ===');
            \Log::info('Invoice No: ' . $InvNo);
            \Log::info('Request all data: ', $request->all());
            
            // ✅ Validate master data
            $validated = $request->validate([
                'InvDate'      => 'required|date',
                'HeaderCode'   => 'required|string|max:50',
                'AccountId'    => 'required|integer',
                'AccountName'  => 'required|string|max:255',
                'Town'         => 'nullable|string|max:255',
                'CashDiscount' => 'nullable|numeric',
                'Notes'        => 'nullable|string',
            ]);
    
            \Log::info('Master validation passed');
    
            // Start transaction for data consistency
            DB::beginTransaction();
    
            // ✅ Update Master record
            $updateData = [
                'HeaderCode'   => $request->HeaderCode,
                'AccountId'    => $request->AccountId,
                'AccountName'  => $request->AccountName,
                'Town'         => $request->Town,
                'InvDate'      => $request->InvDate,
                'CashDiscount' => $request->CashDiscount ?? 0,
                'updated_at'   => now(),
            ];
    
            // Only add Notes if it exists in request
            if ($request->has('Notes')) {
                $updateData['Notes'] = $request->Notes;
            }
    
            $updated = DB::table('tblSaleInvoice')
                ->where('InvNo', $InvNo)
                ->update($updateData);
    
            \Log::info('Master record updated: ' . ($updated ? 'Yes' : 'No'));
    
            if (!$updated) {
                throw new \Exception('Failed to update invoice master record');
            }
    
            // ✅ Check if child data exists
            if (!$request->has('ItemCode') || !is_array($request->ItemCode)) {
                \Log::warning('No ItemCode array found in request');
                throw new \Exception('No items found in the invoice');
            }
    
            \Log::info('Items count: ' . count($request->ItemCode));
            \Log::info('ItemCode: ', $request->ItemCode);
            \Log::info('ItemName: ', $request->ItemName ?? []);
            \Log::info('PacketSize: ', $request->PacketSize ?? []);
            \Log::info('PacketQty: ', $request->PacketQty ?? []);
            \Log::info('Qty: ', $request->Qty ?? []);
            \Log::info('TotalQty: ', $request->TotalQty ?? []);
            \Log::info('Rate: ', $request->Rate ?? []);
    
            // ✅ Delete existing child records
            $deleted = DB::table('tblSaleInvoiceSub')
                ->where('InvNo', $InvNo)
                ->delete();
    
            \Log::info('Old items deleted: ' . $deleted);
    
            // ✅ Insert new child records
            $itemsInserted = 0;
            $itemErrors = [];
            
            foreach ($request->ItemCode as $index => $itemCode) {
                // Skip empty rows
                if (empty($itemCode) && empty($request->ItemName[$index])) {
                    \Log::info('Skipping empty row at index: ' . $index);
                    continue;
                }
    
                try {
                    // Prepare item data
                    $itemData = [
                        'InvNo'      => $InvNo,
                        'ItemCode'   => $itemCode,
                        'ItemName'   => $request->ItemName[$index] ?? '',
                        'PacketSize' => $request->PacketSize[$index] ?? 0,
                        'PacketQty'  => $request->PacketQty[$index] ?? 0,
                        'Qty'        => $request->Qty[$index] ?? 0,
                        'TotalQty'   => $request->TotalQty[$index] ?? 0,
                        'Rate'       => $request->Rate[$index] ?? 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
    
                    \Log::info('Inserting item: ', $itemData);
    
                    // Try to insert the item
                    DB::table('tblSaleInvoiceSub')->insert($itemData);
                    $itemsInserted++;
                    
                } catch (\Exception $e) {
                    $itemErrors[] = "Item $index error: " . $e->getMessage();
                    \Log::error('Error inserting item ' . $index . ': ' . $e->getMessage());
                }
            }
    
            \Log::info('New items inserted: ' . $itemsInserted);
            
            if (!empty($itemErrors)) {
                \Log::error('Item insertion errors: ', $itemErrors);
                throw new \Exception('Some items could not be inserted: ' . implode(', ', $itemErrors));
            }
    
            if ($itemsInserted === 0) {
                throw new \Exception('No valid items found to insert');
            }
    
            DB::commit();
    
            \Log::info('=== UPDATE COMPLETED SUCCESSFULLY ===');
    
            return redirect()->route('SaleInvoice.index')->with('success', 'Invoice updated successfully!');
    
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log the error for debugging
            \Log::error('Invoice update error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating invoice: ' . $e->getMessage());
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
                    'SalePrice' => $item->SalePrice
                ]);
            }
    
            return response()->json(['error' => 'Item not found'], 404);
    
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching item'], 500);
        }
    }


    
    
public function show($InvNo) 
{
    // Get invoice header with CashDiscount
    $invoice = DB::table('tblSaleInvoice')
        ->where('InvNo', $InvNo)
        ->select('InvNo', 'InvDate', 'AccountName', 'CashDiscount', 'CashReceived')
        ->first();

    // Get invoice items
    $items = DB::table('tblSaleInvoiceSub')
        ->where('InvNo', $InvNo)
        ->select('ItemName', 'Rate', 'Qty', 'Disc', 'PacketSize', 'PacketQty', 'TotalQty')
        ->get();

    // Totals — Calculate gross using TotalQty (not Qty)
    $gross = $items->sum(function ($item) {
        return $item->Rate * $item->TotalQty;
    });

    $discount = $items->sum('Disc');
    $cashDiscount = $invoice->CashDiscount ?? 0;
    $net = $gross - $discount - $cashDiscount;

    return view('SaleInvoice.show', compact('invoice', 'items', 'gross', 'discount', 'cashDiscount', 'net'));
}

    
    
    public function showA4($invNo)
    {
        // Fetch invoice master record
        $invoice = DB::table('tblSaleInvoice')
            ->where('InvNo', $invNo)
            ->first();
    
        if (!$invoice) {
            abort(404, "Invoice not found");
        }
    
        // Fetch invoice items
        $items = DB::table('tblSaleInvoiceSub')
            ->where('InvNo', $invNo)
            ->get();
    
        return view('SaleInvoice.showa4', compact('invoice', 'items'));
    }


    public function index(Request $request)
    {
        $query = DB::table('tblSaleInvoice as inv')
            ->leftJoin('tblSaleInvoiceSub as sub', 'inv.InvNo', '=', 'sub.InvNo') // LEFT JOIN instead of JOIN
            ->select(
                'inv.InvNo',
                'inv.InvDate',
                'inv.HeaderCode',
                'inv.AccountId',
                'inv.AccountName',
                'inv.Town',
                'inv.CashDiscount',
                DB::raw('COALESCE(SUM(sub.TotalQty * sub.Rate), 0) as Gross'),
                DB::raw('COALESCE(SUM(sub.Disc), 0) as Disc')
            ) ->OrderBy('inv.InvNo','desc')
            ->groupBy('inv.InvNo', 'inv.InvDate', 'inv.HeaderCode', 'inv.AccountId', 'inv.AccountName', 'inv.Town', 'inv.CashDiscount');
    
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
    
        return view('SaleInvoice.index', compact('invoices', 'totals'));
    }















}
