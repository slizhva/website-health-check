<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use App\Models\Links;
use App\Models\Commands;

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
            ->get(['id', 'link', 'success_content', 'status']);

        return view('dashboard', [
            'links' => $links
        ]);
    }

    public function add(Request $request):RedirectResponse
    {
        $link = new Links;
        $link->user = Auth::id();
        $link->link = $request->get('link');
        $link->status = Links::STATUS_PENDING;
        $link->success_content = $request->get('success_content');
        $link->save();

        $command = new Commands;
        $command->link = $link->id;
        $command->type = Commands::STATUS_ERROR;
        $command->command = [
            'url' => $request->get('request_error_url'),
            'type' => $request->get('request_error_type'),
            'header' => $request->get('request_error_header'),
        ];
        $command->save();

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
        Links
            ::where('id', $request->get('id'))
            ->update([
                'link' => $request->get('link'),
                'status' => Links::STATUS_PENDING,
                'command' => [

                ],
                'success_content' => $request->get('success_content'),
            ]);

        return redirect()->route('dashboard');
    }
}
