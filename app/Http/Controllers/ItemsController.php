<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class ItemsController extends Controller
{

    private function saveItemImages(Request $request, $itemCode)
    {
        $images = $request->file('images', []);
        $capturedImages = $request->input('captured_images', []);

        if (empty($images) && empty($capturedImages)) {
            return;
        }

        if (!is_array($images)) {
            $images = [$images];
        }

        $destinationDir = storage_path('app/item-images/' . $itemCode);

        if (!File::exists($destinationDir)) {
            File::makeDirectory($destinationDir, 0755, true);
        }

        foreach ($images as $image) {
            if (!$image || !$image->isValid()) {
                continue;
            }

            $fileName = now()->format('YmdHis') . '_' . Str::random(8) . '.' . $image->getClientOriginalExtension();
            $image->move($destinationDir, $fileName);

            DB::table('item_images')->insert([
                'ItemCode' => $itemCode,
                'image_path' => 'item-images/' . $itemCode . '/' . $fileName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach ($capturedImages as $payload) {
            $savedPath = $this->storeCapturedImage($payload, $destinationDir, $itemCode);

            if ($savedPath) {
                DB::table('item_images')->insert([
                    'ItemCode' => $itemCode,
                    'image_path' => $savedPath,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function storeCapturedImage(string $payload, string $destinationDir, int $itemCode): ?string
    {
        $payload = trim($payload);

        if ($payload === '') {
            return null;
        }

        $pattern = '/^data:image\/(jpeg|jpg|png|webp);base64,/i';
        if (!preg_match($pattern, $payload, $matches)) {
            return null;
        }

        $extension = strtolower($matches[1] === 'jpeg' ? 'jpg' : $matches[1]);
        $base64Data = preg_replace($pattern, '', $payload);
        $binary = base64_decode($base64Data, true);

        if ($binary === false) {
            return null;
        }

        if (!File::exists($destinationDir)) {
            File::makeDirectory($destinationDir, 0755, true);
        }

        $fileName = now()->format('YmdHis') . '_' . Str::random(8) . '.' . $extension;
        $fullPath = $destinationDir . DIRECTORY_SEPARATOR . $fileName;

        File::put($fullPath, $binary);

        return 'item-images/' . $itemCode . '/' . $fileName;
    }

    private function deleteImageFile(?string $imagePath): void
    {
        if (!$imagePath) {
            return;
        }

        foreach ($this->candidateImagePaths($imagePath) as $fullPath) {
            if (File::exists($fullPath)) {
                File::delete($fullPath);
                return;
            }
        }
    }

    private function candidateImagePaths(string $imagePath): array
    {
        $cleanPath = ltrim(trim($imagePath), '/');

        return array_values(array_unique([
            base_path($cleanPath),
            public_path($cleanPath),
            storage_path('app/' . $cleanPath),
            storage_path('app/public/' . $cleanPath),
        ]));
    }

    private function sharePlaceholderImage(): string
    {
        return 'data:image/svg+xml;base64,' . base64_encode(
            '<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="800" viewBox="0 0 1200 800"><rect width="1200" height="800" fill="#f2f4f7"/><rect x="60" y="60" width="1080" height="680" rx="24" fill="#ffffff" stroke="#d0d7de" stroke-dasharray="18 14"/><text x="600" y="380" text-anchor="middle" font-family="Arial, sans-serif" font-size="42" fill="#98a2b3">No image available</text></svg>'
        );
    }

    private function buildInlineImageData(?string $imagePath, string $placeholderImage): string
    {
        if (!$imagePath) {
            return $placeholderImage;
        }

        foreach ($this->candidateImagePaths($imagePath) as $fullPath) {
            if (!File::exists($fullPath)) {
                continue;
            }

            $mimeType = File::mimeType($fullPath) ?: 'image/jpeg';
            $contents = File::get($fullPath);

            return 'data:' . $mimeType . ';base64,' . base64_encode($contents);
        }

        return $placeholderImage;
    }

    private function resolveShareImageUrl(?string $imagePath, string $placeholderImage): string
    {
        $path = trim((string) $imagePath);

        if ($path === '') {
            return $placeholderImage;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return route('items.image', ['path' => ltrim($path, '/')]);
    }

    private function applyShareFilter($query, ?string $searchColumn, ?string $searchValue)
    {
        $searchColumn = trim((string) $searchColumn);
        $searchValue = trim((string) $searchValue);

        if ($searchColumn === '' || $searchValue === '') {
            return $query;
        }

        $columnMap = [
            'barcode'     => 'Barcode',
            'itemName'    => 'ItemName',
            'companyName' => 'CompanyName',
            'category'    => 'Category',
            'ppprice'     => 'PPurprice',
            'puprice'     => 'UPurprice',
            'usprice'     => 'USalprice',
            'psprice'     => 'RPprice',
            'wuprice'     => 'WUprice',
            'wpprice'     => 'WPprice',
            'duprice'     => 'DUprice',
            'dpprice'     => 'DPprice',
        ];

        $numericColumns = ['ppprice', 'puprice', 'usprice', 'psprice', 'wuprice', 'wpprice', 'duprice', 'dpprice'];

        if (!isset($columnMap[$searchColumn])) {
            return $query;
        }

        if ($searchColumn === 'image') {
            return $query;
        }

        $dbField = $columnMap[$searchColumn];

        if (in_array($searchColumn, $numericColumns, true)) {
            if (preg_match('/^(\d+(?:\.\d+)?)\s*-\s*(\d+(?:\.\d+)?)$/', $searchValue, $m)) {
                return $query->whereBetween($dbField, [(float) $m[1], (float) $m[2]]);
            }

            if (preg_match('/^(>=|<=|>|<|=)\s*(\d+(?:\.\d+)?)$/', $searchValue, $m)) {
                return $query->where($dbField, $m[1], (float) $m[2]);
            }

            if (is_numeric($searchValue)) {
                return $query->where($dbField, (float) $searchValue);
            }

            return $query;
        }

        return $query->where($dbField, 'LIKE', '%' . $searchValue . '%');
    }

    private function shareFilterLabel(?string $searchColumn, ?string $searchValue): string
    {
        $searchColumn = trim((string) $searchColumn);
        $searchValue = trim((string) $searchValue);

        if ($searchColumn === '' || $searchValue === '') {
            return 'All items';
        }

        $labels = [
            'barcode' => 'Barcode',
            'itemName' => 'Item Name',
            'companyName' => 'Company Name',
            'category' => 'Category',
            'ppprice' => 'PPprice',
            'puprice' => 'PUprice',
            'usprice' => 'USprice',
            'psprice' => 'PSprice',
            'wuprice' => 'WUprice',
            'wpprice' => 'WPprice',
            'duprice' => 'DUprice',
            'dpprice' => 'DPprice',
        ];

        $label = $labels[$searchColumn] ?? ucfirst($searchColumn);

        return $label . ': ' . $searchValue;
    }

    private function shareQueryParams(?string $searchColumn, ?string $searchValue): array
    {
        $params = [];

        $searchColumn = trim((string) $searchColumn);
        $searchValue = trim((string) $searchValue);

        if ($searchColumn !== '') {
            $params['search_column'] = $searchColumn;
        }

        if ($searchValue !== '') {
            $params['search_value'] = $searchValue;
        }

        return $params;
    }

    private function fetchShareAllItems(?string $searchColumn, ?string $searchValue)
    {
        $query = DB::table('tblItems')
            ->select(
                'tblItems.*',
                DB::raw('(select image_path from item_images where item_images.ItemCode = tblItems.ItemCode order by id desc limit 1) as thumbnail_path')
            );

        $query = $this->applyShareFilter($query, $searchColumn, $searchValue);

        return $query->orderBy('ItemName')->get();
    }

    private function appendShareImages($items, string $placeholderImage, bool $inline = false): void
    {
        foreach ($items as $item) {
            $imagePath = $item->thumbnail_path ?? '';
            $item->imageUrl = $this->resolveShareImageUrl($imagePath, $placeholderImage);

            if ($inline) {
                $item->inlineImage = $this->buildInlineImageData($imagePath, $placeholderImage);
            }
        }
    }

    public function image($path)
    {
        $imagePath = rawurldecode($path);

        foreach ($this->candidateImagePaths($imagePath) as $fullPath) {
            if (File::exists($fullPath)) {
                return response()->file($fullPath);
            }
        }

        abort(404);
    }



public function getItems()
{
    $items = DB::table('tblItems')->select('ItemCode', 'ItemName')->get();

    return response()->json([
        'status' => 'success',
        'items' => $items
    ]);
}

    public function insertItems(Request $request)
        {
            $items = $request->input('items');
    
            if (!is_array($items) || empty($items)) {
                return response()->json(['status' => 'error', 'message' => 'Invalid data format']);
            }
    
            foreach ($items as $item) {
                DB::table('tblItems')->insert([
                    'Barcode' => $item['Barcode'] ?? null,
                    'ItemName' => $item['ItemName'] ?? null,
                ]);
            }
    
            return response()->json(['status' => 'success', 'message' => 'Items inserted successfully']);
        }
    
    
    public function getItem(Request $r) {
        if ($r->filled('ItemName')) {
            return \DB::table('tblItems')
                ->select('ItemCode', 'ItemName', 'CompanyName', 'UPurprice', 'USalprice','WUprice','DUprice','PacketSize')
                ->where('ItemName', 'like', '%' . $r->ItemName . '%')  // Match anywhere in ItemName
                ->orderBy('ItemName')
                ->limit(10)
                ->get();
        }
        return [];
    }
    
    public function search(Request $request)
        {
            $query = $request->get('q', '');
    
            $items = DB::table('tblItems')
                ->select('ItemCode', 'ItemName', 'SalePrice', 'PacketSize')
                ->where('ItemName', 'like', '%' . $query . '%')
                ->limit(20) // return only first 20 results
                ->get();
    
            return response()->json($items);
        }
    
    
    public function index(Request $request)
    {
        $search = $request->input('search');
        $searchColumn = $request->input('search_column', '');
        $searchValue = $request->input('search_value', '');

        // Column-to-DB-field mapping
        $columnMap = [
            'barcode'     => 'Barcode',
            'itemName'    => 'ItemName',
            'companyName' => 'CompanyName',
            'category'    => 'Category',
            'ppprice'     => 'PPurprice',
            'puprice'     => 'UPurprice',
            'usprice'     => 'USalprice',
            'psprice'     => 'RPprice',
            'wuprice'     => 'WUprice',
            'wpprice'     => 'WPprice',
            'duprice'     => 'DUprice',
            'dpprice'     => 'DPprice',
        ];

        $numericColumns = ['ppprice', 'puprice', 'usprice', 'psprice', 'wuprice', 'wpprice', 'duprice', 'dpprice'];

        // Fetch items (for table)
        $items = DB::table('tblItems')
            ->select(
                'tblItems.*',
                DB::raw('(select count(*) from item_images where item_images.ItemCode = tblItems.ItemCode) as images_count'),
                DB::raw('(select image_path from item_images where item_images.ItemCode = tblItems.ItemCode order by id desc limit 1) as thumbnail_path')
            )
            ->when($search, function ($query, $search) {
                return $query->where('ItemName', 'LIKE', '%' . $search . '%');
            })
            ->when($searchValue && $searchColumn && isset($columnMap[$searchColumn]), function ($query) use ($searchColumn, $searchValue, $columnMap, $numericColumns) {
                $dbField = $columnMap[$searchColumn];

                if ($searchColumn === 'image') {
                    // filter by has/no image handled differently
                    return $query;
                }

                if (in_array($searchColumn, $numericColumns)) {
                    // Range search: e.g. "100-200"
                    if (preg_match('/^(\d+(?:\.\d+)?)\s*-\s*(\d+(?:\.\d+)?)$/', $searchValue, $m)) {
                        return $query->whereBetween($dbField, [(float) $m[1], (float) $m[2]]);
                    }
                    // Operator search: e.g. ">=50"
                    if (preg_match('/^(>=|<=|>|<|=)\s*(\d+(?:\.\d+)?)$/', $searchValue, $m)) {
                        return $query->where($dbField, $m[1], (float) $m[2]);
                    }
                    // Exact numeric
                    if (is_numeric($searchValue)) {
                        return $query->where($dbField, (float) $searchValue);
                    }
                    return $query;
                }

                // Text search: LIKE
                return $query->where($dbField, 'LIKE', '%' . $searchValue . '%');
            })
            ->orderBy('ItemCode', 'desc')
            ->paginate(50);
    
        // Fetch all ItemNames for datalist
        $itemNames = DB::table('tblItems')->select('ItemName')->orderBy('ItemName')->get();
    
        $items->appends($request->only(['search', 'search_column', 'search_value']));
    
        return view('items.index', compact('items', 'itemNames', 'search', 'searchColumn', 'searchValue'));
    }



    public function create()
    {
        // Get the next item code
        $nextItemCode = DB::table('tblItems')->max('ItemCode') + 1;
        
        return view('items.create', compact('nextItemCode'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Barcode'         => 'nullable|string|max:255',
            'ItemName'        => 'required|string|max:255',
            'CompanyName'     => 'nullable|string|max:255',
            'UPurprice'       => 'nullable|numeric|min:0',
            'USalprice'       => 'nullable|numeric|min:0',
            'Category'        => 'nullable|string|max:255',
            'WUprice'         => 'nullable|numeric|min:0',
            'WPprice'         => 'nullable|numeric|min:0',
            'ItemNameUrdu'    => 'nullable|string|max:255',
            'PacketSize'      => 'nullable|string|max:100',
            'PriceCode'       => 'nullable|string|max:100',
            'PPurprice'       => 'nullable|numeric|min:0',
            'EPprice'         => 'nullable|numeric|min:0',
            'DPprice'         => 'nullable|numeric|min:0',
            'DUprice'         => 'nullable|numeric|min:0',
            'ItemDescription' => 'nullable|string',
            'RPprice'         => 'nullable|numeric|min:0',
            'PType'           => 'nullable|string|max:100',
            'PTypeUrdu'       => 'nullable|string|max:255',
            'UType'           => 'nullable|string|max:100',
            'UTypeUrdu'       => 'nullable|string|max:255',
            'images'          => 'nullable|array',
            'images.*'        => 'file|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
            'captured_images' => 'nullable|array',
            'captured_images.*' => 'string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // ItemCode is auto-generated by tblItems.
            $itemCode = DB::table('tblItems')->insertGetId([
                'Barcode'         => $request->Barcode,
                'ItemName'        => $request->ItemName,
                'CompanyName'     => $request->CompanyName,
                'UPurprice'       => $request->UPurprice,
                'USalprice'       => $request->USalprice,
                'Category'        => $request->Category,
                'WUprice'         => $request->WUprice,
                'WPprice'         => $request->WPprice,
                'ItemNameUrdu'    => $request->ItemNameUrdu,
                'PacketSize'      => $request->PacketSize,
                'PriceCode'       => $request->PriceCode,
                'PPurprice'       => $request->PPurprice,
                'EPprice'         => $request->EPprice,
                'DPprice'         => $request->DPprice,
                'DUprice'         => $request->DUprice,
                'ItemDescription' => $request->ItemDescription,
                'RPprice'         => $request->RPprice,
                'PType'           => $request->PType,
                'PTypeUrdu'       => $request->PTypeUrdu,
                'UType'           => $request->UType,
                'UTypeUrdu'       => $request->UTypeUrdu,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            $this->saveItemImages($request, $itemCode);

            DB::commit();

            return redirect()->route('items.index')
                ->with('success', 'Item #' . $itemCode . ' created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error creating item: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $item = DB::table('tblItems')
            ->where('ItemCode', $id)
            ->first();
        $images = DB::table('item_images')
            ->where('ItemCode', $id)
            ->orderByDesc('id')
            ->get();

        if (!$item) {
            abort(404, 'Item not found');
        }

        return view('items.show', compact('item', 'images'));
    }

    public function shareView($id)
    {
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

        $placeholderImage = $this->sharePlaceholderImage();

        $imagePath = $latestImage?->image_path;
        $shareImage = $imagePath
            ? route('items.image', ['path' => ltrim($imagePath, '/')])
            : $placeholderImage;

        return view('items.share', compact('item', 'shareImage', 'placeholderImage'));
    }

    public function sharePdf($id)
    {
        $item = DB::table('tblItems')
            ->where('ItemCode', $id)
            ->first();
        $latestImage = DB::table('item_images')
            ->where('ItemCode', $id)
            ->orderByDesc('id')
            ->first();

        if (!$item) {
            return redirect()->route('items.index')
                ->with('error', 'Item not found');
        }

        $placeholderImage = $this->sharePlaceholderImage();
        $inlineImage = $this->buildInlineImageData($latestImage?->image_path, $placeholderImage);

        $pdf = Pdf::loadView('items.share-pdf', [
            'item' => $item,
            'inlineImage' => $inlineImage,
        ]);

        $fileName = 'item-' . $item->ItemCode . '-' . now()->format('YmdHis') . '.pdf';
        $relativePath = 'item-pdfs/' . $fileName;
        Storage::disk('public')->put($relativePath, $pdf->output());

        $publicUrl = asset('storage/' . $relativePath);
        $message = "Item: {$item->ItemName}\nPDF: {$publicUrl}";
        $whatsAppUrl = 'https://wa.me/?text=' . urlencode($message);

        return redirect()->away($whatsAppUrl);
    }

    public function shareAll(Request $request)
    {
        $searchColumn = $request->input('search_column', '');
        $searchValue = $request->input('search_value', '');

        $items = $this->fetchShareAllItems($searchColumn, $searchValue);
        $placeholderImage = $this->sharePlaceholderImage();
        $this->appendShareImages($items, $placeholderImage);

        $filterLabel = $this->shareFilterLabel($searchColumn, $searchValue);
        $shareParams = $this->shareQueryParams($searchColumn, $searchValue);

        $sharePageUrl = route('items.shareAll', $shareParams);
        $sharePdfUrl = route('items.shareAllPdf', $shareParams);
        $shareWhatsappUrl = route('items.shareAllWhatsapp', $shareParams);

        return view('items.share-all', [
            'items' => $items,
            'placeholderImage' => $placeholderImage,
            'filterLabel' => $filterLabel,
            'sharePageUrl' => $sharePageUrl,
            'sharePdfUrl' => $sharePdfUrl,
            'shareWhatsappUrl' => $shareWhatsappUrl,
        ]);
    }

    public function shareAllWhatsapp(Request $request)
    {
        $searchColumn = $request->input('search_column', '');
        $searchValue = $request->input('search_value', '');

        $filterLabel = $this->shareFilterLabel($searchColumn, $searchValue);
        $sharePageUrl = route('items.shareAll', $this->shareQueryParams($searchColumn, $searchValue));

        $message = "Items list ({$filterLabel})\n{$sharePageUrl}";

        return redirect()->away('https://wa.me/?text=' . urlencode($message));
    }

    public function shareAllPdf(Request $request)
    {
        $searchColumn = $request->input('search_column', '');
        $searchValue = $request->input('search_value', '');

        $items = $this->fetchShareAllItems($searchColumn, $searchValue);
        $placeholderImage = $this->sharePlaceholderImage();
        $this->appendShareImages($items, $placeholderImage, true);

        $filterLabel = $this->shareFilterLabel($searchColumn, $searchValue);
        $fileName = 'items-' . now()->format('YmdHis') . '.pdf';

        $pdf = Pdf::loadView('items.share-all-pdf', [
            'items' => $items,
            'filterLabel' => $filterLabel,
        ]);

        return $pdf->download($fileName);
    }

    public function edit($id)
    {
        $item = DB::table('tblItems')
            ->where('ItemCode', $id)
            ->first();

        if (!$item) {
            return redirect()->route('items.index')
                ->with('error', 'Item not found');
        }

        return view('items.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'Barcode'         => 'nullable|string|max:255',
            'ItemName'        => 'required|string|max:255',
            'CompanyName'     => 'nullable|string|max:255',
            'UPurprice'       => 'nullable|numeric|min:0',
            'USalprice'       => 'nullable|numeric|min:0',
            'Category'        => 'nullable|string|max:255',
            'WUprice'         => 'nullable|numeric|min:0',
            'WPprice'         => 'nullable|numeric|min:0',
            'ItemNameUrdu'    => 'nullable|string|max:255',
            'PacketSize'      => 'nullable|string|max:100',
            'PriceCode'       => 'nullable|string|max:100',
            'PPurprice'       => 'nullable|numeric|min:0',
            'EPprice'         => 'nullable|numeric|min:0',
            'DPprice'         => 'nullable|numeric|min:0',
            'DUprice'         => 'nullable|numeric|min:0',
            'ItemDescription' => 'nullable|string',
            'RPprice'         => 'nullable|numeric|min:0',
            'PType'           => 'nullable|string|max:100',
            'PTypeUrdu'       => 'nullable|string|max:255',
            'UType'           => 'nullable|string|max:100',
            'UTypeUrdu'       => 'nullable|string|max:255',
            'images'          => 'nullable|array',
            'images.*'        => 'file|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
            'captured_images' => 'nullable|array',
            'captured_images.*' => 'string',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        try {
            DB::beginTransaction();

            DB::table('tblItems')
                ->where('ItemCode', $id)
                ->update([
                    'Barcode'         => $request->Barcode,
                    'ItemName'        => $request->ItemName,
                    'CompanyName'     => $request->CompanyName,
                    'UPurprice'       => $request->UPurprice,
                    'USalprice'       => $request->USalprice,
                    'Category'        => $request->Category,
                    'WUprice'         => $request->WUprice,
                    'WPprice'         => $request->WPprice,
                    'ItemNameUrdu'    => $request->ItemNameUrdu,
                    'PacketSize'      => $request->PacketSize,
                    'PriceCode'       => $request->PriceCode,
                    'PPurprice'       => $request->PPurprice,
                    'EPprice'         => $request->EPprice,
                    'DPprice'         => $request->DPprice,
                    'DUprice'         => $request->DUprice,
                    'ItemDescription' => $request->ItemDescription,
                    'RPprice'         => $request->RPprice,
                    'PType'           => $request->PType,
                    'PTypeUrdu'       => $request->PTypeUrdu,
                    'UType'           => $request->UType,
                    'UTypeUrdu'       => $request->UTypeUrdu,
                    'updated_at'      => now(),
                ]);

            $this->saveItemImages($request, $id);

            DB::commit();
    
            return redirect()->route('items.index')
                ->with('success', "Item #{$id} updated successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating item: ' . $e->getMessage());
    
            return redirect()->back()
                ->with('error', 'Something went wrong while updating the item.')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $images = DB::table('item_images')
                ->where('ItemCode', $id)
                ->get();

            foreach ($images as $image) {
                $this->deleteImageFile($image->image_path);
            }

            DB::table('item_images')
                ->where('ItemCode', $id)
                ->delete();

            DB::table('tblItems')
                ->where('ItemCode', $id)
                ->delete();

            return redirect()->route('items.index')
                ->with('success', 'Item #' . $id . ' deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('items.index')
                ->with('error', 'Error deleting item: ' . $e->getMessage());
        }
    }

    public function storeImages(Request $request, $id)
    {
        $request->validate([
            'images' => 'required|array|min:1',
            'images.*' => 'file|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
        ]);

        $item = DB::table('tblItems')->where('ItemCode', $id)->first();

        if (!$item) {
            return redirect()->route('items.index')->with('error', 'Item not found');
        }

        $this->saveItemImages($request, $id);

        return redirect()->route('items.show', $id)->with('success', 'Images uploaded successfully.');
    }

    public function updateImage(Request $request, $imageId)
    {
        $request->validate([
            'image' => 'required|file|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
        ]);

        $image = DB::table('item_images')->where('id', $imageId)->first();

        if (!$image) {
            return redirect()->route('items.index')->with('error', 'Image not found');
        }

        $this->deleteImageFile($image->image_path);

        $destinationDir = public_path('item-images/' . $image->ItemCode);

        if (!File::exists($destinationDir)) {
            File::makeDirectory($destinationDir, 0755, true);
        }

        $fileName = now()->format('YmdHis') . '_' . Str::random(8) . '.' . $request->file('image')->getClientOriginalExtension();
        $request->file('image')->move($destinationDir, $fileName);

        DB::table('item_images')
            ->where('id', $imageId)
            ->update([
                'image_path' => 'item-images/' . $image->ItemCode . '/' . $fileName,
                'updated_at' => now(),
            ]);

        return redirect()->route('items.show', $image->ItemCode)->with('success', 'Image updated successfully.');
    }

    public function destroyImage($imageId)
    {
        $image = DB::table('item_images')->where('id', $imageId)->first();

        if (!$image) {
            return redirect()->route('items.index')->with('error', 'Image not found');
        }

        $this->deleteImageFile($image->image_path);

        DB::table('item_images')->where('id', $imageId)->delete();

        return redirect()->route('items.show', $image->ItemCode)->with('success', 'Image deleted successfully.');
    }
}