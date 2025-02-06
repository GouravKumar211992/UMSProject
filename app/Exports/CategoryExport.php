<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class CategoryExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $categories = Category::orderBy('id', 'DESC');

        if($this->request->search) {
            $keyword = $this->request->search;
            $categories->where(function($q) use ($keyword){

                $q->where('name', 'LIKE', '%'.$keyword.'%');

            });
        }
        if(!empty($this->request->name)){
            
            $categories->where('name','LIKE', '%'.$this->request->name.'%');
        }
        
        $categories = $categories->get();
        return $categories;
    }

    public function map($categories): array
    {
        return [
            $categories->name,
            date('Y-m-d',strtotime($categories->created_at)),

        ];
    }

    public function headings(): array
    {
        $columns = array('Name','Create Date');

        return $columns;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:B1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
            }
        ];
    }
}