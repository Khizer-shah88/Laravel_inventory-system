@extends('layouts.app')
@section('page_title', 'List Of Reports')
@section('content')
<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">📊 Reports Dashboard</h5>
                </div>

                <div class="card-body">
                    <ol class="list-group list-group-numbered">
                        <li class="list-group-item">
                            <a href="{{ url('reports/balance-sheet') }}" class="text-decoration-none text-dark fw-semibold">
                                Balance Sheet
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="{{ url('reports/expense') }}" class="text-decoration-none text-dark fw-semibold">
                                Total Expense
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="{{ url('reports/bank') }}" class="text-decoration-none text-dark fw-semibold">
                                Banks Balance
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="{{ url('reports/personal_loan') }}" class="text-decoration-none text-dark fw-semibold">
                                Personal Loan
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="{{ url('reports/staff_loan') }}" class="text-decoration-none text-dark fw-semibold">
                                Staff Loan
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="{{ url('reports/customer_receivables') }}" class="text-decoration-none text-dark fw-semibold">
                                Customers Receivables
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="{{ url('reports/company_payables') }}" class="text-decoration-none text-dark fw-semibold">
                                Companies Payables
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="{{ url('reports/company_claim') }}" class="text-decoration-none text-dark fw-semibold">
                                Company Claim
                            </a>
                        </li>
                    </ol>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
