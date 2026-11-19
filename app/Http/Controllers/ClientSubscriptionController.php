<?php

namespace App\Http\Controllers;

use App\Models\VendorUsers;
use Illuminate\Support\Facades\Auth;

/**
 * #114: subscriptions a provider sells to its own clients (for example a monthly home cleaning).
 *
 * The provider version of the store panel's Customer Subscriptions. Not to be confused with "My Subscriptions"
 * (what the provider pays the platform). Own collections: provider_subscription_plans (written here),
 * provider_subscriptions and provider_subscription_payments (written by the website when a client pays; read-only here).
 */
class ClientSubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('client_subscriptions.index')->with('id', $this->providerId());
    }

    public function create()
    {
        return view('client_subscriptions.create')->with('id', $this->providerId());
    }

    public function edit($id)
    {
        return view('client_subscriptions.edit')->with('planId', $id)->with('id', $this->providerId());
    }

    public function subscribers()
    {
        return view('client_subscriptions.subscribers')->with('id', $this->providerId());
    }

    public function payments()
    {
        return view('client_subscriptions.payments')->with('id', $this->providerId());
    }

    /** The provider's Firestore id (users/{id}); the screens only ever show this provider's own records. */
    private function providerId()
    {
        $exist = VendorUsers::where('user_id', Auth::id())->first();
        abort_if(!$exist, 403);

        return $exist->uuid;
    }
}
