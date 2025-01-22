@extends('layouts.app')
@section('content')
<style>
    .po-order-detail tr td .min-width {
        min-width: 100px;
    }
    .min-width {
        min-width: 150px;
    }
</style>
<!-- BEGIN: Content -->
<form class="ajax-input-form" method="POST" action="{{ route('sales-accounts.store') }}" data-redirect="{{ url('/sales-accounts') }}" id="salesAccountForm">
    @csrf
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header pocreate-sticky">
                <div class="row">
                    <div class="content-header-left col-md-6 mb-2">
                        <div class="row breadcrumbs-top">
                            <div class="col-12">
                                <h2 class="content-header-title float-start mb-0">Sales Account Setup</h2>
                                <div class="breadcrumb-wrapper">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                        <li class="breadcrumb-item active">Setup</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="content-header-right text-sm-end col-md-6 mb-50 mb-sm-0">
                        <div class="form-group breadcrumb-right">
                            <button type="submit" class="btn btn-primary btn-sm"><i data-feather="check-circle"></i> Submit</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body customernewsection-form">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="newheader border-bottom mb-2 pb-25">
                                                <h4 class="card-title text-theme">Basic Information</h4>
                                                <p class="card-text">Fill the details</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive-md" style="overflow:auto;">
                                                <table class="table myrequesttablecbox table-striped po-order-detail custnewpo-detail border newdesignerptable newdesignpomrnpad" id="salesAccountsTable">
                                                    <thead>
                                                        <tr>
                                                            <th>S.No</th>
                                                            <th>Company</th>
                                                            <th>Organization</th>
                                                            <th>Customer Category</th>
                                                            <th>Customer Subcategory</th>
                                                            <th class="min-width">Customers</th>
                                                            <th>Item Category</th>
                                                            <th>Item Subcategory</th>
                                                            <th class="min-width">Items</th>
                                                            <th class="min-width">Books</th>
                                                            <th>Ledger</th>
                                                            <th>Ledger Group</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody >
                                                        @if($salesAccount->isEmpty())
                                                            <tr data-index="0" data-id="">
                                                                <td>1</td>
                                                                <td>
                                                                    <select class="form-select select2" name="sales_accounts[0][company_id]">
                                                                    <option value="">Select Company</option>
                                                                        @foreach($companies as $company)
                                                                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <select class="form-select select2" name="sales_accounts[0][organization_id]">
                                                                        <option value="">Select Organization</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control autocomplete min-width customer_category_name" name="sales_accounts[0][customer_category_name]" placeholder="Search Customer Category">
                                                                    <input type="hidden" name="sales_accounts[0][customer_category_id]" />
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control autocomplete min-width customer_sub_category_name" name="sales_accounts[0][customer_sub_category_name]" placeholder="Search Customer Sub Category">
                                                                    <input type="hidden" name="sales_accounts[0][customer_sub_category_id]" />
                                                                </td>
                                                                <td>
                                                                    <select class="form-select min-width select2" name="sales_accounts[0][customer_id][]" multiple>
                                                                        <option value="">Select Customer</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control min-width autocomplete item_category_name" name="sales_accounts[0][item_category_name]" placeholder="Search Item Category">
                                                                    <input type="hidden" name="sales_accounts[0][item_category_id]" />
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control min-width autocomplete item_sub_category_name" name="sales_accounts[0][item_sub_category_name]" placeholder="Search Item Sub Category">
                                                                    <input type="hidden" name="sales_accounts[0][item_sub_category_id]" />
                                                                </td>
                                                                <td>
                                                                    <select class="form-select select2" name="sales_accounts[0][item_id][]" multiple>
                                                                        <option value="">Select Item</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <select class="form-select select2" name="sales_accounts[0][book_id][]" multiple>
                                                                        <option value="">Select Book</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control min-width autocomplete ledger_name" name="sales_accounts[0][ledger_name]" placeholder="Search Ledger">
                                                                    <input type="hidden" name="sales_accounts[0][ledger_id]" />
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control min-width autocomplete ledger_group_name" name="sales_accounts[0][ledger_group_name]" placeholder="Search Ledger Group">
                                                                    <input type="hidden" name="sales_accounts[0][ledger_group_id]" />
                                                                </td>
                                                                <td>
                                                                    <a href="#" class="text-primary me-50 add-row"><i data-feather="plus-square"></i></a>
                                                                    <a href="#" class="text-danger delete-row"><i data-feather="trash-2"></i></a>
                                                                </td>
                                                            </tr>
                                                        @else
                                                            @foreach($salesAccount as $index => $item)
                                                                <tr data-index="{{ $index }}" data-id="{{ $item->id }}">
                                                                    <td>{{ $index + 1 }}</td>
                                                                    <input type="hidden" name="sales_accounts[{{ $index }}][id]" value="{{ $item->id }}">
                                                                    <td>
                                                                        <select class="form-select select2" name="sales_accounts[{{ $index }}][company_id]" data-row="{{ $index }}">
                                                                            @foreach($companies as $company)
                                                                                <option value="{{ $company->id }}" {{ $company->id == $item->company_id ? 'selected' : '' }}>{{ $company->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select class="form-select select2 organization-select" name="sales_accounts[{{ $index }}][organization_id]" data-row="{{ $index }}">
                                                                            <option value="">Select Organization</option>
                                                                            @if($item->company && $item->company->organizations)
                                                                                @foreach($item->company->organizations as $organization)
                                                                                    <option value="{{ $organization->id }}" {{ $organization->id == $item->organization_id ? 'selected' : '' }}>{{ $organization->name }}</option>
                                                                                @endforeach
                                                                            @else
                                                                                <option value="">No Organizations Available</option>
                                                                            @endif
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="form-control min-width autocomplete customer-category-name" name="sales_accounts[{{ $index }}][customer_category_name]" value="{{ $item->customerCategory->name ?? '' }}" placeholder="Search Customer Category">
                                                                        <input type="hidden" name="sales_accounts[{{ $index }}][customer_category_id]" value="{{ $item->customer_category_id ?? '' }}" />
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="form-control min-width autocomplete customer-subcategory-name" name="sales_accounts[{{ $index }}][customer_sub_category_name]" value="{{ $item->customerSubCategory->name ?? '' }}" placeholder="Search Customer Subcategory">
                                                                        <input type="hidden" name="sales_accounts[{{ $index }}][customer_sub_category_id]" value="{{ $item->customer_sub_category_id ?? '' }}" />
                                                                    </td>
                                                                    <td>
                                                                        <select class="form-select select2 customer-select" name="sales_accounts[{{ $index }}][customer_id][]" multiple>
                                                                            @php 
                                                                                $categoryId = $item->customer_category_id ?? null;
                                                                                $subCategoryId = $item->customer_sub_category_id ?? null;
                                                                                $organization = $item->organization ?? null;
                                                                                $customerIds = is_array($item->customer_id) ? $item->customer_id : json_decode($item->customer_id, true) ?? [];
                                                                            @endphp

                                                                            @if($subCategoryId && $item->customerCategory && $item->customerCategory->customersSub->count() > 0)
                                                                                @foreach($item->customerCategory->customersSub as $customerOption)
                                                                                    <option value="{{ $customerOption->id }}" {{ in_array($customerOption->id, $customerIds) ? 'selected' : '' }}>
                                                                                        {{ $customerOption->company_name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            @elseif($categoryId && $item->customerCategory && $item->customerCategory->customers->count() > 0)
                                                                                @foreach($item->customerCategory->customers as $customerOption)
                                                                                    <option value="{{ $customerOption->id }}" {{ in_array($customerOption->id, $customerIds) ? 'selected' : '' }}>
                                                                                        {{ $customerOption->company_name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            @elseif($organization && $organization->customers->count() > 0)
                                                                                @foreach($organization->customers as $customerOption)
                                                                                    <option value="{{ $customerOption->id }}" {{ in_array($customerOption->id, $customerIds) ? 'selected' : '' }}>
                                                                                        {{ $customerOption->company_name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            @else
                                                                                <option value="">No Customers Available</option>
                                                                            @endif
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="form-control min-width autocomplete item-category-name" name="sales_accounts[{{ $index }}][item_category_name]" value="{{ $item->itemCategory->name ?? '' }}" placeholder="Search Item Category">
                                                                        <input type="hidden" name="sales_accounts[{{ $index }}][item_category_id]" value="{{ $item->item_category_id ?? '' }}" />
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="form-control min-width autocomplete item-subcategory-name" name="sales_accounts[{{ $index }}][item_sub_category_name]" value="{{ $item->itemSubCategory->name ?? '' }}" placeholder="Search Item Subcategory">
                                                                        <input type="hidden" name="sales_accounts[{{ $index }}][item_sub_category_id]" value="{{ $item->item_sub_category_id ?? '' }}" />
                                                                    </td>
                                                                    <td>
                                                                        <select class="form-select select2 item-select" name="sales_accounts[{{ $index }}][item_id][]" multiple>
                                                                            @php
                                                                                $categoryId = $item->item_category_id ?? null;
                                                                                $subCategoryId = $item->item_sub_category_id ?? null;
                                                                                $organization = $item->organization ?? null;
                                                                                $itemIds = is_array($item->item_id) ? $item->item_id : json_decode($item->item_id, true) ?? [];
                                                                            @endphp

                                                                            @if($subCategoryId && $item->itemCategory->subCategories->where('id', $subCategoryId)->first())
                                                                                @foreach($item->itemCategory->subCategories->where('id', $subCategoryId)->first()->itemSub as $itemOption)
                                                                                    <option value="{{ $itemOption->id }}" {{ in_array($itemOption->id, $itemIds) ? 'selected' : '' }}>
                                                                                        {{ $itemOption->item_name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            @elseif($categoryId && $item->itemCategory->itemsCat->count() > 0)
                                                                                @foreach($item->itemCategory->items as $itemOption)
                                                                                    <option value="{{ $itemOption->id }}" {{ in_array($itemOption->id, $itemIds) ? 'selected' : '' }}>
                                                                                        {{ $itemOption->item_name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            @elseif($organization && $organization->items->count() > 0)
                                                                                @foreach($organization->items as $itemOption)
                                                                                    <option value="{{ $itemOption->id }}" {{ in_array($itemOption->id, $itemIds) ? 'selected' : '' }}>
                                                                                        {{ $itemOption->item_name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            @else
                                                                                <option value="">No Items Available</option>
                                                                            @endif
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select class="form-select select2 book-select" name="sales_accounts[{{ $index }}][book_id][]" multiple data-row="{{ $index }}">
                                                                            <option value="">Select Book</option>
                                                                            @php
                                                                                $bookIds = is_array($item->book_id) ? $item->book_id : json_decode($item->book_id, true) ?? [];
                                                                            @endphp
                                                                            @if($item->organization && $item->organization->books)
                                                                                @foreach($item->organization->books as $book)
                                                                                    <option value="{{ $book->id }}" {{ in_array($book->id, $bookIds) ? 'selected' : '' }}>
                                                                                        {{ $book->book_code }}
                                                                                    </option>
                                                                                @endforeach
                                                                            @else
                                                                                <option value="">No Books Available</option>
                                                                            @endif
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="form-control min-width autocomplete ledger_name"  name="sales_accounts[{{ $index }}][ledger_name]"  value="{{ $item->ledger->name ?? '' }}" placeholder="Search Ledger">
                                                                        <input type="hidden" name="sales_accounts[{{ $index }}][ledger_id]" value="{{ $item->ledger_id ?? '' }}" />
                                                                    </td>

                                                                    <td>
                                                                        <input type="text" class="form-control min-width autocomplete ledger_group_name"  name="sales_accounts[{{ $index }}][ledger_group_name]"   value="{{ $item->ledgerGroup->name ?? '' }}" placeholder="Search Ledger Group"/> 
                                                                        <input type="hidden" name="sales_accounts[{{ $index }}][ledger_group_id]" value="{{ $item->ledger_group_id ?? '' }}" />
                                                                    </td>
                                                                    <td>
                                                                        <a href="#" class="text-primary me-50 add-row"><i data-feather="plus-square"></i></a>
                                                                        <a href="#" class="text-danger delete-row"><i data-feather="trash-2"></i></a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</form>

@endsection
@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        $('.select2').select2();
        feather.replace();
        document.querySelector('tbody').addEventListener('click', function (e) {
        if (e.target.closest('.add-row')) {
            e.preventDefault();
            const clickedRow = e.target.closest('tr');
            addRow(clickedRow); 
        }

        if (e.target.closest('.delete-row')) {
            e.preventDefault();
            const row = e.target.closest('tr');
            const salesAccountId = row.dataset.id;
            
            if (salesAccountId) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Are you sure you want to delete this Record?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, keep it'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/sales-accounts/${salesAccountId}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                        })
                        .then(response => response.json())
                        .then(response => {
                            if (response.status) {
                                row.remove();
                                Swal.fire('Deleted!', response.message, 'success');
                                updateRowIndexes();
                            } else {
                                Swal.fire('Error!', response.message || 'Could not delete sales account.', 'error');
                            }
                        })
                        .catch(() => {
                            Swal.fire('Error!', 'An error occurred while deleting the sales account.', 'error');
                        });
                    }
                });
            } else {
                row.remove();
                updateRowIndexes();
            }
        }
    });

    function addRow(clickedRow) {
        if (!clickedRow) {
            console.error("clickedRow is null, unable to add a row.");
            return;
        }

        const tableBody = document.querySelector('#salesAccountsTable tbody');
        const rowCount = tableBody.querySelectorAll('tr').length;
        const newRow = document.createElement('tr');
        newRow.dataset.id = ''; 
        newRow.innerHTML = `
            <td>${rowCount + 1}</td>
            <td>
                <select class="form-select select2" name="sales_accounts[${rowCount}][company_id]">
                    <option value="">Select Company</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                    @endforeach
                </select>
            </td>
            <td class="organization-field">
                <select class="form-select select2" name="sales_accounts[${rowCount}][organization_id]">
                    <option value="">Select Organization</option>
                </select>
            </td>
            <td class="customer-category-field">
                <input type="text" class="form-control min-width autocomplete customer-category-name" name="sales_accounts[${rowCount}][customer_category_name]" placeholder="Search Customer Category">
                <input type="hidden" name="sales_accounts[${rowCount}][customer_category_id]" />
            </td>
            <td class="customer-sub-category-field">
                <input type="text" class="form-control min-width autocomplete customer-subcategory-name" name="sales_accounts[${rowCount}][customer_sub_category_name]" placeholder="Search Customer Subcategory">
                <input type="hidden" name="sales_accounts[${rowCount}][customer_sub_category_id]" />
            </td>
            <td class="customer-field">
                <select class="form-select select2" name="sales_accounts[${rowCount}][customer_id][]" multiple>
                    <option value="">Select Customer</option>
                </select>
            </td>
            <td class="item-category-field">
                <input type="text" class="form-control min-width autocomplete item-category-name" name="sales_accounts[${rowCount}][item_category_name]" placeholder="Search Item Category">
                <input type="hidden" name="sales_accounts[${rowCount}][item_category_id]" />
            </td>
            <td class="item-sub-category-field">
                <input type="text" class="form-control min-width autocomplete item-subcategory-name" name="sales_accounts[${rowCount}][item_sub_category_name]" placeholder="Search Item Subcategory">
                <input type="hidden" name="sales_accounts[${rowCount}][item_sub_category_id]" />
            </td>
            <td class="item-field">
                <select class="form-select select2" name="sales_accounts[${rowCount}][item_id][]" multiple>
                    <option value="">Select Item</option>
                </select>
            </td>
            <td class="book-field">
                <select class="form-select select2" name="sales_accounts[${rowCount}][book_id][]" multiple>
                    <option value="">Select Book</option>
                </select>
            </td>
            <td class="ledger-field">
                <input type="text" class="form-control min-width autocomplete ledger_name" name="sales_accounts[${rowCount}][ledger_name]" placeholder="Search Ledger">
                <input type="hidden" name="sales_accounts[${rowCount}][ledger_id]" />
            </td>
            <td class="ledger-field">
                <input type="text" class="form-control min-width autocomplete ledger_group_name" name="sales_accounts[${rowCount}][ledger_group_name]" placeholder="Search Ledger Group">
                <input type="hidden" name="sales_accounts[${rowCount}][ledger_group_id]" />
            </td>
            <td>
                <a href="#" class="text-primary me-50 add-row"><i data-feather="plus-square"></i></a>
                <a href="#" class="text-danger delete-row"><i data-feather="trash-2"></i></a>
            </td>
        `;
        clickedRow.insertAdjacentElement('afterend', newRow);
        $(newRow).find('.select2').select2();
        feather.replace();
        updateRowIndexes();
    }

    function updateRowIndexes() {
        const tableBody = document.querySelector('#salesAccountsTable tbody');
        const rows = tableBody.querySelectorAll('tr');
        rows.forEach((row, index) => {
            const rowIndexCell = row.querySelector('td');
            if (rowIndexCell) {
                rowIndexCell.textContent = index + 1; 
            }
            row.querySelectorAll('input[name], select[name]').forEach(field => {
                const name = field.getAttribute('name');
                const updatedName = name.replace(/\[\d+\]/, `[${index}]`);
                field.setAttribute('name', updatedName);
            });
        });
    }
    $(document).on('change', '[name^="sales_accounts"][name$="[company_id]"]', function () {
        var companyId = $(this).val();
        var $row = $(this).closest('tr');
        var organizationSelect = $row.find('[name$="[organization_id]"]');
        organizationSelect.empty().append('<option value="">Select Organization</option>');
        if (companyId) {
            $.get(`/sales-accounts/organizations/${companyId}`, function (data) {
                if (data && data.organizations) {
                    data.organizations.forEach(function (org) {
                        organizationSelect.append(`<option value="${org.id}">${org.name}</option>`);
                    });
                    organizationSelect.select2();
                } else {
                    Swal.fire('Error!', 'No organizations found for the selected company.', 'error');
                }
            }).fail(function () {
                Swal.fire('Error!', 'An error occurred while loading organizations.', 'error');
            });
        }
    });
    $(document).on('change', '[name$="[organization_id]"]', function () {
        var organizationId = $(this).val();
        var $row = $(this).closest('tr');
        var customerCategoryInput = $row.find('[name$="[customer_category_name]"]');
        var customerCategoryIdInput = $row.find('[name$="[customer_category_id]"]');
        var itemCategoryInput = $row.find('[name$="[item_category_name]"]');
        var itemCategoryIdInput = $row.find('[name$="[item_category_id]"]');
        var ledgerInput = $row.find('[name$="[ledger_name]"]');
        var ledgerIdInput = $row.find('[name$="[ledger_id]"]');
        var itemSelect = $row.find('[name$="[item_id][]"]');
        var customerSelect = $row.find('[name$="[customer_id][]"]');
        var bookSelect = $row.find('[name$="[book_id][]"]');
        customerCategoryInput.val('');
        customerCategoryIdInput.val('');
        itemCategoryInput.val('');
        itemCategoryIdInput.val('');
        ledgerInput.val('');
        ledgerIdInput.val('');
        itemSelect.empty().append('<option value="">Select Item</option>').trigger('change');
        customerSelect.empty().append('<option value="">Select Customer</option>').trigger('change');
        bookSelect.empty().append('<option value="">Select Book</option>').trigger('change');
        $.get(`/sales-accounts/data-by-organization/${organizationId}`, function(data) {
            if (data) {
                customerCategoryInput.autocomplete({
                    source: function (request, response) {
                        if (!data.customerCategories || data.customerCategories.length === 0) {
                            response([{ label: "No records found", value: "" }]);
                        } else {
                            response($.map(data.customerCategories, function (category) {
                                return { label: category.name, value: category.id };
                            }));
                        }
                    },
                    minLength: 2,
                    select: function (event, ui) {
                        if (ui.item.value === "") return false;
                        customerCategoryInput.val(ui.item.label);
                        customerCategoryIdInput.val(ui.item.value);
                        customerCategoryIdInput.trigger('change');
                        return false;
                    }
                });
                itemCategoryInput.autocomplete({
                    source: function (request, response) {
                        if (!data.itemCategories || data.itemCategories.length === 0) {
                            response([{ label: "No records found", value: "" }]);
                        } else {
                            response($.map(data.itemCategories, function (category) {
                                return { label: category.name, value: category.id };
                            }));
                        }
                    },
                    minLength: 2,
                    select: function (event, ui) {
                        if (ui.item.value === "") return false;
                        itemCategoryInput.val(ui.item.label);
                        itemCategoryIdInput.val(ui.item.value);
                        itemCategoryIdInput.trigger('change');
                    }
                });
                ledgerInput.autocomplete({
                    source: function (request, response) {
                        if (!data.ledgers || data.ledgers.length === 0) {
                            response([{ label: "No records found", value: "" }]);
                        } else {
                            response($.map(data.ledgers, function (ledger) {
                                return { label: ledger.name, value: ledger.id };
                            }));
                        }
                    },
                    minLength: 2,
                    select: function (event, ui) {
                        if (ui.item.value === "") return false;
                        ledgerInput.val(ui.item.label);
                        ledgerIdInput.val(ui.item.value);
                        ledgerIdInput.trigger('change');
                        return false;
                    }
                });
                bookSelect.empty().append('<option value="">Select Book</option>');
                if (data.erpBooks) {
                    data.erpBooks.forEach(function(book) {
                        bookSelect.append(`<option value="${book.id}">${book.book_code}</option>`);
                    });
                }
                itemSelect.empty().append('<option value="">Select Item</option>');
                if (data.items) {
                    data.items.forEach(function(item) {
                        itemSelect.append(`<option value="${item.id}">${item.item_code}</option>`);
                    });
                }
                customerSelect.empty().append('<option value="">Select Customer</option>');
                if (data.customers) {
                    data.customers.forEach(function(customer) {
                        customerSelect.append(`<option value="${customer.id}">${customer.company_name || 'Unnamed Customer'}</option>`);
                    });
                }
                itemSelect.select2();
                customerSelect.select2();
                bookSelect.select2();
            }
        }).fail(function () {
            Swal.fire('Error!', 'An error occurred while loading data for the selected organization.', 'error');
        });
    });

    $(document).on('focus input', '[name$="[customer_category_name]"]', function () {
        var $input = $(this);
        var $row = $input.closest('tr');
        var customerCategoryIdInput = $row.find('[name$="[customer_category_id]"]');
        var organizationId = $row.find('[name$="[organization_id]"]').val();
        var searchTerm = $input.val();
        $input.next('.no-records').remove();
        if (!organizationId) return;
        $.get(`/sales-accounts/categories-by-organization/${organizationId}`, { search: searchTerm }, function (data) {
            var results = data.customer_categories && data.customer_categories.length > 0 ? 
                $.map(data.customer_categories, function (category) {
                    return { label: category.name, value: category.id };
                }) : 
                [{ label: "No records found", value: "" }];

            $input.autocomplete({
                source: function(request, response) {
                    console.log('Autocomplete source called:', results); 
                    response(results); 
                },
                minLength: 0,  
                select: function(event, ui) {
                    if (ui.item.value === "") return false; 
                    $input.val(ui.item.label);  
                    customerCategoryIdInput.val(ui.item.value);  
                    customerCategoryIdInput.trigger('change'); 
                    return false;  
                }
            }).autocomplete("search", searchTerm); 
        }).fail(function () {
            $input.autocomplete({
                source: function(request, response) {
                    response([{ label: "No records found", value: "" }]);
                },
                minLength: 0,  
                select: function(event, ui) {
                    if (ui.item.value === "") return false;  
                    $input.val(ui.item.label); 
                    customerCategoryIdInput.val(ui.item.value); 
                    customerCategoryIdInput.trigger('change');  
                    return false;  
                }
            });
            Swal.fire('Error!', 'An error occurred while fetching customer categories for the organization.', 'error');
        });
    });


  $(document).on('change', '[name$="[customer_category_id]"]', function () {
        var customerCategoryId = $(this).val();
        var $row = $(this).closest('tr');
        var subCategoryInput = $row.find('[name$="[customer_sub_category_name]"]');
        var subCategoryIdInput = $row.find('[name$="[customer_sub_category_id]"]');
        var customerSelect = $row.find('[name$="[customer_id][]"]');
        subCategoryInput.val('');
        subCategoryIdInput.val('');
        customerSelect.empty().append('<option value="">Select Customer</option>');

        if (customerCategoryId) {
            $.get(`/sales-accounts/customer-subcategories-by-category`, { category_id: customerCategoryId }, function (data) {
                if (data.subCategories && data.subCategories.length > 0) {
                    subCategoryInput.autocomplete({
                        source: function (request, response) {
                            var results = $.map(data.subCategories, function (subCategory) {
                                return { label: subCategory.name, value: subCategory.id };
                            });
                            response(results);
                        },
                        minLength: 2,
                        select: function (event, ui) {
                            if (ui.item.value === "") return false;
                            subCategoryInput.val(ui.item.label);
                            subCategoryIdInput.val(ui.item.value);
                            subCategoryInput.trigger('change');
                            return false;
                        }
                    });
                } else {
                    subCategoryInput.autocomplete("disable");
                }
                customerSelect.empty().append('<option value="">Select Customer</option>');
                if (data.customers) {
                    data.customers.forEach(function (customer) {
                        customerSelect.append(`<option value="${customer.id}">${customer.company_name}</option>`);
                    });
                }
                customerSelect.select2();
            }).fail(function () {
                Swal.fire('Error!', 'An error occurred while loading customer subcategories and customers.', 'error');
            });
        } else {
            subCategoryInput.val('');
            subCategoryIdInput.val('');
            customerSelect.empty().append('<option value="">Select Customer</option>');
            customerSelect.select2();
        }
    });


    $(document).on('focus input', '[name$="[customer_sub_category_name]"]', function () {
        var $input = $(this);
        var $row = $input.closest('tr');
        var customerCategoryIdInput = $row.find('[name$="[customer_category_id]"]');
        var customerSubCategoryIdInput = $row.find('[name$="[customer_sub_category_id]"]');
        var customerCategoryId = customerCategoryIdInput.val(); 
        var searchTerm = $input.val();
        $input.next('.no-records').remove();
        if (!customerCategoryId) return;

        $.get(`/sales-accounts/subcategories-by-category/${customerCategoryId}`, { category_id: customerCategoryId }, function (data) {
            if (data.subCategories && data.subCategories.length > 0) {
                $input.autocomplete({
                    source: function (request, response) {
                        var results = $.map(data.subCategories, function (subCategory) {
                            return {
                                label: subCategory.name, 
                                value: subCategory.id   
                            };
                        });
                        response(results);  
                    },
                    minLength: 0, 
                    select: function (event, ui) {
                        if (ui.item.value === "") return false; 
                        $input.val(ui.item.label); 
                        customerSubCategoryIdInput.val(ui.item.value); 
                        customerSubCategoryIdInput.trigger('change');  
                        return false; 
                    }
                }).autocomplete("search", searchTerm);  
            } else {
                $input.autocomplete("disable");
            }
        }).fail(function () {
            $input.autocomplete({
                source: function (request, response) {
                    response([{ label: "No records found", value: "" }]); 
                },
                minLength: 0, 
                select: function (event, ui) {
                    if (ui.item.value === "") return false; 
                    $input.val(ui.item.label);
                    customerSubCategoryIdInput.val(ui.item.value); 
                    customerSubCategoryIdInput.trigger('change'); 
                    return false; 
                }
            });
            Swal.fire('Error!', 'An error occurred while fetching customer subcategories.', 'error');
        });
     });


    $(document).on('change', '[name$="[customer_sub_category_id]"]', function () {
        var customerSubCategoryId = $(this).val();
        var $row = $(this).closest('tr');
        var customerSelect = $row.find('[name$="[customer_id][]"]');
        customerSelect.empty().append('<option value="">Select Customer</option>');

        if (customerSubCategoryId) {
            $.get(`/sales-accounts/customer-by-subcategory`, { sub_category_id: customerSubCategoryId }, function (data) {
                if (data.customers) {
                    data.customers.forEach(function (customer) {
                        customerSelect.append(`<option value="${customer.id}">${customer.company_name}</option>`);
                    });
                }
                customerSelect.select2();
            }).fail(function () {
                Swal.fire('Error!', 'An error occurred while loading customer data for the selected subcategory.', 'error');
            });
        }
    });

    $(document).on('focus input', '[name$="[item_category_name]"]', function () {
        var $input = $(this);
        var $row = $input.closest('tr');
        var itemCategoryIdInput = $row.find('[name$="[item_category_id]"]');
        var organizationId = $row.find('[name$="[organization_id]"]').val();
        var searchTerm = $input.val(); 
        if (!organizationId) return;
        $.get(`/sales-accounts/categories-by-organization/${organizationId}`, function (data) {
            if (data.item_categories && data.item_categories.length > 0) {
                $input.autocomplete({
                    source: function (request, response) {
                        var results = $.map(data.item_categories, function (category) {
                            return {
                                label: category.name,  
                                value: category.id   
                            };
                        });
                        response(results); 
                    },
                    minLength: 0, 
                    select: function (event, ui) {
                        if (ui.item.value === "") return false; 
                        $input.val(ui.item.label);  
                        itemCategoryIdInput.val(ui.item.value);  
                        itemCategoryIdInput.trigger('change');  
                        return false; 
                    }
                }).autocomplete("search", searchTerm);  
            } else {
                $input.autocomplete("disable");
            }
        }).fail(function () {
            $input.autocomplete({
                source: function (request, response) {
                    response([{ label: "No records found", value: "" }]);  
                },
                minLength: 0, 
                select: function (event, ui) {
                    if (ui.item.value === "") return false;  
                    $input.val(ui.item.label); 
                    itemCategoryIdInput.val(ui.item.value);  
                    itemCategoryIdInput.trigger('change'); 
                    return false; 
                }
            });
            Swal.fire('Error!', 'An error occurred while fetching item categories.', 'error');
        });
     });


    $(document).on('change', '[name$="[item_category_id]"]', function () {
        var itemCategoryId = $(this).val();
        var $row = $(this).closest('tr');
        var subCategoryInput = $row.find('[name$="[item_sub_category_name]"]');
        var subCategoryIdInput = $row.find('[name$="[item_sub_category_id]"]');
        var itemSelect = $row.find('[name$="[item_id][]"]');
        subCategoryInput.val('');
        subCategoryIdInput.val('');
        itemSelect.empty().append('<option value="">Select Item</option>');
        if (itemCategoryId) {
            $.get(`/sales-accounts/item-subcategories-by-category`, { category_id: itemCategoryId }, function (data) {
                if (data.subCategories && data.subCategories.length > 0) {
                    subCategoryInput.autocomplete({
                        source: function (request, response) {
                            var results = $.map(data.subCategories, function (subCategory) {
                                return { label: subCategory.name, value: subCategory.id };
                            });
                            response(results);
                        },
                        minLength: 2,
                        select: function (event, ui) {
                            if (ui.item.value === "") return false;
                            subCategoryInput.val(ui.item.label);
                            subCategoryIdInput.val(ui.item.value);
                            subCategoryInput.trigger('change');
                            return false;
                        }
                    });
                } else {
                    subCategoryInput.autocomplete("disable");
                }
                itemSelect.empty().append('<option value="">Select Item</option>');
                if (data.items) {
                    data.items.forEach(function (item) {
                        itemSelect.append(`<option value="${item.id}">${item.item_code}</option>`);
                    });
                }
                itemSelect.select2();
            }).fail(function () {
                Swal.fire('Error!', 'An error occurred while loading item subcategories and items.', 'error');
            });
        } else {
            subCategoryInput.val('');
            subCategoryIdInput.val('');
            itemSelect.empty().append('<option value="">Select Item</option>');
            itemSelect.select2();
        }
    });

    $(document).on('focus input', '[name$="[item_sub_category_name]"]', function () {
        var $input = $(this);
        var $row = $input.closest('tr');
        var itemCategoryIdInput = $row.find('[name$="[item_category_id]"]');
        var itemSubCategoryIdInput = $row.find('[name$="[item_sub_category_id]"]');
        var itemCategoryId = itemCategoryIdInput.val(); 
        var searchTerm = $input.val();

        if (!itemCategoryId) return;

        $.get(`/sales-accounts/subcategories-by-category/${itemCategoryId}`, { category_id: itemCategoryId }, function (data) {
            if (data.subCategories && data.subCategories.length > 0) {
                $input.autocomplete({
                    source: function (request, response) {
                        var results = $.map(data.subCategories, function (subCategory) {
                            return {
                                label: subCategory.name, 
                                value: subCategory.id   
                            };
                        });
                        response(results);  
                    },
                    minLength: 0,
                    select: function (event, ui) {
                        if (ui.item.value === "") return false; 
                        $input.val(ui.item.label); 
                        itemSubCategoryIdInput.val(ui.item.value); 
                        itemSubCategoryIdInput.trigger('change');  
                        return false; 
                    }
                }).autocomplete("search", searchTerm);  
            } else {
            
                $input.autocomplete("disable");
            }
        }).fail(function () {
            $input.autocomplete({
                source: function (request, response) {
                    response([{ label: "No records found", value: "" }]);  
                },
                minLength: 0, 
                select: function (event, ui) {
                    if (ui.item.value === "") return false; 
                    $input.val(ui.item.label); 
                    itemSubCategoryIdInput.val(ui.item.value);
                    itemSubCategoryIdInput.trigger('change'); 
                    return false; 
                }
            });

            Swal.fire('Error!', 'An error occurred while fetching item subcategories.', 'error');
        });
    });

    $(document).on('change', '[name$="[item_sub_category_id]"]', function () {
        var itemSubCategoryId = $(this).val();
        var $row = $(this).closest('tr');
        var itemSelect = $row.find('[name$="[item_id][]"]');
        itemSelect.empty().append('<option value="">Select Item</option>');

        if (itemSubCategoryId) {
            $.get(`/sales-accounts/items-by-subcategory`, { sub_category_id: itemSubCategoryId }, function (data) {
                if (data.items) {
                    data.items.forEach(function (item) {
                        itemSelect.append(`<option value="${item.id}">${item.item_code}</option>`);
                    });
                }
                itemSelect.select2();
            }).fail(function () {
                Swal.fire('Error!', 'An error occurred while loading items for the selected subcategory.', 'error');
            });
        }
    });

    $(document).on('focus input', '[name$="[ledger_name]"]', function () {
        var $input = $(this);
        var $row = $input.closest('tr');
        var ledgerIdInput = $row.find('[name$="[ledger_id]"]');
        var organizationId = $row.find('[name$="[organization_id]"]').val();
        var searchTerm = $input.val();  

        $input.next('.no-records').remove(); 
        if (!organizationId) {
            return;
        }
        $.get(`/stock-accounts/ledgers-by-organization/${organizationId}`, { search: searchTerm }, function (data) {
            var results = data.ledgers && data.ledgers.length > 0 ? 
                $.map(data.ledgers, function (ledger) {
                    return {
                        label: ledger.name,
                        value: ledger.id
                    };
                }) : 
                [{ label: "No records found", value: "" }];
            
            $input.autocomplete({
                source: function(request, response) {
                    response(results);  
                },
                minLength: 0, 
                select: function(event, ui) {
                    if (ui.item.value === "") return false;  
                    $input.val(ui.item.label); 
                    ledgerIdInput.val(ui.item.value); 
                    ledgerIdInput.trigger('change');
                    return false; 
                }
            }).autocomplete("search", searchTerm);

        }).fail(function () {
            $input.autocomplete({
                source: function(request, response) {
                    response([{ label: "No records found", value: "" }]);
                },
                minLength: 0, 
                select: function(event, ui) {
                    if (ui.item.value === "") return false;  
                    $input.val(ui.item.label); 
                    ledgerIdInput.val(ui.item.value); 
                    ledgerIdInput.trigger('change');
                    return false;
                }
            });
            Swal.fire('Error!', 'An error occurred while fetching ledgers for the organization.', 'error');
        });
    });

    $(document).on('change input', '[name$="[ledger_id]"]', function () {
        var selectedLedgerId = $(this).val(); 
        var $row = $(this).closest('tr');
        var ledgerGroupNameInput = $row.find('[name$="[ledger_group_name]"]');
        var ledgerGroupIdInput = $row.find('[name$="[ledger_group_id]"]');
        var searchTerm = ledgerGroupNameInput.val();
        ledgerGroupNameInput.val('');
        ledgerGroupIdInput.val('');
        if (selectedLedgerId) {
            $.get('/stock-accounts/ledgers-by-group', {
                ledger_id: selectedLedgerId,
                search_term: searchTerm  
            }, function (data) {
                if (data && data.ledgerGroupData) {
                    ledgerGroupNameInput.autocomplete({
                        source: function (request, response) {
                            var results = $.map(data.ledgerGroupData, function (ledgerGroup) {
                                if (ledgerGroup && ledgerGroup.name && ledgerGroup.id) {
                                    return {
                                        label: ledgerGroup.name, 
                                        value: ledgerGroup.id  
                                    };
                                }
                            });
                            response(results); 
                        },
                        minLength: 2, 
                        select: function (event, ui) {
                            ledgerGroupNameInput.val(ui.item.label);
                            ledgerGroupIdInput.val(ui.item.value); 
                            ledgerGroupNameInput.trigger('change'); 
                            return false;
                        }
                    });
                } else {
                    Swal.fire('No Ledger Group Found', 'No ledger group found for the selected ledger.', 'warning');
                }
            }).fail(function () {
                Swal.fire('Error!', 'An error occurred while fetching the ledger group.', 'error');
            });
        } else {
            ledgerGroupNameInput.val('');
            ledgerGroupIdInput.val('');
        }
    });

    $(document).on('focus input', '[name$="[ledger_group_name]"]', function () {
        var $input = $(this); 
        var $row = $input.closest('tr');
        var selectedLedgerId = $row.find('[name$="[ledger_id]"]').val(); 
        var ledgerGroupNameInput = $row.find('[name$="[ledger_group_name]"]');  
        var ledgerGroupIdInput = $row.find('[name$="[ledger_group_id]"]'); 
        var searchTerm = $input.val(); 
        $input.next('.no-records').remove();

        if (selectedLedgerId) {
            $.get('/stock-accounts/ledgers-by-group', {
                ledger_id: selectedLedgerId, 
                search_term: searchTerm
            }, function (data) {
                var results = (data && data.ledgerGroupData && data.ledgerGroupData.length > 0) ? 
                    $.map(data.ledgerGroupData, function (ledgerGroup) {
                        return {
                            label: ledgerGroup.name, 
                            value: ledgerGroup.id 
                        };
                    }) : 
                    [{ label: "No records found", value: "" }];

                ledgerGroupNameInput.autocomplete({
                    source: function (request, response) {
                        response(results); 
                    },
                    minLength: 0, 
                    select: function (event, ui) {
                        if (ui.item.value === "") return false;  
                        ledgerGroupNameInput.val(ui.item.label); 
                        ledgerGroupIdInput.val(ui.item.value);  
                        return false; 
                    }
                }).autocomplete("search", searchTerm); 
            }).fail(function () {
                ledgerGroupNameInput.autocomplete({
                    source: function (request, response) {
                        response([{ label: "No records found", value: "" }]);
                    },
                    minLength: 0, 
                    select: function (event, ui) {
                        if (ui.item.value === "") return false; 
                        ledgerGroupNameInput.val(ui.item.label); 
                        ledgerGroupIdInput.val(ui.item.value); 
                        return false; 
                    }
                });

                Swal.fire('Error!', 'An error occurred while fetching the ledger group.', 'error');
            });
        }
    });

});
</script>
@endsection
