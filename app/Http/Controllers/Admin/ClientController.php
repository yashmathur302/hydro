<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveClientRequest;
use App\Models\Client;
use App\Services\Images\WebpImageProcessor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

final class ClientController extends Controller
{
    private const LOGO_DIRECTORY = 'clients';

    private const LOGO_MAX_WIDTH = 400;

    public function __construct(private readonly WebpImageProcessor $images) {}

    public function index(): View
    {
        return view('admin.clients.index', ['clients' => Client::query()->ordered()->get()]);
    }

    public function create(): View
    {
        return view('admin.clients.form', ['client' => new Client(['is_active' => true])]);
    }

    public function store(SaveClientRequest $request): RedirectResponse
    {
        $client = new Client($request->clientAttributes());
        $client->logo_path = $this->images->storeSingle($request->file('logo'), self::LOGO_DIRECTORY, self::LOGO_MAX_WIDTH);
        $client->save();

        return redirect()->route('admin.clients.index')->with('status', "Client \"{$client->name}\" added.");
    }

    public function edit(Client $client): View
    {
        return view('admin.clients.form', ['client' => $client]);
    }

    public function update(SaveClientRequest $request, Client $client): RedirectResponse
    {
        $previousLogo = $client->logo_path;
        $client->fill($request->clientAttributes());

        if ($logo = $request->file('logo')) {
            $client->logo_path = $this->images->storeSingle($logo, self::LOGO_DIRECTORY, self::LOGO_MAX_WIDTH);
        }

        $client->save();

        if ($previousLogo !== $client->logo_path) {
            Storage::disk('public')->delete($previousLogo);
        }

        return redirect()->route('admin.clients.index')->with('status', "Client \"{$client->name}\" saved.");
    }

    public function destroy(Client $client): RedirectResponse
    {
        $client->delete();
        Storage::disk('public')->delete($client->logo_path);

        return redirect()->route('admin.clients.index')->with('status', "Client \"{$client->name}\" deleted.");
    }
}
