<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Contact;

class ContactController extends Controller
{
    public function contacts(){
        if(request()->input('id') == 'new'){
            return view('admin.contact.addnew');
        }
        elseif(is_numeric(request()->input('delete'))){
            $contact = Contact::findOrFail(request()->input('delete'));
            $contact->delete();
            flash()->addSuccess('Contact delete successfully.');
            return redirect('contacts');
        }
        elseif(is_numeric(request()->input('id'))){
            $contact = Contact::where('contacts.id', request()->input('id'))->get();
            return view('admin.contact.edit', compact('contact'));
        }
        else{
            $contacts = Contact::all();
            return view('admin.contact.manage', compact('contacts'));
        }
    }

    public function contact_save(Request $request){
        // print_r($request->all());
        $rules = [
            'name' => ['required', 'string'],
            'mobile' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string']
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            foreach ($validator->messages()->toArray() as $key => $value) { 
                flash()->addError($value[0]);
            }
            return redirect('contacts');
        }

        $uploadedFiles = [];
        // Check if files were uploaded
        if ($request->hasFile('img')) {
            
            foreach ($request->file('img') as $file) {
                // Generate a unique filename
                $filename = time() . '_' . $file->getClientOriginalName();
                
                // Store the file in the 'public/uploads' directory
                $uploadedFiles[] = $file->storeAs('public/uploads', $filename);
                
            }
        }

        $input = $request->all();

        if($request->input('id')){
            $data = Contact::findOrFail($request->input('id'));
            $msg = 'Contact Update Successfully.';
        }
        else{
            if(count($uploadedFiles) > 0){
                $input["img"] = $uploadedFiles[0];
            }
            $data = new Contact();
            $msg = 'Contact Save Successfully.';
        }
        
        $data->fill($input)->save();
        flash()->addSuccess($msg);
        return redirect('contacts');
    }
    
}
