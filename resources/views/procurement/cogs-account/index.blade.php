@extends('layouts.app')

@section('content')
<!-- BEGIN: Content -->
<style>
    .po-order-detail tr td .min-width {
        min-width: 100px;
    }
    .min-width {
        min-width: 150px;
    }
</style>
<form class="ajax-input-form" method="POST" action="{{ route('cogs-accounts.store') }}" data-redirect="{{ url('/cogs-accounts') }}" id="cogsAccountForm">
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
                                <h2 class="content-header-title float-start mb-0">Cogs Account Setup</h2>
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
                                                <table class="table myrequesttablecbox table-striped po-order-detail custnewpo-detail border newdesignerptable newdesignpomrnpad">
                                                    <thead>
                                                        <tr>
                                                            <th>S.No</th>
                                                            <th>Company</th>
                                                            <th>Organization</th>
                                                            <th>Item Category</th>
                                                            <th>Sub Category</th>
                                                            <th class="min-width">Items</th>
                                                            <th class="min-width">Books</th>
                                                            <th>Ledger</th>
                                                            <th>Ledger Group</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="cogsAccountsTable">
                                                        @if($cogsAccounts->isEmpty())
                                                            <tr data-index="0" data-id="">
                                                                <td>1</td>
                                                                <td>
                                                                    <select class="form-select select2" name="cogs_accounts[0][company_id]">
                                                                        <option value="">Select Company</option>
                                                                        @foreach($companies as $company)
                                                                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <select class="form-select select2" name="cogs_accounts[0][organization_id]">
                                                                        <option value="">Select Organization</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control min-width autocomplete category_name" name="stock_accounts[0][category_name]" placeholder="Search Category">
                                                                    <input type="hidden" name="stock_accounts[0][category_id]" />
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control min-width autocomplete sub_category_name" name="cogs_accounts[0][sub_category_name]" placeholder="Search Sub Category">
                                                                    <input type="hidden" name="cogs_accounts[0][sub_category_id]" />
                                                                </td>
                                                                <td>
                                                                    <select class="form-select select2" name="cogs_accounts[0][item_id][]" multiple>
                                                                        <option value="">Select Item</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <select class="form-select select2" name="cogs_accounts[0][book_id][]" multiple>
                                                                        <option value="">Select Book</option>
                                                                    </select>
                                                                </td>
                                                                 <td>
                                                                    <input type="text" class="form-control min-width autocomplete ledger_name"  name="cogs_accounts[0][ledger_name]"  value="{{ $item->ledger->name ?? '' }}" placeholder="Search Ledger">
                                                                    <input type="hidden" name="cogs_accounts[0][ledger_id]" value="{{ $item->ledger_id ?? '' }}" />
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control min-width autocomplete ledger_group_name" name="cogs_accounts[0][ledger_group_name]" placeholder="Search Ledger Group">
                                                                    <input type="hidden" name="cogs_accounts[0][ledger_group_id]" />
                                                                </td>
                                                                <td>
                                                                    <a href="#" class="text-primary me-50 add-row"><i data-feather="plus-square"></i></a>
                                                                    <a href="#" class="text-danger delete-row"><i data-feather="trash-2"></i></a>
                                                                </td>
                                                            </tr>
                                                        @else
                                                        @foreach($cogsAccounts as $index => $item)
                                                            <tr data-index="{{ $index }}" data-id="{{ $item->id }}">
                                                                <td>{{ $index + 1 }}</td>
                                                                <input type="hidden" name="cogs_accounts[{{ $index }}][id]" value="{{ $item->id }}">
                                                                <td>
                                                                    <select class="form-select select2 company-select" name="cogs_accounts[{{ $index }}][company_id]" data-row="{{ $index }}">
                                                                        <option value="">Select Company</option>
                                                                        @foreach($companies as $company)
                                                                            <option value="{{ $company->id }}" {{ $company->id == $item->company_id ? 'selected' : '' }}>
                                                                                {{ $company->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <select class="form-select select2 organization-select" name="cogs_accounts[{{ $index }}][organization_id]" data-row="{{ $index }}">
                                                                        <option value="">Select Organization</option>
                                                                        @if($item->company && $item->company->organizations && $item->company->organizations->count() > 0)
                                                                            @foreach($item->company->organizations as $organization)
                                                                                <option value="{{ $organization->id }}" {{ $organization->id == $item->organization_id ? 'selected' : '' }}>
                                                                                    {{ $organization->alias }}
                                                                                </option>
                                                                            @endforeach
                                                                        @else
                                                                            <option value="">No Organizations Available</option>
                                                                        @endif
                                                                    </select>
                                                                </td>

                                                                <td>
                                                                    <input type="text" class="form-control min-width autocomplete category_name" name="gr_acogs_accountsccounts[{{ $index }}][category_name]" value="{{ $item->category->name ?? '' }}" placeholder="Search Category">
                                                                    <input type="hidden" name="cogs_accounts[{{ $index }}][category_id]" value="{{ $item->category_id ?? '' }}" />
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control min-width autocomplete sub_category_name" name="cogs_accounts[{{ $index }}][sub_category_name]" value="{{ $item->subCategory->name ?? '' }}" placeholder="Search Subcategory">
                                                                    <input type="hidden" name="cogs_accounts[{{ $index }}][sub_category_id]" value="{{ $item->sub_category_id ?? '' }}" />
                                                                </td>
                                                                <td>
                                                                    <select class="form-select select2 item-select" name="cogs_accounts[{{ $index }}][item_id][]" multiple>
                                                                        @php
                                                                            $categoryId = $item->category_id ?? null;
                                                                            $subCategoryId = $item->sub_category_id ?? null;
                                                                            $organization = $item->organization ?? null;
                                                                            $itemIds = is_array($item->item_id) ? $item->item_id : json_decode($item->item_id, true) ?? [];
                                                                        @endphp

                                                                        @if($subCategoryId && $item->category && $item->category->subCategories->where('id', $subCategoryId)->first())
                                                                            @foreach($item->category->subCategories->where('id', $subCategoryId)->first()->itemSub as $itemOption)
                                                                                <option value="{{ $itemOption->id }}" {{ in_array($itemOption->id, $itemIds) ? 'selected' : '' }}>
                                                                                    {{ $itemOption->item_name }}
                                                                                </option>
                                                                            @endforeach
                                                                        @elseif($categoryId && $item->category && $item->category->items->count() > 0)
                                                                            @foreach($item->category->items as $itemOption)
                                                                                <option value="{{ $itemOption->id }}" {{ in_array($itemOption->id, $itemIds) ? 'selected' : '' }}>
                                                                                    {{ $itemOption->item_name }}
                                                                                </option>
                                                                            @endforeach
                                                                        @elseif($organization && $organization->items && $organization->items->count() > 0)
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

                                                                <!-- Book Select -->
                                                                <td>
                                                                    <select class="form-select select2 book-select" name="cogs_accounts[{{ $index }}][book_id][]" multiple data-row="{{ $index }}">
                                                                        <option value="">Select Book</option>
                                                                        @if($item->organization && $item->organization->books && $item->organization->books->count() > 0)
                                                                            @foreach($item->organization->books as $book)
                                                                                <option value="{{ $book->id }}" {{ in_array($book->id, is_array($item->book_id) ? $item->book_id : json_decode($item->book_id, true) ?? []) ? 'selected' : '' }}>
                                                                                    {{ $book->book_code }}
                                                                                </option>
                                                                            @endforeach
                                                                        @else
                                                                            <option value="">No Books Available</option>
                                                                        @endif
                                                                    </select>
                                                                </td>

                                                                 <!-- Ledger Select -->
                                                                 <td>
                                                                    <input type="text" class="form-control min-width autocomplete ledger_name"  name="cogs_accounts[{{ $index }}][ledger_name]"  value="{{ $item->ledger->name ?? '' }}" placeholder="Search Ledger">
                                                                    <input type="hidden" name="cogs_accounts[{{ $index }}][ledger_id]" value="{{ $item->ledger_id ?? '' }}" />
                                                                </td>
                                                                <!-- Ledger Group Select -->
                                                                <td>
                                                                    <input type="text" class="form-control min-width autocomplete ledger_group_name"  name="cogs_accounts[{{ $index }}][ledger_group_name]"   value="{{ $item->ledgerGroup->name ?? '' }}" placeholder="Search Ledger Group"/> 
                                                                    <input type="hidden" name="cogs_accounts[{{ $index }}][ledger_group_id]" value="{{ $item->ledger_group_id ?? '' }}" />
                                                                </td>
                                                                <!-- Actions -->
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
        const row = e.target.closest('tr');
        addRow(row);
    }
    if (e.target.closest('.delete-row')) {
        e.preventDefault();
        const row = e.target.closest('tr');
        const cogsAccountId = row.dataset.id;  // Changed to cogsAccountId
        if (cogsAccountId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Are you sure you want to delete this Record?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/cogs-accounts/${cogsAccountId}`, {  // Changed URL to cogs-accounts
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
                            Swal.fire('Error!', response.message || 'Could not delete COGS account.', 'error');
                        }
                    })
                    .catch(() => {
                        Swal.fire('Error!', 'An error occurred while deleting the COGS account.', 'error');
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
    const tableBody = document.querySelector('#cogsAccountsTable');
    const rows = tableBody.querySelectorAll('tr');
    const rowIndex = Array.from(rows).indexOf(clickedRow);
    const newRow = document.createElement('tr');
    newRow.dataset.id = '';
    newRow.innerHTML = `
        <td>${rowIndex + 1}</td> 
        <td>
            <select class="form-select select2" name="cogs_accounts[${rowIndex}][company_id]">
                <option value="">Select Company</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                @endforeach
            </select>
        </td>
        <td class="organization-field">
            <select class="form-select select2" name="cogs_accounts[${rowIndex}][organization_id]"> 
                <option value="">Select Organization</option>
            </select>
        </td>
        <td class="category-field">
            <input type="text" class="form-control min-width autocomplete category_name" name="cogs_accounts[${rowIndex}][category_name]" placeholder="Search Category">  <!-- Changed to cogs_accounts -->
            <input type="hidden" name="cogs_accounts[${rowIndex}][category_id]" />
        </td>
        <td class="sub-category-field">
            <input type="text" class="form-control min-width autocomplete sub_category_name" name="cogs_accounts[${rowIndex}][sub_category_name]" placeholder="Search Subcategory">  <!-- Changed to cogs_accounts -->
            <input type="hidden" name="cogs_accounts[${rowIndex}][sub_category_id]" />  
        </td>
        <td class="item-field">
            <select class="form-select select2" name="cogs_accounts[${rowIndex}][item_id][]" multiple> 
                <option value="">Select Item</option>
            </select>
        </td>
        <td class="book-field">
            <select class="form-select select2" name="cogs_accounts[${rowIndex}][book_id][]" multiple> 
                <option value="">Select Book</option>
            </select>
        </td>
        <td class="ledger-field">
            <input type="text" class="form-control min-width autocomplete ledger_name" name="cogs_accounts[${rowIndex}][ledger_name]" placeholder="Search Ledger">  <!-- Changed to cogs_accounts -->
            <input type="hidden" name="cogs_accounts[${rowIndex}][ledger_id]" /> 
        </td>
        <td class="ledger-field">
            <input type="text" class="form-control min-width autocomplete ledger_group_name" name="cogs_accounts[${rowIndex}][ledger_group_name]" placeholder="Search Ledger Group">
            <input type="hidden" name="cogs_accounts[${rowIndex}][ledger_group_id]" />
        </td>
        <td>
            <a href="#" class="text-primary me-50 add-row"><i data-feather="plus-square"></i></a>
            <a href="#" class="text-danger delete-row"><i data-feather="trash-2"></i></a>
        </td>
    `;
    clickedRow.insertAdjacentElement('afterend', newRow);
    $('.select2').select2();
    feather.replace();
    const allRows = tableBody.querySelectorAll('tr');
    updateRowIndexes();
}

function updateRowIndexes() {
    const tableBody = document.querySelector('#cogsAccountsTable');
    const rows = tableBody.querySelectorAll('tr');
    console.log("Updating row indexes...");

    rows.forEach((row, index) => {
        const rowIndexCell = row.querySelector('td');
        if (rowIndexCell) {
            rowIndexCell.textContent = index + 1;
        }
        row.querySelectorAll('input[name], select[name]').forEach(field => {
            const name = field.getAttribute('name');
            const updatedName = name.replace(/\[\d+\]/, `[${index}]`);
            field.setAttribute('name', updatedName);
            console.log(`Updated field name: ${name} => ${updatedName}`);
        });
    });
}

$(document).on('change', '[name^="cogs_accounts"][name$="[company_id]"]', function () {
        var companyId = $(this).val();
        var $row = $(this).closest('tr');
        var organizationSelect = $row.find('[name$="[organization_id]"]');
        
        organizationSelect.empty().append('<option value="">Select Organization</option>');
        
        if (companyId) {
            $.get(`/stock-accounts/organizations/${companyId}`, function (data) {
                if (data && data.organizations) {
                    data.organizations.forEach(function (org) {
                        organizationSelect.append(`<option value="${org.id}">${org.alias}</option>`);
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
        var categoryInput = $row.find('[name$="[category_name]"]');
        var categoryIdInput = $row.find('[name$="[category_id]"]');
        var ledgerInput = $row.find('[name$="[ledger_name]"]');
        var ledgerIdInput = $row.find('[name$="[ledger_id]"]');
        var itemSelect = $row.find('[name$="[item_id][]"]');
        var subCategorySelect = $row.find('[name$="[sub_category_id]"]');
        var bookSelect = $row.find('[name$="[book_id][]"]');
        categoryInput.val('');
        categoryIdInput.val('');
        ledgerInput.val('');
        ledgerIdInput.val('');
        itemSelect.empty().append('<option value="">Select Item</option>');
        subCategorySelect.empty().append('<option value="">Select Subcategory</option>');
        bookSelect.empty().append('<option value="">Select Book</option>');
        $.get(`/stock-accounts/data-by-organization/${organizationId}`, function (data) {
            categoryInput.autocomplete({
                source: function (request, response) {
                    if (data.categories.length === 0) {
                        response([{ label: "No records found", value: "" }]);
                    } else {
                        response($.map(data.categories, function (category) {
                            return {
                                label: category.name,
                                value: category.id
                            };
                        }));
                    }
                },
                minLength: 2,
                select: function (event, ui) {
                    if (ui.item.value === "") return false;
                    categoryInput.val(ui.item.label);
                    categoryIdInput.val(ui.item.value);
                    categoryIdInput.trigger('change');
                    return false;
                }
            });
            ledgerInput.autocomplete({
                source: function (request, response) {
                    if (data.ledgers.length === 0) {
                        response([{ label: "No records found", value: "" }]);
                    } else {
                        response($.map(data.ledgers, function (ledger) {
                            return {
                                label: ledger.name,
                                value: ledger.id
                            };
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
            data.erpBooks.forEach(function (book) {
                bookSelect.append(`<option value="${book.id}">${book.book_code}</option>`);
            });
            
            itemSelect.empty().append('<option value="">Select Item</option>');
            data.items.forEach(function (item) {
                itemSelect.append(`<option value="${item.id}">${item.item_code}</option>`);
            });
            itemSelect.select2();
            subCategorySelect.empty().append('<option value="">Select Subcategory</option>');
        }).fail(function () {
            Swal.fire('Error!', 'An error occurred while loading data for the selected organization.', 'error');
        });
    });
    $(document).on('focus input', '[name$="[category_name]"]', function () {
        var $input = $(this);
        var $row = $input.closest('tr');
        var categoryIdInput = $row.find('[name$="[category_id]"]');
        var organizationId = $row.find('[name$="[organization_id]"]').val();
        var searchTerm = $input.val(); 
        $input.next('.no-records').remove();
        if (!organizationId) return;
        $.get(`/stock-accounts/categories-by-organization/${organizationId}`, { search: searchTerm }, function (data) {
            console.log('API Response:', data); 
            var results = data.categories && data.categories.length > 0 ? 
                $.map(data.categories, function (category) {
                    return {
                        label: category.name,
                        value: category.id
                    };
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
                    categoryIdInput.val(ui.item.value); 
                    categoryIdInput.trigger('change');
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
                    categoryIdInput.val(ui.item.value);
                    categoryIdInput.trigger('change');
                    return false;
                }
            });
        });
    });

    $(document).on('focus input', '[name$="[sub_category_name]"]', function () {
        var $input = $(this);
        var $row = $input.closest('tr');
        var categoryIdInput = $row.find('[name$="[category_id]"]');
        var subCategoryIdInput = $row.find('[name$="[sub_category_id]"]');
        var categoryId = categoryIdInput.val(); 
        var searchTerm = $input.val(); 
        $input.next('.no-records').remove();
        
        if (!categoryId) {
            return; 
        }

        $.get(`/stock-accounts/sub-categories-by-category/${categoryId}`, { category_id: categoryId, search: searchTerm }, function (data) {
            var results = data.subCategories && data.subCategories.length > 0 ?
                $.map(data.subCategories, function (subCategory) {
                    return {
                        label: subCategory.name,
                        value: subCategory.id
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
                    subCategoryIdInput.val(ui.item.value);  
                    subCategoryIdInput.trigger('change'); 
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
                    subCategoryIdInput.val(ui.item.value);
                    subCategoryIdInput.trigger('change'); 
                    return false;
                }
            });
            Swal.fire('Error!', 'An error occurred while fetching subcategories for the selected category.', 'error');
        });
    });

    $(document).on('change input', '[name$="[category_id]"]', function () {
        var categoryId = $(this).val();
        var $row = $(this).closest('tr');
        var subCategoryInput = $row.find('[name$="[sub_category_name]"]');
        var subCategoryIdInput = $row.find('[name$="[sub_category_id]"]');
        var itemSelect = $row.find('[name$="[item_id][]"]');
        subCategoryInput.val('');
        subCategoryIdInput.val('');
        itemSelect.empty().append('<option value="">Select Item</option>');

        if (categoryId) {
            $.get(`/stock-accounts/items-and-subcategories-by-category`, { category_id: categoryId }, function (data) {
                subCategoryInput.autocomplete({
                    source: function (request, response) {
                        var results = $.map(data.subCategories, function (subCategory) {
                            return {
                                label: subCategory.name,
                                value: subCategory.id
                            };
                        });
                        response(results);
                    },
                    minLength: 2,
                    select: function (event, ui) {
                        subCategoryInput.val(ui.item.label);
                        subCategoryIdInput.val(ui.item.value);
                        subCategoryInput.trigger('change');
                        return false;
                    }
                });

                itemSelect.empty().append('<option value="">Select Item</option>');
                data.items.forEach(function (item) {
                    itemSelect.append(`<option value="${item.id}">${item.item_code}</option>`);
                });
                itemSelect.select2();
            }).fail(function () {
                Swal.fire('Error!', 'An error occurred while loading subcategories and items.', 'error');
            });
        }
    });
    $(document).on('change input', '[name$="[sub_category_id]"]', function () {
        var subCategoryId = $(this).val();
        var $row = $(this).closest('tr');
        var itemSelect = $row.find('[name$="[item_id][]"]');
        itemSelect.empty().append('<option value="">Select Item</option>');

        if (subCategoryId) {
            $.get(`/stock-accounts/items-by-subcategory`, { sub_category_id: subCategoryId }, function (data) {
                data.items.forEach(function (item) {
                    itemSelect.append(`<option value="${item.id}">${item.item_code}</option>`);
                });
                itemSelect.select2();
            }).fail(function () {
                Swal.fire('Error!', 'An error occurred while loading items.', 'error');
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
