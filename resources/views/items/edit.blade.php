<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item - #{{ $item->ItemCode }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background: linear-gradient(45deg, #4e73df, #2e59d9);
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }
        .btn-primary {
            background: linear-gradient(45deg, #4e73df, #2e59d9);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(45deg, #2e59d9, #4e73df);
        }
        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        .item-code-display {
            background-color: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
<form action="{{ route('items.update', $item->ItemCode) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="item-code-display">
                <i class="fas fa-barcode"></i> Item Code: #{{ $item->ItemCode }}
                <br>
                <small class="text-muted">Auto-generated and cannot be changed</small>
            </div>
        </div>
    </div>

    {{-- Barcode & Category --}}
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="Barcode" class="form-label">Barcode</label>
            <input type="text" class="form-control" id="Barcode" name="Barcode"
                   value="{{ old('Barcode', $item->Barcode) }}" placeholder="Enter barcode">
        </div>
        <div class="col-md-6">
            <label for="Category" class="form-label">Category</label>
            <input type="text" class="form-control" id="Category" name="Category"
                   value="{{ old('Category', $item->Category) }}" placeholder="Enter category">
        </div>
    </div>

    {{-- Company --}}
    <div class="row mb-3">
        <div class="col-md-12">
            <label for="CompanyName" class="form-label">Company Name *</label>
            <input type="text" class="form-control" id="CompanyName" name="CompanyName"
                   value="{{ old('CompanyName', $item->CompanyName) }}" required>
        </div>
    </div>

    {{-- Item Names --}}
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="ItemName" class="form-label">Item Name *</label>
            <input type="text" class="form-control" id="ItemName" name="ItemName"
                   value="{{ old('ItemName', $item->ItemName) }}" required>
        </div>
        <div class="col-md-6">
            <label for="ItemNameUrdu" class="form-label">Item Name (Urdu)</label>
            <input type="text" class="form-control" id="ItemNameUrdu" name="ItemNameUrdu"
                   value="{{ old('ItemNameUrdu', $item->ItemNameUrdu) }}">
        </div>
    </div>

    {{-- Packet Size & Price Code --}}
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="PacketSize" class="form-label">Packet Size</label>
            <input type="text" class="form-control" id="PacketSize" name="PacketSize"
                   value="{{ old('PacketSize', $item->PacketSize) }}">
        </div>
        <div class="col-md-6">
            <label for="PriceCode" class="form-label">Price Code</label>
            <input type="text" class="form-control" id="PriceCode" name="PriceCode"
                   value="{{ old('PriceCode', $item->PriceCode) }}">
        </div>
    </div>

    {{-- Prices --}}
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="UPurprice" class="form-label">Unit Purchase Price</label>
            <input type="number" class="form-control" id="UPurprice" name="UPurprice"
                   value="{{ old('UPurprice', $item->UPurprice) }}" step="0.01" min="0">
        </div>
        <div class="col-md-4">
            <label for="USalprice" class="form-label">Unit Sale Price</label>
            <input type="number" class="form-control" id="USalprice" name="USalprice"
                   value="{{ old('USalprice', $item->USalprice) }}" step="0.01" min="0">
        </div>
        <div class="col-md-4">
            <label for="PPurprice" class="form-label">Purchase Price (P)</label>
            <input type="number" class="form-control" id="PPurprice" name="PPurprice"
                   value="{{ old('PPurprice', $item->PPurprice) }}" step="0.01" min="0">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-3">
            <label for="WUprice" class="form-label">Wholesale Unit Price</label>
            <input type="number" class="form-control" id="WUprice" name="WUprice"
                   value="{{ old('WUprice', $item->WUprice) }}" step="0.01" min="0">
        </div>
        <div class="col-md-3">
            <label for="WPprice" class="form-label">Wholesale Pack Price</label>
            <input type="number" class="form-control" id="WPprice" name="WPprice"
                   value="{{ old('WPprice', $item->WPprice) }}" step="0.01" min="0">
        </div>
        <div class="col-md-2">
            <label for="EPprice" class="form-label">E Price</label>
            <input type="number" class="form-control" id="EPprice" name="EPprice"
                   value="{{ old('EPprice', $item->EPprice) }}" step="0.01" min="0">
        </div>
        <div class="col-md-2">
            <label for="DPprice" class="form-label">D Price</label>
            <input type="number" class="form-control" id="DPprice" name="DPprice"
                   value="{{ old('DPprice', $item->DPprice) }}" step="0.01" min="0">
        </div>
        <div class="col-md-2">
            <label for="DUprice" class="form-label">DU Price</label>
            <input type="number" class="form-control" id="DUprice" name="DUprice"
                   value="{{ old('DUprice', $item->DUprice) }}" step="0.01" min="0">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <label for="RPprice" class="form-label">Retail Price</label>
            <input type="number" class="form-control" id="RPprice" name="RPprice"
                   value="{{ old('RPprice', $item->RPprice) }}" step="0.01" min="0">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-12">
            <label for="images" class="form-label">Add More Item Images</label>
            <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
            <div class="form-text">Upload additional images here. Use the View page to delete or replace existing images.</div>
        </div>
    </div>

    {{-- Description --}}
    <div class="row mb-3">
        <div class="col-md-12">
            <label for="ItemDescription" class="form-label">Item Description</label>
            <textarea class="form-control" id="ItemDescription" name="ItemDescription"
                      rows="3">{{ old('ItemDescription', $item->ItemDescription) }}</textarea>
        </div>
    </div>

    {{-- Types --}}
    <div class="row mb-3">
        <div class="col-md-3">
            <label for="PType" class="form-label">P Type</label>
            <input type="text" class="form-control" id="PType" name="PType"
                   value="{{ old('PType', $item->PType) }}">
        </div>
        <div class="col-md-3">
            <label for="PTypeUrdu" class="form-label">P Type (Urdu)</label>
            <input type="text" class="form-control" id="PTypeUrdu" name="PTypeUrdu"
                   value="{{ old('PTypeUrdu', $item->PTypeUrdu) }}">
        </div>
        <div class="col-md-3">
            <label for="UType" class="form-label">U Type</label>
            <input type="text" class="form-control" id="UType" name="UType"
                   value="{{ old('UType', $item->UType) }}">
        </div>
        <div class="col-md-3">
            <label for="UTypeUrdu" class="form-label">U Type (Urdu)</label>
            <input type="text" class="form-control" id="UTypeUrdu" name="UTypeUrdu"
                   value="{{ old('UTypeUrdu', $item->UTypeUrdu) }}">
        </div>
    </div>

    {{-- Submit --}}
    <div class="row">
        <div class="col-md-12">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('items.index') }}" class="btn btn-secondary me-md-2">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Item
                </button>
                <button type="reset" class="btn btn-outline-secondary">
                    <i class="fas fa-redo"></i> Reset
                </button>
            </div>
        </div>
    </div>
</form>


</body>
</html>