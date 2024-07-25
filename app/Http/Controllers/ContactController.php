<?php

namespace App\Http\Controllers;
use App\Client;
use App\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // Add Contacts
    public function newContact(Request $request)
    {
        $client = Client::find($request->CompanyId);

        if ($request->has('ContactName')) {
            foreach ($request->ContactName as $key => $contact_name) {
                if (!empty($contact_name)) {
                    // Create new contact
                    Contact::create([
                        'CompanyId' => $client->id,
                        'ContactName' => $contact_name,
                        'Designation' => $request->Designation[$key] ?? null,
                        'PrimaryTelephone' => $request->PrimaryTelephone[$key] ?? null,
                        'SecondaryTelephone' => $request->SecondaryTelephone[$key] ?? null,
                        'PrimaryMobile' => $request->PrimaryMobile[$key] ?? null,
                        'SecondaryMobile' => $request->SecondaryMobile[$key] ?? null,
                        'EmailAddress' => $request->EmailAddress[$key] ?? null,
                        'Skype' => $request->Skype[$key] ?? null,
                        'Viber' => $request->Viber[$key] ?? null,
                        'Facebook' => $request->Facebook[$key] ?? null,
                        'WhatsApp' => $request->WhatsApp[$key] ?? null,
                        'LinkedIn' => $request->LinkedIn[$key] ?? null,
                        'Birthday' => $request->Birthday[$key] ?? null
                    ]);  
                }
            }
        }

        return response()->json(['success' => 'Successfully Saved']);
    }

    // Edit Contacts
    public function editContact(Request $request, $id)
    {
        $client = Client::find($request->CompanyId);

        if (!$client) {
            return response()->json(['error' => 'Client not found.'], 404);
        }

        $contact = Contact::findOrFail($id); // Find the contact by ID

        $contact->update([
            'CompanyId' => $client->id,
            'ContactName' => $request->ContactName,
            'Designation' => $request->Designation,
            'PrimaryTelephone' => $request->PrimaryTelephone,
            'SecondaryTelephone' => $request->SecondaryTelephone,
            'PrimaryMobile' => $request->PrimaryMobile,
            'SecondaryMobile' => $request->SecondaryMobile,
            'EmailAddress' => $request->EmailAddress,
            'Skype' => $request->Skype,
            'Viber' => $request->Viber,
            'Facebook' => $request->Facebook,
            'WhatsApp' => $request->WhatsApp,
            'LinkedIn' => $request->LinkedIn,
            'Birthday' => $request->Birthday
        ]);

        return response()->json(['success' => 'Contact updated successfully']);
    }

    // Delete
    public function delete($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return response()->json(['message' => 'Contact deleted successfully!']);
    }
}
