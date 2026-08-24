<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateDeanAccountRequest;
use App\Models\User;
use App\Notifications\DeanAccountCreatedNotification;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DeanAccountController extends Controller
{
    public function index(): View
    {
        return view('admin.deans', [
            'deans' => $this->deans(),
        ]);
    }

    public function create(): View
    {
        return view('admin.deans.create');
    }

    public function store(CreateDeanAccountRequest $request): View
    {
        $password = Str::password(12);

        $dean = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => $password,
            'role' => 'dean',
            'department' => $request->validated('department'),
        ]);

        $dean->notify(new DeanAccountCreatedNotification);

        // Rendered directly rather than flashed through a redirect - see
        // StudentAccountController::store() for why a one-time credential
        // must never round-trip through a session flash or URL.
        return view('admin.deans', [
            'deans' => $this->deans(),
            'created' => [
                'name' => $dean->name,
                'email' => $dean->email,
                'password' => $password,
            ],
        ]);
    }

    private function deans()
    {
        return User::where('role', 'dean')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'department']);
    }
}
