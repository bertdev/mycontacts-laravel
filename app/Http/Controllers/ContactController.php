<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = DB::table('contacts')
            ->leftJoin('categories', 'contacts.category_id', '=', 'categories.id')
            ->select('contacts.*','categories.name as category_name')
            ->get();
        return response($contacts, 200);
    }

    public function show(string $id)
    {
        $contact = DB::table('contacts')
            ->leftJoin('categories', 'contacts.category_id', '=', 'categories.id')
            ->select('contacts.*','categories.name as category_name')
            ->where('contacts.id', '=', $id)
            ->get();
        if (count($contact) <= 0) {
            return response(['errors' => 'Contact not found.'], 400);
        }
        return response($contact, 200);
    }

    public function store(Request $request)
    {
        $validation = validator($request->only(['name', 'email', 'phone', 'category_id']),[
            'name' => 'required|min:3',
            'email' => 'required|email|unique:contacts,email',
            'phone' => 'required|min:8',
            'category_id' => 'required|uuid'
        ]);
        if ($validation->fails()) {
            return response(['errors' => $validation->errors()->first()], 400);
        }
        $newContact = Contact::create([
            'id' => Str::uuid(),
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'category_id' => $request->category_id
        ]);
        return  response($newContact, 200);
    }

    public function update(string $id, Request $request)
    {
        $contact = Contact::find($id);
        if (is_null($contact)) {
            return response(['errors' => 'Contact not found.'], 400);
        }
        $validation = validator($request->only(['name', 'email', 'phone', 'category_id']),[
            'name' => 'min:3',
            'email' => 'email|unique:contacts,email',
            'phone' => 'min:8',
            'category_id' => 'uuid'
        ]);
        if ($validation->fails()) {
            return response(['errors' => $validation->errors()->first()], 400);
        }
        $contact->name = is_null($request->name) ? $contact->name : $request->name;
        $contact->email = is_null($request->email) ? $contact->email : $request->email;
        $contact->phone = is_null($request->phone) ? $contact->phone : $request->phone;
        $contact->category_id = is_null($request->category_id) ? $contact->category_id : $request->category_id;
        $contact->save();
        return response(null, 204);
    }

    public function delete(string $id)
    {
        $contact = Contact::find($id);
        if (is_null($contact)) {
            return response(['errors' => 'Contact not found.'], 400);
        }
        Contact::destroy($id);
        return response(null, 204);
    }
}
