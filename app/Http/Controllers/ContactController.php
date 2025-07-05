<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function contacts()
    {
        
        $contacts = Contact::orderBy('created_at', 'desc')->paginate(10);
        $contactCount = Contact::count(); // assuming you are using Eloquent model 'Contact'
        $contacts = Contact::latest()->paginate(10); 

        return view('admin.contacts', compact('contacts', 'contactCount'));
    }


    public function contactstore(Request $request)
    {
        $this->validate($request, [
            'name'=> 'required|string|max:255',
            'email'=> 'required|email|max:255',
            'phone' => ['required', 'regex:/^0[0-9]{9}$/'],
            'message'=> 'required|string|max:1000',
        ]);

        $contact = new Contact();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->message = $request->message;

        $contact->save();

        return redirect()->back()->with('success', 'Thông tin liên hệ đã được gửi thành công!');
    }

    public function delete_contact($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return redirect()->back()->with('success', 'Xóa liên hệ thành công!');
    }
}
