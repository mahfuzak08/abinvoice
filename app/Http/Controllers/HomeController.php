<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SendTicket;
use App\Models\Customer;
use App\Models\Ticket;
use App\Models\User;

class HomeController extends Controller
{
    public function dashboard(){
        activity()->log('Logged in');
        if(Auth::user()->role->name == 'Officer')
            return redirect('tickets');
        // accounts_payable
        // $banks = Bankacc::get();
        // $accounts_payable_bid = 0;
        // $accounts_receivable_bid = 0;
        // foreach($banks as $r){
        //     if($r->type == 'Due' && $r->name == 'Due')
        //         $accounts_payable_bid = $r->id;
        //     if($r->type == 'Due' && $r->name == 'Due2')
        //         $accounts_receivable_bid = $r->id;
        // }
        
        // $data['accounts_payable'] = AccountTranx::where('account_id', '=', $accounts_payable_bid)
        //                             ->sum('amount');
        // $data['accounts_receivable'] = AccountTranx::where('account_id', '=', $accounts_receivable_bid)
        //                             ->sum('amount');
        $data = array();
        return view('admin.home', compact('data'));
    }

    public function tickets(){
        if(request()->input('id') == 'new'){
            $customers = Customer::where('is_delete', 0)->get();
            return view('admin.ticket.addnew', compact('customers'));
        }
        elseif(is_numeric(request()->input('delete'))){
            $ticket = Ticket::findOrFail(request()->input('delete'));
            $ticket->delete();
            flash()->addSuccess('Ticket delete successfully.');
            return redirect('tickets');
        }
        elseif(is_numeric(request()->input('id'))){
            $customers = Customer::where('is_delete', 0)->get();
            $status = array("Initialize", "In Progress", "Completed", "Cancel");
            $ticket = Ticket::join("customers", "tickets.client_id", "=", "customers.id")
                            ->join("users", "tickets.submit_by", "=", "users.id")
                            ->select('tickets.*', 'customers.name as customer_name', 'users.email as submitter_email')
                            ->where('tickets.id', request()->input('id'))
                            ->get();
            return view('admin.ticket.edit', compact('ticket', 'customers', 'status'));
        }
        else{
            $tickets = Ticket::join("customers", "tickets.client_id", "=", "customers.id")
                            ->select('tickets.*', 'customers.name as customer_name')
                            ->get();
            return view('admin.ticket.manage', compact('tickets'));
        }
    }

    public function ticket_save(Request $request){
        // print_r($request->all());
        $rules = [
            'customer_id' => ['required', 'numeric'],
            'title' => ['required', 'string', 'max:255'],
            // 'description' => ['string'],
            // 'img.*' => ['file', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            foreach ($validator->messages()->toArray() as $key => $value) { 
                flash()->addError($value[0]);
            }
            return redirect('tickets');
        }

        $uploadedFiles = [];
        // Check if files were uploaded
        if ($request->hasFile('img')) {
            
            foreach ($request->file('img') as $file) {
                // Generate a unique filename
                $filename = time() . '_' . $file->getClientOriginalName();
                
                // Store the file in the 'public/uploads' directory
                $uploadedFiles[] = $file->storeAs('public/uploads', $filename);
                
                // Save the public path to return or store in database
                // $uploadedFiles[] = [
                //     'original_name' => $file->getClientOriginalName(),
                //     'stored_name' => $filename,
                //     'path' => Storage::url($path),
                //     'size' => $file->getSize(),
                //     'mime_type' => $file->getMimeType(),
                // ];

            }
        }

        $input = array(
            "client_id" => $request->input('customer_id'),
            "title" => $request->input('title'),
            "priority" => $request->input('priority'),
            "description" => $request->input('description'),
        );

        if($request->input('id')){
            $input["closing_by"] = Auth::id();
            $input["status"] = $request->input('status');
            $data = Ticket::findOrFail($request->input('id'));
            $msg = 'Ticket Update Successfully.';
        }
        else{
            if(count($uploadedFiles) > 0){
                $input["imgs"] = json_encode($uploadedFiles);
            }
            $input["submit_by"] = Auth::id();
            $data = new Ticket();
            $msg = 'Ticket Initialize Successfully.';
            $user = User::find(Auth::id());
            $user->notify(new SendTicket());
            $admin = User::find(1); // Mahfuz 
            $admin->notify(new SendTicket()); 
        }
        
        $data->fill($input)->save();
        flash()->addSuccess($msg);
        return redirect('tickets');
    }
    
}
