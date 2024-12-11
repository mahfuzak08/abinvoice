<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountTranx;
use App\Models\Bankacc;
use App\Models\Purchase;
use App\Models\Product_tranx;
use App\Models\Vendor;

class PurchaseController extends Controller
{
    public function index(){
        if(! hasModuleAccess("Purchase"))
            return view('error403');
        $account = Bankacc::all();
        $vendor = Vendor::where('is_delete', 0)->get();
        if(hasModuleAccess("Inventory")){
            return view('admin.purchase.register_with_inventory', compact('vendor', 'account'));
        }else{
            if(! empty(request()->input('search'))){
                $str = request()->input('search');
                $datas = Vendor::select('vendors.*')
                                ->addSelect(DB::raw('(COALESCE((SELECT SUM(total_due) FROM purchases WHERE vendor_id = vendors.id AND status = 1), 0) + COALESCE((SELECT SUM(amount) FROM account_tranxes WHERE ref_id = vendors.id AND ref_type = "vendor" AND ref_tranx_id = "0"), 0)) as due'))
                                ->where(function ($query) use ($str){
                                    $query->where('name', 'like', '%'.$str.'%')
                                    ->orWhere('mobile', 'like', '%'.$str.'%')
                                    ->orWhere('email', 'like', '%'.$str.'%')
                                    ->orWhere('address', 'like', '%'.$str.'%');
                                })
                                ->where('is_delete', 0)
                                ->latest()->paginate(10)->withQueryString();
            }else{
                $datas = Vendor::select('vendors.*')
                                ->addSelect(DB::raw('(COALESCE((SELECT SUM(total_due) FROM purchases WHERE vendor_id = vendors.id AND status = 1), 0) + COALESCE((SELECT SUM(amount) FROM account_tranxes WHERE ref_id = vendors.id AND ref_type = "vendor" AND ref_tranx_id = "0"), 0)) as due'))
                                ->latest()
                                ->where('is_delete', 0)
                                ->paginate(10)
                                ->withQueryString();
            }
            return view('admin.purchase.register', compact('vendor', 'account', 'datas'))->with('i', (request()->input('page', 1) - 1) * 10);
        }
    }
    
    public function set_purchase(Request $request){
        $rules = [
            'vendor_new' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'total' => ['required', 'integer']
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            foreach ($validator->messages()->toArray() as $key => $value) { 
                flash()->addError($value[0]);
            }
            return redirect('purchase');
        }
        $vendor_id = $request->input('vendor_id');
        $due = 0;
        if($request->input('vendor_id') == null){
            // dd($request->input('vendor_id') == null);
            // exit();
            if($request->input('mobile') == null){
                flash()->addError('Mobile number is required.');
                return redirect('purchase');
            }
            DB::beginTransaction();
            try{
                $vinfo = new Vendor();
                
                $input = ["name"=>$request->input("vendor_new"), "address"=>$request->input('address'), "mobile"=>$request->input('mobile')];
                
                $vinfo->fill($input)->save();
        
                $vendor_id = $vinfo->id;
                
                flash()->addSuccess('New Vendor Added Successfully.');
                // If all queries succeed, commit the transaction
                DB::commit();
            }catch (\Exception $e) {
                // If any query fails, catch the exception and roll back the transaction
                flash()->addError('Vendor Not Added Successfully.');
                DB::rollback();
                return redirect('purchase');
            }
        }else{
            $vinfo = Vendor::findOrFail($vendor_id);
        }
        // $total_purchase = Purchase::where('vendor_id', $vendor_id)->sum('total');
        // $total_payment = AccountTranx::where('ref_id', $vendor_id)
        //                     ->where('ref_type', 'vendor')
        //                     ->where('ref_tranx_id', NULL)
        //                     ->sum('amount');
        // $due = $total_purchase - $total_payment;
        
        // dd($due);

        $due_acc_id = Bankacc::where('type', 'Due')->pluck('id');
        
        $pn = $request->input('product_name');
        $pd = $request->input('product_details');
        $qty = $request->input('quantity');
        $price = $request->input('price');
        $pid = $request->input('product_id');
        $ptype = $request->input('payment_type');
        $receive_amount = $request->input('receive_amount');
        
        $items = array();
        $payments = array();
        $total = 0;
        
        for($i=0; $i<count($pn); $i++){
            $items[] = [
                "pid"=>$pid[$i],
                "product_name"=>$pn[$i], 
                "product_details"=>$pd[$i], 
                "quantity"=>(float) $qty[$i],
                "price"=>(float) $price[$i],
                "total"=>(float) $qty[$i] * (float) $price[$i] 
            ];
            $total += (float) $qty[$i] * (float) $price[$i];
        }
        
        for($i=0; $i<count($ptype); $i++){
            $payments[] = [
                "pid"=>$ptype[$i],
                "receive_amount"=>(float) $receive_amount[$i]
            ];
            if(count($due_acc_id) > 0 && $ptype[$i] == $due_acc_id[0])
                $due += (float) $receive_amount[$i];
        }

        $discount = (float) $request->input('discount');
        $total -= $discount;

        if(! empty($request->input('order_id')) && $request->input('order_id') > 0){
            $input_order = [
                "order_type"=> "purchase",
                "user_id"=> Auth::id(),
                "vendor_id"=> $vendor_id,
                "products"=> json_encode($items),
                "date"=> $request->input('date'),
                "discount"=> $discount,
                "total"=> $total,
                "total_due"=> $due,
                "payment"=> json_encode($payments)
            ];

            try{
                DB::beginTransaction();
    
                $order = Purchase::findOrFail($request->input('order_id'));
                $op = json_decode($order->payment);
                $input_order["note"] = json_encode(array("old_date"=>$order->date, "old_vendor_id"=>$order->vendor_id, "old_products"=>$order->products, "old_payment"=>$order->payment, "old_discount"=>$order->discount));

                $order->fill($input_order)->save();
                $order_id = $request->input('order_id'); 

                // for($pi = 0; $pi < count($op); $pi++){
                //     if(count($due_acc_id) > 0 && $op[$pi]->pid == $due_acc_id[0])
                //         $due -= (float) $op[$pi]->receive_amount;
                // }

                // $vinfo->balance = $asofdue;
                // $vinfo->save();
    
                
                for($i=0; $i<count($ptype); $i++){
                    if(count($due_acc_id) > 0 && $ptype[$i] == $due_acc_id[0]) continue;
                    $tdata = AccountTranx::where('ref_tranx_id', $order_id)
                                        ->where('ref_tranx_type', 'purchase_order')
                                        ->where('ref_type', 'vendor')
                                        ->where('account_id', $ptype[$i])
                                        ->get()->toArray();
                    if(count($tdata) > 0){
                        $trnxdata = [
                            'user_id' => Auth::id(),
                            'tranx_date' => $request->input('date'),
                            'ref_id' => $vendor_id,
                            'amount' => (float) $receive_amount[$i] * -1,
                            'note' => 'This tranx updated. Old amount was '.$tdata[0]['amount']
                        ];
                        AccountTranx::where('id', $tdata[0]['id'])->update($trnxdata);
                    }
                    else{
                        $trnxdata = [
                            'account_id' => $ptype[$i],
                            'user_id' => Auth::id(),
                            'tranx_date' => $request->input('date'),
                            'ref_id' => $vendor_id,
                            'ref_type' => 'vendor',
                            'ref_tranx_id' => $order_id,
                            'ref_tranx_type' => 'purchase_order',
                            'note' => 'This tranx updated.',
                            'amount' => (float) $receive_amount[$i] * -1
                        ];
                        $tdata = new AccountTranx();
                        $tdata->fill($trnxdata)->save();
                    }
                }

                AccountTranx::where('ref_tranx_id', $order_id)->where('note', '')->delete();
    
                flash()->addSuccess('Purchase Order Updated Successfully.');
                DB::commit();
            }catch (\Exception $e) {
                // If any query fails, catch the exception and roll back the transaction
                dd($e);
                flash()->addError('Purchase Order Not Updated Successfully.');
                DB::rollback();
                return redirect('purchase');
            }
            return redirect("purchase_invoice/$order_id");
        }
        else{
            $moid = Purchase::max("order_id");
            $input_order = [
                "order_id"=> $moid > 0 ? $moid+1 : 786,
                "order_type"=> "purchase",
                "user_id"=> Auth::id(),
                "vendor_id"=> $vendor_id,
                "products"=> json_encode($items),
                "date"=> $request->input('date'),
                "discount"=> $discount,
                "total"=> $total,
                "payment"=> json_encode($payments),
                "total_due"=> $due,
            ];

            try{
                DB::beginTransaction();
    
                $order = New Purchase();
                $order->fill($input_order)->save();
                $order_id = $order->id; 
    
                // $vinfo->balance = $asofdue;
                // $vinfo->save();
    
                for($i=0; $i<count($ptype); $i++){
                    if(count($due_acc_id) > 0 && $ptype[$i] == $due_acc_id[0]) continue;
    
                    $trnxdata = [
                        'account_id' => $ptype[$i],
                        'user_id' => Auth::id(),
                        'tranx_date' => $request->input('date'),
                        'ref_id' => $vendor_id,
                        'ref_type' => 'vendor',
                        'ref_tranx_id' => $order_id,
                        'ref_tranx_type' => 'purchase_order',
                        'note' => '',
                        'amount' => (float) $receive_amount[$i] * -1
                    ];
                    $tdata = new AccountTranx();
                    $tdata->fill($trnxdata)->save();
                }

                if(hasModuleAccess("Inventory")){
                    $ptdata = array();
                    for($i=0; $i<count($pn); $i++){
                        $pidvid = explode("@", $pid[$i]);
                        $ptdata[] = array(
                            "product_id"=>$pidvid[0],
                            "variant_id"=>$pidvid[1],
                            "order_id"=>$order_id, 
                            "order_type"=>'purchase', 
                            "date"=>$request->input('date'),
                            "inout"=>'in',
                            "qty"=>(float) $qty[$i], 
                            "actual_buy_price"=>(float) $price[$i], 
                        );
                    }
                    Product_tranx::insert($ptdata);
                }
    
                flash()->addSuccess('Purchase Order Added Successfully.');
                DB::commit();
            }catch (\Exception $e) {
                // If any query fails, catch the exception and roll back the transaction
                dd($e);
                flash()->addError('Purchase Order Not Added Successfully.');
                DB::rollback();
                return redirect('purchase');
            }
            return redirect("purchase_invoice/$order_id");
        }
    }

    public function invoice($id){
        $invoice = Purchase::join("vendors", "purchases.vendor_id", "=", "vendors.id")
                            ->select('purchases.*', 'vendors.name as vendor_name', 'vendors.mobile', 'vendors.address')
                            ->where("purchases.id", $id)
                            ->get();
        $account = Bankacc::all();
        
        return view('admin.purchase.invoice', compact('invoice', 'account'));
    }
    
    public function purchase_edit($id){
        $order = Purchase::join("vendors", "purchases.vendor_id", "=", "vendors.id")
                            ->select('purchases.*', 'vendors.name as vendor_name', 'vendors.mobile', 'vendors.address')
                            ->where("purchases.id", $id)
                            ->get();
        $account = Bankacc::all();
        $vendor = Vendor::where('is_delete', 0)->get();
        
        return view('admin.purchase.register_edit', compact('order', 'account', 'vendor'));
    }
    
    public function purchase_delete($id){
        try{
            DB::beginTransaction();
            $order = Purchase::findOrFail($id);
            $order->order_type = "purchase_delete";
            $order->status = 0;
            $order->note = "Deleted By ". Auth::user()->name . "( User Id: ". Auth::id() .")";
            
            $ptype = json_decode($order->payment);

            $order->save();

            $due_acc_id = Bankacc::where('type', 'Due')->pluck('id');
            
            for($i=0; $i<count($ptype); $i++){
                if(count($due_acc_id) > 0 && $ptype[$i]->pid == $due_acc_id[0]){
                    $v = Vendor::findOrFail($order->vendor_id); 
                    $v->balance -= $ptype[$i]->receive_amount;
                    $v->save();
                }
            }

            AccountTranx::where('ref_tranx_id', $id)->delete();
            flash()->addSuccess('Purchase Order Deleted Successfully.');
            DB::commit();
        }catch (\Exception $e) {
            dd($e);
            flash()->addError('Purchase Order Unable To Delete');
            DB::rollback();
            return redirect('purchase');
        }
        
        return redirect("reportPurchase");
    }
    
}
