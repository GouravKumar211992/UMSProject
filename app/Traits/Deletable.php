<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

trait Deletable
{
    public function isReferenced()
    {
        $table = $this->getTable();
        $primaryKey = $this->getKeyName();
        $id = $this->getKey();
        $modelName = class_basename($this); 

        try {
            $foreignKeys = DB::select(
                "
                SELECT
                    TABLE_NAME AS referencing_table,
                    COLUMN_NAME AS referencing_column
                FROM
                    INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE
                    REFERENCED_TABLE_NAME = :table
                    AND REFERENCED_COLUMN_NAME = :column
                    AND TABLE_SCHEMA = DATABASE()
                ",
                ['table' => $table, 'column' => $primaryKey]
            );

            $referencingTables = [];

            foreach ($foreignKeys as $fk) {
                $referencingTable = $fk->referencing_table;
                $referencingColumn = $fk->referencing_column;

                $count = DB::table($referencingTable)
                    ->where($referencingColumn, $id)
                    ->count();

                if ($count > 0) {
                    if (!isset($referencingTables[$referencingTable])) {
                        $referencingTables[$referencingTable] = [];
                    }
                    $referencingTables[$referencingTable][] = $referencingColumn;
                }
            }

            if (!empty($referencingTables)) {
                $messages = [];
                foreach ($referencingTables as $table => $columns) {
                    $columnList = implode(', ', $columns);
                    $messages[] = "Table '$table'";
                }
                // $message = "{$modelName} cannot be deleted because it is already in use";
                $message = "Record cannot be deleted because it is already in use";
                
                return [
                    'status' => false,
                    'message' => $message
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'Error fetching foreign keys: ' . $e->getMessage()
            ];
        }

        return ['status' => true];
    }

    public function deleteWithReferences(array $referenceTables = [])
    {
        $id = $this->getKey(); 
        $table = $this->getTable(); 
        $totalReferences = 0;
        $referencedTables = []; 
        $linkedTables = [];
        $allReferences = $this->getReferencedTablesAndColumns();
        
        if (!$allReferences['status']) {
            return [
                'status' => false,
                'message' => $allReferences['message']
            ];
        }
    
        foreach ($allReferences['referenced_tables'] as $referencingTable => $columns) {
            if (array_key_exists($referencingTable, $referenceTables)) {
                $linkedRecords = DB::table($referencingTable)
                    ->where($columns[0] ?? 'id', $id) 
                    ->get();
                if ($linkedRecords->isNotEmpty()) {
                    $totalReferences += $linkedRecords->count();
                    $linkedTables[] = $referencingTable; 
                }
            } else {
                foreach ($columns as $column) {
                    $linkedRecords = DB::table($referencingTable)
                        ->where($column, $id)
                        ->get();
                    if ($linkedRecords->isNotEmpty()) {
                        Log::warning("Attempted to delete {$table} ID {$id} but found references in '{$referencingTable}', column '{$column}'. Linked Records: ", $linkedRecords->toArray());
                        return [
                            'status' => false,
                            'message' => "Record cannot be deleted because it is already in use.",
                            'referenced_tables' => $linkedTables
                        ];
                    }
                }
            }
    
        }
        
        # Code by shobhit
        foreach ($this->getCachedModels() as $modelClass) {
            $relatedModel = resolve($modelClass);
            if (property_exists($relatedModel, 'referencingRelationships')) {
                foreach ($relatedModel->referencingRelationships as $relationMethod => $foreignKey) {
                    try {

                        if (method_exists($relatedModel, $relationMethod)) {
                            $relationQuery = $relatedModel->$relationMethod();
                            $relatedTable = $relationQuery->getRelated()->getTable();

                            if($table == $relatedTable) {
                                $relationQuery = $relatedModel;
                                if ($relationQuery->where($foreignKey, $id)->exists()) {
                                    $referencedTables[] = $modelClass;
                                    $linkedTables[] = $modelClass;
                                    $linkedRecords = $relationQuery->where($foreignKey, $id)->get();
                                    Log::warning("Attempted to delete {$table} ID {$id} but found references in '{$modelClass}', which is not in the specified reference tables. Linked Records: ", $linkedRecords->toArray());
                                    return [
                                        'status' => false,
                                        'message' => 'Record cannot be deleted because it is already in use.',
                                        'referenced_tables' => $linkedTables
                                    ];
                                }
                            }
                            
                        } else {
                            Log::warning("The method '{$relationMethod}' is not defined in {$modelClass}.");
                        }
                    } catch (\Throwable $e) {
                        Log::error("Error processing {$relationMethod} in {$modelClass}: " . $e->getMessage());
                    }
                }   
            }
        }

        foreach ($referenceTables as $referenceTable => $columnNames) {
            foreach ($columnNames as $columnName) {
                $count = DB::table($referenceTable)
                    ->where($columnName, $id)
                    ->count();
    
                if ($count > 0) {
                    $deletedCount = DB::table($referenceTable)
                        ->where($columnName, $id)
                        ->delete();
    
                    if ($deletedCount > 0) {
                        Log::info("Deleted {$deletedCount} references from table '{$referenceTable}' for {$table} ID {$id}.");
                        $totalReferences += $deletedCount; 
                        $referencedTables[] = $referenceTable; 
                    }
                }
            }
        }
        
        if ($totalReferences > 0 || count($linkedTables) === 0) { 
            $this->delete(); 
            Log::info("Deleted {$table} ID {$id} successfully.");
            return [
                'status' => true,
                'message' => 'Item deleted successfully.'
            ];
        }
        Log::info("Item cannot be deleted as it is referenced in other tables: " . implode(", ", $referencedTables));

        return [
            'status' => false,
            'message' => 'Item cannot be deleted as it is referenced in other tables.',
            'referenced_tables' => $referencedTables
        ];
    }
    
    public function getReferencedTablesAndColumns()
    {
        $table = $this->getTable();
        $primaryKey = $this->getKeyName();

        try {
            $foreignKeys = DB::select(
                "
                SELECT
                    TABLE_NAME AS referencing_table,
                    COLUMN_NAME AS referencing_column
                FROM
                    INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE
                    REFERENCED_TABLE_NAME = :table
                    AND REFERENCED_COLUMN_NAME = :column
                    AND TABLE_SCHEMA = DATABASE()
                ",
                ['table' => $table, 'column' => $primaryKey]
            );

            $referencingTables = [];
            foreach ($foreignKeys as $fk) {
                $referencingTable = $fk->referencing_table;
                $referencingColumn = $fk->referencing_column;
                if (!isset($referencingTables[$referencingTable])) {
                    $referencingTables[$referencingTable] = [];
                }
                $referencingTables[$referencingTable][] = $referencingColumn;
            }

            return [
                'status' => true,
                'referenced_tables' => $referencingTables
            ];

        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'Error fetching foreign keys: ' . $e->getMessage()
            ];
        }
    }

    protected function getCachedModels()
    {
        return Cache::remember('model_relations', 3600, function () {
            $modelFiles = File::allFiles(app_path('Models'));
            $models = [];
            foreach ($modelFiles as $file) {
                $relativePath = $file->getRelativePathname();
                $classPath = str_replace(['/', '.php'], ['\\', ''], $relativePath);
                $modelClass = 'App\\Models\\' . $classPath;
                if (class_exists($modelClass)) {
                    $models[] = $modelClass;
                }
            }
            return $models;
        });
    }    
}
