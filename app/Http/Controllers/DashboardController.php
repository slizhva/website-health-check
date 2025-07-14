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
            ->get(['id', 'name', 'url', 'success_content', 'status']);

        return view('dashboard', [
            'links' => $links
        ]);
    }

    public function add(Request $request):RedirectResponse
    {
        $link = new Links;
        $link->user = Auth::id();
        $link->name = $request->get('name');
        $link->url = $request->get('url');
        $link->success_content = $request->get('success_content');
        $link->status = Links::STATUS_PENDING;
        $link->save();
        $link->error_command = [
            'url' => $request->get('request_error_url'),
            'type' => $request->get('request_error_type'),
            'header' => $request->get('request_error_header'),
        ];
        $link->save();

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

    public function update(Request $request):RedirectResponse
    {
        $link = Links::find($request->get('id'));
        $link->url = $request->get('url');
        $link->name = $request->get('name');
        $link->success_content = $request->get('success_content');
        $link->status = Links::STATUS_PENDING;
        $link->error_command = [
            'url' => $request->get('request_error_url'),
            'type' => $request->get('request_error_type'),
            'header' => $request->get('request_error_header'),
        ];
        $link->save();

        return redirect()->route('dashboard');
    }
}
