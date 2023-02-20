<?php

namespace App\Exports;

use App\Purchase;
use App\Supplier;
use App\SupplierCreditList;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PayableHistoryExport implements FromArray,ShouldAutoSize,WithHeadings
{
    use Exportable;

    protected $from_date;
    protected $to_date;
    protected $id;

    public function __construct($from,$to,$id){

        $this->from_date =$from;
        $this->to_date =$to;
        $this->id=$id;

    }


   public function array() :array
    {

            // $creditamount = SupplierCreditList::



            if($this->id == 0){
                $payables= Purchase::whereBetween('purchase_date', [$this->from_date,$this->to_date])->get();
                $payable_lists = array();
                foreach($payables as $payable){

                    if($payable->credit_amount != 0){

                        $supplier_name=$payable->supplier_name;
                        $purchase_date=$payable->purchase_date;
                        $total_amount=$payable->total_price;
                        $credit_amount=$payable->credit_amount;

                        $combined = array('supplier_name'=> $supplier_name,'purchase_date'=>$purchase_date,'total_amount'=>$total_amount,'credit_amount'=>$credit_amount);
                        array_push($payable_lists,$combined);
                    }

                }
                return $payable_lists;
            }
            else if($this->id != 0){
                $payables= Purchase::whereBetween('purchase_date', [$this->from_date,$this->to_date])->where('supplier_id',$this->id)->get();
                $payable_lists = array();

                foreach($payables as $payable){

                    $supplier_name=$payable->supplier_name;

                    $purchase_date=$payable->purchase_date;
                    $total_amount=$payable->total_price;
                    $credit_amount=$payable->credit_amount;

                    $combined = array('supplier_name'=> $supplier_name,'purchase_date'=>$purchase_date,'total_amount'=>$total_amount,'credit_amount'=>$credit_amount);
                    array_push($payable_lists,$combined);
            }

            return $payable_lists;
    }
    }

    public function headings():array{

            return [
                'supplier_name',
                'purchase_date',
                'total_amount',
                'credit_amount'
        ];

    }


}
