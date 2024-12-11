<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountTranx;
use App\Models\Bankacc;
use App\Models\Employee;
use App\Models\Attendance;

class EmployeeController extends Controller
{
    public function index(){
        if(! empty(request()->input('search'))){
            $str = request()->input('search');
            $datas = Employee::where(function ($query) use ($str){
                                $query->where('name', 'like', '%'.$str.'%')
                                ->orWhere('mobile', 'like', '%'.$str.'%')
                                ->orWhere('gender', 'like', '%'.$str.'%')
                                ->orWhere('designation', 'like', '%'.$str.'%')
                                ->orWhere('nid', 'like', '%'.$str.'%')
                                ->orWhere('address', 'like', '%'.$str.'%');
                            })
                            ->orderBy('name', 'ASC')->paginate(10)->withQueryString();
        }else{
            $datas = Employee::orderBy('name', 'ASC')->paginate(10)->withQueryString();
        }
        return view('admin.employee.manage', compact('datas'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function open_employee_form(){
        return view('admin.employee.addnew');
    }

    public function set_employee(Request $request){
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'digits:13', 'unique:employees,mobile']
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            foreach ($validator->messages()->toArray() as $key => $value) { 
                flash()->addError($value[0]);
            }
            return redirect('add_new_employee');
        }
        
        DB::beginTransaction();
        try{
            $data = new Employee();
            
            $input = $request->all();
            $input['salary'] = empty($input['salary']) ? 0 : $input['salary']; 
            $input['bonus'] = empty($input['bonus']) ? 0 : $input['bonus'];
            $data->fill($input)->save();
    
            flash()->addSuccess('New Data Added Successfully.');
            // If all queries succeed, commit the transaction
            DB::commit();
        }catch (\Exception $e) {
            dd($e);
            exit();
            // If any query fails, catch the exception and roll back the transaction
            flash()->addError('Data Not Added Successfully.');
            DB::rollback();
        }
        return redirect('employee');
    }

    public function edit_employee($id){
        $employee = Employee::findOrFail($id);
        return view('admin.employee.edit', compact('employee'));
    }
    
    public function update_employee(Request $request, $id){
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'digits:13']
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            foreach ($validator->messages()->toArray() as $key => $value) { 
                flash()->addError($value[0]);
            }
            return redirect('add_new_employee');
        }
        
        DB::beginTransaction();
        try{
            $data = Employee::findOrFail($id);
            $input = $request->all();
            $input['salary'] = empty($input['salary']) ? $data->salary : $input['salary']; 
            $input['bonus'] = empty($input['bonus']) ? $data->bonus : $input['bonus'];
            $data->update($input);

            DB::commit();
            // If all queries succeed, commit the transaction
            flash()->addSuccess('Data Update Successfully.');
        }catch (\Exception $e) {
            // If any query fails, catch the exception and roll back the transaction
            flash()->addError('Data Not Updated Successfully.');
            DB::rollback();
        }
        return redirect('employee');
    }

    public function delete_employee($id){
        $data = Employee::findOrFail($id);
        $data->closing = date('Y-m-d');
        $data->save();
        flash()->addSuccess('Data Delete Successfully.');
        return redirect('employee');
    }

    public function see_employee($id){
        $employee = Employee::findOrFail($id);
        $banks = Bankacc::where('type', '!=', 'Due')->get();
        if(! empty(request()->input('search'))){
            $str = request()->input('search');
            $datas = AccountTranx::join('bankaccs', 'account_tranxes.account_id', '=', 'bankaccs.id')
                            ->where(function ($query) use ($str){
                                $query->where('tranx_date', 'like', '%'.$str.'%')
                                ->orWhere('amount', 'like', '%'.$str.'%')
                                ->orWhere('bankaccs.name', 'like', '%'.$str.'%')
                                ->orWhere('note', 'like', '%'.$str.'%');
                            })
                            ->where('ref_id', $id)
                            ->where('ref_type', 'employee')
                            ->select('account_tranxes.*', 'bankaccs.name as bank_name')
                            ->latest()->paginate(10)->withQueryString();
        }else{
            $datas = AccountTranx::join('bankaccs', 'account_tranxes.account_id', '=', 'bankaccs.id')
                    ->where('ref_id', $id)
                    ->where('ref_type', 'employee')
                    ->select('account_tranxes.*', 'bankaccs.name as bank_name')
                    ->latest()->paginate(10)->withQueryString();
        }
        $total_receive = AccountTranx::where('ref_id', $id)->where('ref_type', 'employee')->sum('amount');
        $yearly_attendance = Attendance::where('emp_id', $id)->where('hours', 8)->count();
        return view('admin.employee.details', compact('employee', 'banks', 'datas', 'total_receive', 'yearly_attendance'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function add_amount(Request $request){
        $rules = [
            'account_id' => ['required'],
            'tranx_date' => ['required', 'date'],
            'amount' => ['required', 'numeric']
        ];
        $id = $request->input('account_id');
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            foreach ($validator->messages()->toArray() as $key => $value) { 
                flash()->addError($value[0]);
            }
            return redirect($request->input('redirect_url'));
        }

        DB::beginTransaction();
        try{
            if(!empty($request->input('id'))){
                $data = AccountTranx::where('id', $request->input('id'))
                                    ->where('ref_id', $request->input('ref_id'))
                                    ->where('ref_type', $request->input('ref_type'))
                                    ->get();

                $input = $request->all();
                $input['amount'] *= -1;
                $input['note'] = $input['note'] . ' (Edited by ' . Auth::user()->name . ' and Old amount was ' . $data[0]->amount*-1;
                $data[0]->fill($input)->save();
                flash()->addSuccess('Data Update Successfully.');
            }else{
                $data = new AccountTranx();
                
                $input = $request->all();
                $input['amount'] *= -1;
                $input['user_id'] = Auth::id();
                $data->fill($input)->save();
                flash()->addSuccess('New Data Added Successfully.');
            }
    
            // If all queries succeed, commit the transaction
            DB::commit();
        }catch (\Exception $e) {
            dd($e);
            // If any query fails, catch the exception and roll back the transaction
            flash()->addError('Data Not Added or Update Successfully.');
            DB::rollback();
        }
        return redirect($request->input('redirect_url'));
    }


    public function attendance(Request $request){
        $d = $request->input('oldDate') ? $request->input('oldDate') : date('Y-m-d');
        if(! empty(request()->input('search'))){
            $str = request()->input('search');
            $employee = Employee::Where('name', 'like', '%'.$str.'%')
                            ->orWhere('mobile', 'like', '%'.$str.'%')
                            ->orWhere('designation', 'like', '%'.$str.'%')
                            ->orderBy('name', 'ASC')->get();
        }else{
            $employee = Employee::all()->toArray();
        }
        
        $attendance = Attendance::where('date', $d)->where('hours', 8)->pluck('emp_id')->toArray();
        for($i=0; $i<count($employee); $i++){
            $employee[$i]['attendance'] = 'no';
            if(in_array($employee[$i]['id'], $attendance)){
                $employee[$i]['attendance'] = 'yes';
            }
        }
        return view('admin.employee.attendance', compact('employee'));
    }

    public function save_attendance(Request $request){
        $attendance_data = array();
        $d = $request->input('attendance-date');
        $attendance = $request->input('attendance');
        $empid = $request->input('empid');
        for($i=0; $i<count($attendance); $i++){
            $status = $attendance[$i] == "true" ? 8 : 0;
            $attendance_data = [
                'date' => $d,
                'emp_id'=> $empid[$i],
                'hours'=> $status,
                'user_id'=> Auth::id()
            ];
            $existingRecord = Attendance::where('date', $d)->where('emp_id', $empid[$i])->first();
            if ($existingRecord) {
                if($status != $existingRecord->hours){
                    $existingRecord->update([
                        'hours' => $status,
                        'user_id' => Auth::id()
                    ]);
                }
            } else {
                $data = new Attendance();
                $data->fill($attendance_data)->save();
            }
        }
        
        flash()->addSuccess('Attendance taken successfully.');
        return redirect('attendance');
    }

    private function getLastDayOfMonth($year, $month) {
        $lastDayOfMonth = date('Y-m-d', strtotime($year . '-' . $month . '-' . date('t', strtotime($year . '-' . $month . '-01'))));
        return $lastDayOfMonth;
    }
    
    public function attendance_report(Request $request){
        $inputYearMonth = $request->input('month') ? $request->input('month') : date('Y-m');
        list($year, $month) = explode('-', $inputYearMonth);
        $lastDay = $this->getLastDayOfMonth($year, $month);
        // dd($lastDay);
        list($year, $month, $totalDays) = explode('-', $lastDay);
        $firstDay = $year."-".$month."-01";
        $monthYear = date("F, Y", strtotime($year."-".$month."-01"));
        if(! empty($request->input('empid')) && $request->input('empid') != 'all')
            $employee = Employee::where('id', $request->input('empid'))->get()->toArray();
        else
            $employee = Employee::all()->toArray();
        $attendance = Attendance::whereBetween('date', [$firstDay, $lastDay])->get()->toArray();
        
        for($i=0; $i<count($employee); $i++){
            $employee[$i]['attendance'] = array();
            for($j=0; $j<count($attendance); $j++){
                if($attendance[$j]['emp_id'] == $employee[$i]['id']){
                    $employee[$i]['attendance'][] = $attendance[$j];
                }
            }
        }
        // dd($employee);
        return view('admin.employee.attendance-report', compact('employee', 'monthYear', 'totalDays', 'inputYearMonth'));
    }

    public function employee_trnx_edit($id){
        $order = AccountTranx::join('bankaccs', 'account_tranxes.account_id', '=', 'bankaccs.id')
                            ->join('employees', 'account_tranxes.ref_id', '=', 'employees.id')
                            ->where('account_tranxes.id', $id)
                            ->where('ref_type', 'employee')
                            ->select('account_tranxes.*', 'employees.name as employee_name', 'bankaccs.name as bank_name')
                            ->get();
        $account = Bankacc::all();
        
        return view('admin.employee.register_edit', compact('order', 'account'));
    }
    
    public function employee_trnx_delete($id){
        try{
            DB::beginTransaction();
            AccountTranx::where('id', $id)->delete();
            flash()->addSuccess('Employee Transection Deleted Successfully.');
            DB::commit();
        }catch (\Exception $e) {
            dd($e);
            flash()->addError('Employee Transection Unable To Delete');
            DB::rollback();
            return redirect('employee');
        }
        
        return redirect("employee");
    }

}
