<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PRTed extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'erp_purchase_return_ted';

    protected $fillable = [
        'header_id', 
        'detail_id', 
        'ted_id',
        'ted_type', 
        'ted_name',
        'ted_level', 
        'book_code', 
        'document_number', 
        'ted_code', 
        'assesment_amount', 
        'ted_percentage', 
        'ted_amount', 
        'applicability_type'
    ];

    public function header()
    {
        return $this->belongsTo(PRHeader::class, 'header_id');
    }

    public function detail()
    {
        return $this->belongsTo(PRDetail::class, 'detail_id');
    }

    public function taxDetail()
    {
        return $this->belongsTo(TaxDetail::class, 'ted_id');
    }
}
