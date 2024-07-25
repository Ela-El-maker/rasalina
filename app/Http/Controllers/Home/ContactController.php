<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;


class ContactController extends Controller
{
    //

    public function contactMe()
    {
        return view('frontend.home_all.contact');

    }

    public function storeMessage(request $request)
    {
        Contact::insert([
            'name' => $request ->name,
            'email' => $request ->email,
            'subject' => $request ->subject,
            'phone' => $request ->phone,
            'message' => $request ->message,
            'created_at'=>Carbon::now(),


        ]);
        $notification = [
            'message' => 'Message sent Successfully.',
            'alert-type' => 'success',
        ];
        return redirect()->back()->with($notification);

    }

    public function contactMessage()
    {
        $contactMessages = Contact::latest()->get();
        return view('admin.contact_page.contact_page_all',compact('contactMessages'));
    }

    public function deleteContactmessage($id)
    {
          // Find the item by its ID and throw a 404 error if not found
    try {
        $item = Contact::findOrFail($id);
      

        if ($item->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Message deleted successfully.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete the item.'
            ], 500);
        }
    } catch (\Exception $e) {
        Log::error('Error deleting item: ' . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred while deleting the item.'
        ], 500);
    }

    return redirect()->route('contact.message')->with($notification);

    }
}
