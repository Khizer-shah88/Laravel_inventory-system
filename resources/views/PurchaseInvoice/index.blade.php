@extends('layouts.app')

@section('page_title', 'List Of Purchase Invoices')

@section('content')

@push('styles')
<style>
    .table td, .table th {
        padding-left: 1rem;   /* left */
        padding-right: 1rem;  /* right */
        padding-top: 0.1rem;       /* optional */
        padding-bottom: 0.1rem;    /* optional */
    }
</style>
@endpush


<div class="container">
<div class="row align-items-center">
    <div class="col-2 mb-2">
        <a href="{{ route('PurchaseInvoice.create') }}" class="btn btn-primary btn-sm">+ Create Invoice</a>
    </div>
    <div class="col-10">
        <div class="d-flex justify-content-end">
            {{-- Search Form --}}
            <form action="{{ route('PurchaseInvoice.index') }}" method="GET" class="d-flex align-items-center flex-nowrap gap-1">

                {{-- Radio Buttons --}}
               <div class="d-flex align-items-center flex-nowrap">
    <label class="form-check form-check-inline mb-0 me-2">
        <input class="form-check-input" type="radio" name="search_by" value="InvNo"
               {{ request('search_by', 'InvNo') == 'InvNo' ? 'checked' : '' }}>
        <span class="ms-1 text-nowrap">Inv No</span>
    </label>

    <label class="form-check form-check-inline mb-0 me-2">
        <input class="form-check-input" type="radio" name="search_by" value="AccountName"
               {{ request('search_by') == 'AccountName' ? 'checked' : '' }}>
        <span class="ms-1 text-nowrap">Account Name</span>
    </label>

    <label class="form-check form-check-inline mb-0 me-2">
        <input class="form-check-input" type="radio" name="search_by" value="InvDate"
               {{ request('search_by') == 'InvDate' ? 'checked' : '' }}>
        <span class="ms-1 text-nowrap">Inv Date</span>
    </label>
</div>


                {{-- Search Input --}}
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Enter search value..." value="{{ request('search') }}" style="max-width: 200px;">

                {{-- Search Button --}}
                <button class="btn btn-outline-secondary btn-sm">Search</button>
            </form>
        </div>
    </div>
</div>




    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    
    <table class="table table-bordered table-sm table-striped">
    <thead>
        <tr>
            <th  class="text-center">InvNo</th>
            <th style="width:15%;">InvDate</th>
            <th>Account Name</th>
            <th class="text-end">Gross</th>
            <th class="text-end">Disc</th>
            <th class="text-end">Cash Discount</th>
            <th class="text-end">Net</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($invoices as $inv)
        <tr>
            <td class="text-center">{{ $inv->InvNo }}</td>
            <td>{{ \Carbon\Carbon::parse($inv->InvDate)->format('d-m-Y') }}</td>
            <td>{{ $inv->AccountName }}</td>
            <td class="text-end">{{ number_format($inv->Gross, 0) }}</td>
            <td class="text-end">{{ number_format($inv->Disc, 0) }}</td>
            <td class="text-end">{{ number_format($inv->CashDiscount, 0) }}</td>
            <td class="text-end">{{ number_format($inv->Gross - $inv->Disc - $inv->CashDiscount, 0) }}</td>
            <td>
    <a href="{{ route('PurchaseInvoice.edit', $inv->InvNo) }}" title="Edit" style="text-decoration: none;">
        <i class="fa fa-pencil-alt text-danger"></i> Edit
    </a>
    &nbsp; | &nbsp;
<a href="#" 
   onclick="openInvoice('{{ route('PurchaseInvoice.show', $inv->InvNo) }}'); return false;" 
   title="Print" 
   style="text-decoration: none;">
    <i class="fa fa-print text-primary"></i> Print
</a>
    &nbsp; | &nbsp;
<a href="{{ route('PurchaseInvoice.showA4', $inv->InvNo) }}" target="_blank">
    Print A4
</a>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="fw-bold">
            <td colspan="3" class="text-end">Total:</td>
            <td  class="text-end">{{ number_format($totals['Gross'], 0) }}</td>
            <td  class="text-end">{{ number_format($totals['Disc'], 0) }}</td>
            <td>-</td>
            <td  class="text-end">{{ number_format($totals['Net'], 0) }}</td>
            <td></td>
        </tr>
    </tfoot>
</table>

<div class="d-flex justify-content-between align-items-center mt-2">
    <div>
        Showing {{ $invoices->firstItem() }} to {{ $invoices->lastItem() }} of {{ $invoices->total() }} results
    </div>
    <div>
        {{ $invoices->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>


</div>

@push('scripts')
<script>
function openInvoice(url) {
    window.open(url, 'PrintInvoice', 'width=600,height=600,scrollbars=no,menubar=no,toolbar=no,location=no,status=no');
}
</script>
@endpush
@endsection
