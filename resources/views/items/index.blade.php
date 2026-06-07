@extends('layouts.app')
@push('styles')

<style>
    /* Make the table content stack vertically on small screens */
    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto;
        }

        .table thead {
            display: none; /* Hide table headers */
        }

        .table td {
            display: block;
            text-align: right; /* Align the data to the right */
            padding-left: 50%;
            position: relative;
        }

        .table td::before {
            content: attr(data-label);
            position: absolute;
            left: 0;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Optionally, style the row content better */
        .table td:nth-child(odd) {
            background-color: #f8f9fa;
        }

        .table td:last-child {
            border-bottom: none; /* Remove border for the last column */
        }
    }

    .item-thumb {
        cursor: zoom-in;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .item-thumb:hover {
        transform: scale(1.03);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .item-row {
        cursor: pointer;
    }

    .item-row:hover {
        background-color: rgba(13, 110, 253, 0.04);
    }

    .inventory-filter {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
    }

    .inventory-filter label {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #475569;
        margin-bottom: 4px;
    }

    .inventory-filter .form-select,
    .inventory-filter .form-control {
        min-height: 42px;
        border-radius: 10px;
        border: 1px solid #cbd5f5;
        box-shadow: none;
    }

    .inventory-filter .form-select:focus,
    .inventory-filter .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.15);
    }

    .inventory-filter .btn-clear {
        background: #eef2ff;
        color: #1e3a8a;
        border: 1px solid #c7d2fe;
        border-radius: 10px;
        font-weight: 700;
        min-height: 42px;
        padding: 0 18px;
    }

    .inventory-filter .btn-clear:hover {
        background: #e0e7ff;
        color: #1d4ed8;
    }
</style>
@endpush
@section('page_title', 'List Of Items')

@section('content')

    <div class="container-fluid ">
        @php
            $placeholderImage = 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 120 120"><rect width="120" height="120" fill="#f2f4f7"/><rect x="8" y="8" width="104" height="104" rx="10" fill="#ffffff" stroke="#d0d7de" stroke-dasharray="6 5"/><text x="60" y="63" text-anchor="middle" font-family="Arial, sans-serif" font-size="14" fill="#98a2b3">No image</text></svg>');

            $resolveThumbUrl = function ($path) use ($placeholderImage) {
                $path = trim((string) $path);

                if ($path === '') {
                    return $placeholderImage;
                }

                if (Str::startsWith($path, ['http://', 'https://'])) {
                    return $path;
                }

                return route('items.image', ['path' => ltrim($path, '/')]);
            };

            $shareAllParams = array_filter([
                'search_column' => $searchColumn ?? '',
                'search_value' => $searchValue ?? '',
            ], function ($value) {
                return $value !== null && $value !== '';
            });
            $shareAllLabel = ($searchColumn ?? '') !== '' && ($searchValue ?? '') !== ''
                ? 'Share Filtered'
                : 'Share All';
            $shareAllUrl = route('items.shareAll', $shareAllParams);
        @endphp

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
            <a href="{{ route('items.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Item
            </a>
            <form method="GET" action="{{ route('items.index') }}" class="inventory-filter flex-grow-1" id="inventoryFilterForm">
                <div class="d-flex flex-column" style="min-width: 210px;">
                    <label for="inventoryFilterColumn">Filter Column</label>
                    <select class="form-select" id="inventoryFilterColumn" name="search_column">
                        <option value="image" {{ ($searchColumn ?? '') === 'image' ? 'selected' : '' }}>Image</option>
                        <option value="barcode" {{ ($searchColumn ?? '') === 'barcode' ? 'selected' : '' }}>Barcode</option>
                        <option value="itemName" {{ ($searchColumn ?? '') === 'itemName' || empty($searchColumn ?? '') ? 'selected' : '' }}>Item Name</option>
                        <option value="ppprice" {{ ($searchColumn ?? '') === 'ppprice' ? 'selected' : '' }}>PPprice</option>
                        <option value="puprice" {{ ($searchColumn ?? '') === 'puprice' ? 'selected' : '' }}>PUprice</option>
                        <option value="usprice" {{ ($searchColumn ?? '') === 'usprice' ? 'selected' : '' }}>USprice</option>
                        <option value="psprice" {{ ($searchColumn ?? '') === 'psprice' ? 'selected' : '' }}>PSprice</option>
                        <option value="wuprice" {{ ($searchColumn ?? '') === 'wuprice' ? 'selected' : '' }}>WUprice</option>
                        <option value="wpprice" {{ ($searchColumn ?? '') === 'wpprice' ? 'selected' : '' }}>WPprice</option>
                        <option value="duprice" {{ ($searchColumn ?? '') === 'duprice' ? 'selected' : '' }}>DUprice</option>
                        <option value="dpprice" {{ ($searchColumn ?? '') === 'dpprice' ? 'selected' : '' }}>DPprice</option>
                        <option value="companyName" {{ ($searchColumn ?? '') === 'companyName' ? 'selected' : '' }}>Company Name</option>
                        <option value="category" {{ ($searchColumn ?? '') === 'category' ? 'selected' : '' }}>Category</option>
                    </select>
                </div>
                <div class="d-flex flex-column flex-grow-1" style="min-width: 240px;">
                    <label for="inventoryFilterValue">Search Value</label>
                    <input type="text" class="form-control" id="inventoryFilterValue" name="search_value" value="{{ $searchValue ?? '' }}" placeholder="Type a value to filter">
                </div>
                <div class="d-flex flex-column">
                    <label class="invisible">Search</label>
                    <button type="submit" class="btn btn-primary" style="min-height:42px; border-radius:10px; font-weight:700; padding:0 18px;">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
                <div class="d-flex flex-column">
                    <label class="invisible">Clear</label>
                    <a href="{{ route('items.index') }}" class="btn btn-clear" id="inventoryFilterClear">Clear</a>
                </div>
                <div class="d-flex flex-column">
                    <label class="invisible">Share</label>
                    <a href="{{ $shareAllUrl }}"
                       class="btn btn-outline-primary"
                       style="min-height:42px; border-radius:10px; font-weight:700; padding:0 18px;"
                       target="_blank" rel="noopener">
                        <i class="fas fa-share-alt"></i> {{ $shareAllLabel }}
                    </a>
                </div>
            </form>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

       <div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm">
                <thead>
                    <tr>
                        <th style="width: 72px;">Image</th>
                        <th>Barcode</th>
                        <th>Item Name</th>
                        <th class="text-end">PPprice</th>
                        <th class="text-end">PUprice</th>
                        <th class="text-end">USprice</th>
                        <th class="text-end">PSprice</th>
                        <th class="text-end">WUprice</th>
                        <th class="text-end">WPprice</th>
                        <th class="text-end">DUprice</th>
                        <th class="text-end">DPprice</th>
                        <th>Company Name</th>
                        <th>Category</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr class="d-block d-md-table-row item-row"
                            tabindex="0"
                            role="button"
                            data-filter-row="true"
                            data-image="{{ $item->thumbnail_path ? 'yes' : 'no' }}"
                            data-barcode="{{ $item->Barcode ?? '' }}"
                            data-item-name="{{ $item->ItemName }}"
                            data-ppprice="{{ $item->PPurprice ?? 0 }}"
                            data-puprice="{{ $item->UPurprice ?? 0 }}"
                            data-usprice="{{ $item->USalprice ?? 0 }}"
                            data-psprice="{{ $item->RPprice ?? 0 }}"
                            data-wuprice="{{ $item->WUprice ?? 0 }}"
                            data-wpprice="{{ $item->WPprice ?? 0 }}"
                            data-duprice="{{ $item->DUprice ?? 0 }}"
                            data-dpprice="{{ $item->DPprice ?? 0 }}"
                            data-company-name="{{ $item->CompanyName ?? '' }}"
                            data-category="{{ $item->Category ?? '' }}"
                            data-item-image="{{ $resolveThumbUrl($item->thumbnail_path ?? '') }}">
                            <td class="d-block d-md-table-cell align-middle">
                                @php
                                    $thumbUrl = $resolveThumbUrl($item->thumbnail_path ?? '');
                                @endphp

                                  <img src="{{ $thumbUrl }}"
                                      alt="{{ $item->ItemName }}"
                                      class="item-thumb"
                                      style="width: 56px; height: 56px; object-fit: cover; border-radius: 8px; border: 1px solid #d9d9d9; background: #fff;"
                                      onerror="this.src='{{ $placeholderImage }}'">
                            </td>
                            <td class="d-block d-md-table-cell">{{ $item->Barcode }}</td>
                            <td class="d-block d-md-table-cell">{{ Str::limit($item->ItemName, 40) }}</td>
                            <td class="text-end d-block d-md-table-cell">{{ number_format($item->PPurprice, 2) }}</td>
                            <td class="text-end d-block d-md-table-cell">{{ number_format($item->UPurprice, 2) }}</td>
                            <td class="text-end d-block d-md-table-cell">{{ number_format($item->USalprice, 2) }}</td>
                            <td class="text-end d-block d-md-table-cell">{{ number_format($item->RPprice, 2) }}</td>
                            <td class="text-end d-block d-md-table-cell">{{ number_format($item->WUprice, 2) }}</td>
                            <td class="text-end d-block d-md-table-cell">{{ number_format($item->WPprice, 2) }}</td>
                            <td class="text-end d-block d-md-table-cell">{{ number_format($item->DUprice, 2) }}</td>
                            <td class="text-end d-block d-md-table-cell">{{ number_format($item->DPprice, 2) }}</td>
                            <td class="d-block d-md-table-cell">{{ Str::limit($item->CompanyName, 20) }}</td>
                            <td class="d-block d-md-table-cell">{{ Str::limit($item->Category, 15) }}</td>
                            <td class="text-end d-block d-md-table-cell">
                                <a href="{{ route('items.share', $item->ItemCode) }}"
                                   class="btn btn-sm btn-outline-secondary me-2"
                                   title="Share item"
                                   target="_blank" rel="noopener">
                                    <i class="fas fa-share-alt"></i>
                                </a>
                                <a href="{{ route('items.show', $item->ItemCode) }}"
                                   class="btn btn-sm btn-outline-success me-2"
                                   title="View images">
                                    <i class="fas fa-images"></i>
                                    <span class="ms-1">{{ $item->images_count ?? 0 }}</span>
                                </a>

                                <a href="{{ route('items.edit', $item->ItemCode) }}" 
                                   title="Edit"
                                   style="color: #0d6efd; text-decoration: none; margin-right: 10px;">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('items.destroy', $item->ItemCode) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this item and its images?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link p-0 align-baseline text-danger" title="Delete item" style="text-decoration: none;">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="14" class="text-center">No items found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-between align-items-center mt-2">
                <div>
                    Showing {{ $items->firstItem() }} to {{ $items->lastItem() }} of {{ $items->total() }} results
                </div>
                <div>
                    {{ $items->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>



    </div>

    <div class="modal fade" id="itemImageModal" tabindex="-1" aria-labelledby="itemImageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="itemImageModalLabel">Item Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="itemImageModalPreview" src="" alt="Item image" class="img-fluid rounded" style="max-height: 75vh; object-fit: contain;">
                    <div id="itemImageModalEmpty" class="text-muted d-none py-5">No image available</div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        (function () {
            const init = function () {
            const filterColumn = document.getElementById('inventoryFilterColumn');
            const filterValue = document.getElementById('inventoryFilterValue');
            const filterClear = document.getElementById('inventoryFilterClear');
            const filterRows = Array.from(document.querySelectorAll('tr[data-filter-row]'));
            const numericColumns = new Set(['ppprice', 'puprice', 'usprice', 'psprice', 'wuprice', 'wpprice', 'duprice', 'dpprice']);
            const toDataAttr = function (column) {
                return 'data-' + String(column || '').replace(/[A-Z]/g, function (match) {
                    return '-' + match.toLowerCase();
                });
            };
            const columnIndexMap = {
                image: 0,
                barcode: 1,
                itemName: 2,
                ppprice: 3,
                puprice: 4,
                usprice: 5,
                psprice: 6,
                wuprice: 7,
                wpprice: 8,
                duprice: 9,
                dpprice: 10,
                companyName: 11,
                category: 12
            };

            const placeholders = {
                image: 'Type yes or no',
                barcode: 'Enter barcode',
                itemName: 'Search item name',
                ppprice: 'e.g. 100, >=50, 100-200',
                puprice: 'e.g. 100, >=50, 100-200',
                usprice: 'e.g. 100, >=50, 100-200',
                psprice: 'e.g. 100, >=50, 100-200',
                wuprice: 'e.g. 100, >=50, 100-200',
                wpprice: 'e.g. 100, >=50, 100-200',
                duprice: 'e.g. 100, >=50, 100-200',
                dpprice: 'e.g. 100, >=50, 100-200',
                companyName: 'Search company name',
                category: 'Search category'
            };

            const normalize = function (value) {
                return String(value || '').toLowerCase().trim();
            };

            const parseNumeric = function (value) {
                const cleaned = String(value || '').replace(/,/g, '').trim();
                const parsed = Number.parseFloat(cleaned);
                return Number.isNaN(parsed) ? null : parsed;
            };

            const matchesNumeric = function (rawValue, query) {
                const value = parseNumeric(rawValue);
                const trimmed = String(query || '').trim();

                if (!trimmed) {
                    return true;
                }

                if (value === null) {
                    return false;
                }

                const rangeMatch = trimmed.match(/^(\d+(?:\.\d+)?)\s*-\s*(\d+(?:\.\d+)?)$/);
                if (rangeMatch) {
                    const min = Number.parseFloat(rangeMatch[1]);
                    const max = Number.parseFloat(rangeMatch[2]);
                    return value >= min && value <= max;
                }

                const opMatch = trimmed.match(/^(>=|<=|>|<|=)\s*(\d+(?:\.\d+)?)$/);
                if (opMatch) {
                    const op = opMatch[1];
                    const target = Number.parseFloat(opMatch[2]);
                    if (op === '>=') { return value >= target; }
                    if (op === '<=') { return value <= target; }
                    if (op === '>') { return value > target; }
                    if (op === '<') { return value < target; }
                    return value === target;
                }

                const exact = parseNumeric(trimmed);
                return exact !== null ? value === exact : false;
            };

            const matchesImage = function (rawValue, query) {
                const value = normalize(rawValue);
                const term = normalize(query);

                if (!term) {
                    return true;
                }

                if (['yes', 'y', 'has', 'with', '1'].includes(term)) {
                    return value === 'yes';
                }

                if (['no', 'n', 'none', 'without', '0'].includes(term)) {
                    return value === 'no';
                }

                return value.includes(term);
            };

            const matchesText = function (rawValue, query) {
                const value = normalize(rawValue);
                const term = normalize(query);
                return term ? value.includes(term) : true;
            };

            const applyFilter = function () {
                if (!filterColumn || !filterValue) {
                    return;
                }

                const column = filterColumn.value;
                const query = filterValue.value;

                filterRows.forEach(function (row) {
                    let rawValue = row.getAttribute(toDataAttr(column)) || row.dataset[column] || '';
                    if (!rawValue && Object.prototype.hasOwnProperty.call(columnIndexMap, column)) {
                        const cells = row.querySelectorAll('td');
                        const cellIndex = columnIndexMap[column];
                        if (cells[cellIndex]) {
                            rawValue = cells[cellIndex].textContent || '';
                        }
                    }
                    let isMatch = true;

                    if (numericColumns.has(column)) {
                        isMatch = matchesNumeric(rawValue, query);
                    } else if (column === 'image') {
                        isMatch = matchesImage(rawValue, query);
                    } else {
                        isMatch = matchesText(rawValue, query);
                    }

                    row.style.display = isMatch ? '' : 'none';
                });
            };

            const modal = document.getElementById('itemImageModal');
            const modalTitle = document.getElementById('itemImageModalLabel');
            const modalImage = document.getElementById('itemImageModalPreview');
            const modalEmpty = document.getElementById('itemImageModalEmpty');
            const itemRows = document.querySelectorAll('.item-row');
            const modalInstance = (modal && window.bootstrap) ? bootstrap.Modal.getOrCreateInstance(modal) : null;

            const openItemModal = function (itemName, imageSrc) {
                if (!modalInstance || !modal || !modalTitle || !modalImage || !modalEmpty) {
                    return;
                }
                modalTitle.textContent = itemName || 'Item Image';

                if (imageSrc) {
                    modalImage.src = imageSrc;
                    modalImage.alt = itemName || 'Item Image';
                    modalImage.classList.remove('d-none');
                    modalEmpty.classList.add('d-none');
                } else {
                    modalImage.removeAttribute('src');
                    modalImage.classList.add('d-none');
                    modalEmpty.classList.remove('d-none');
                }

                modalInstance.show();
            };

            itemRows.forEach(function (row) {
                row.addEventListener('click', function (event) {
                    if (event.target.closest('a, button, form, input, textarea, select, label')) {
                        return;
                    }

                    openItemModal(row.getAttribute('data-item-name'), row.getAttribute('data-item-image'));
                });

                row.addEventListener('keydown', function (event) {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        openItemModal(row.getAttribute('data-item-name'), row.getAttribute('data-item-image'));
                    }
                });
            });

            if (modal && modalInstance) {
                modal.addEventListener('hidden.bs.modal', function () {
                    modalImage.removeAttribute('src');
                    modalImage.classList.remove('d-none');
                    modalEmpty.classList.add('d-none');
                    modalTitle.textContent = 'Item Image';
                });
            }

            if (filterColumn && filterValue) {
                filterValue.placeholder = placeholders[filterColumn.value] || 'Type a value to filter';
                filterColumn.addEventListener('change', function () {
                    filterValue.placeholder = placeholders[filterColumn.value] || 'Type a value to filter';
                    applyFilter();
                });
                filterValue.addEventListener('input', applyFilter);
                filterValue.addEventListener('keyup', applyFilter);
                filterValue.addEventListener('change', applyFilter);
            }

            if (filterClear) {
                filterClear.addEventListener('click', function () {
                    if (filterValue) {
                        filterValue.value = '';
                    }
                    applyFilter();
                });
            }
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }
        })();
    </script>
    @endpush

@endsection