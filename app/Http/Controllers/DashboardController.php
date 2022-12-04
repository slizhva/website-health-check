<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use App\Models\Links;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $links = Links
            ::where('user', Auth::id())
            ->orderBy('id', 'desc')
            ->get(['id', 'link', 'status'])
            ->toArray();

        return view('dashboard', [
            'links' => $links
        ]);
    }

    public function add(Request $request):RedirectResponse
    {
        $links = new Links;
        $links->user = Auth::id();
        $links->link = $request->get('link');
        $links->save();

        return redirect()->route('dashboard');
    }

    public function delete(Request $request):RedirectResponse
    {
        Links
            ::where('id', $request->get('id'))
            ->where('user', Auth::id())
            ->delete();

        return redirect()->route('dashboard');
    }
}