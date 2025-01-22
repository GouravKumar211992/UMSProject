<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockLedger extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stock_ledger';

    // Define relationships
    public function attributes()
    {
        return $this->hasMany(StockLedgerItemAttribute::class, 'stock_ledger_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function company()
    {
        return $this->belongsTo(OrganizationCompany::class, 'company_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function store()
    {
        return $this->belongsTo(ErpStore::class, 'store_id');
    }

    public function rack()
    {
        return $this->belongsTo(ErpRack::class, 'rack_id');
    }

    public function shelf()
    {
        return $this->belongsTo(ErpShelf::class, 'shelf_id');
    }

    public function bin()
    {
        return $this->belongsTo(ErpBin::class, 'bin_id');
    }

    public function mrnHeader()
    {
        return $this->belongsTo(MrnHeader::class, 'document_header_id');
    }

    public function mrnDetail()
    {
        return $this->belongsTo(MrnDetail::class, 'document_detail_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
