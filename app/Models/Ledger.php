<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DefaultGroupCompanyOrg;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Deletable;



class Ledger extends Model
{
    protected $table = 'erp_ledgers';

    use HasFactory,SoftDeletes,DefaultGroupCompanyOrg;

    protected $fillable = [
        'ledger_group_id',
        'code',
        'name',
        'cost_center_id',
        'status',
        'group_id',
        'company_id',
        'organization_id'
    ];
    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class);
    }
    public function deleteWithReferences($referenceTables)
{
    $referencedTables = [];

    // Loop through reference tables and check if the ledger is being used
    foreach ($referenceTables as $table => $columns) {
        foreach ($columns as $column) {
            $exists = DB::table($table)->where($column, $this->id)->exists();

            if ($exists) {
                // If reference exists, prevent deletion and add table to list
                $referencedTables[] = $table;
            }
        }
    }

    // If references exist, return status as false with message
    if (count($referencedTables) > 0) {
        return [
            'status' => false,
            'message' => 'Record cannot be deleted because it is already in use.',
            'referenced_tables' => $referencedTables
        ];
    }

    // If no references, proceed with soft delete
    $this->delete();

    return [
        'status' => true,
        'message' => 'Item deleted successfully.'
    ];
}

    public function group()
    {
        $groupIds = json_decode($this->ledger_group_id, true);

        if (is_array($groupIds)) {
            return Group::whereIn('id', $groupIds)->get();
        }

        if (is_numeric($this->ledger_group_id)) {
            return $this->belongsTo(Group::class, 'ledger_group_id')->getResults();
        }
        return null;
    }

    public function groups()
    {
        $groupIds = json_decode($this->ledger_group_id, true);
        if (is_array($groupIds)) {
            return Group::whereIn('id', $groupIds)->get();
        }
        if (is_numeric($this->ledger_group_id)) {
            return $this->belongsTo(Group::class, 'ledger_group_id');
        }
        return null;
    }

    public function parent()
    {
        return $this->belongsTo(Ledger::class, 'parent_ledger_id');
    }

    public function details()
    {
        return $this->hasMany(ItemDetail::class, 'ledger_id', 'id');
    }
    
}
