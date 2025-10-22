<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class ClientsController extends Controller
{
    public function index()
    {
        // If table doesn't exist yet, prevent error by falling back to empty collection
        try {
            $clients = class_exists(Client::class) ? Client::paginate(15) : collect();
        } catch (\Exception $e) {
            $clients = collect();
        }
        return view('admin.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('admin.clients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $data = $request->only(['name','email','phone','company','address','notes']);

        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/clients', $imageName);
            $data['logo'] = 'storage/clients/' . $imageName;
        }

        if (class_exists(Client::class)) {
            Client::create($data);
            return redirect()->route('clients.index')->with('success', 'Client created successfully.');
        }

        return redirect()->route('clients.index')->with('warning', 'Client model/table not ready yet.');
    }

    public function show($id)
    {
        $client = Client::findOrFail($id);
        return view('admin.clients.view', compact('client'));
    }

    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('admin.clients.edit', compact('client'));
    }

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $data = $request->only(['name','email','phone','company','address','notes']);

        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/clients', $imageName);
            $data['logo'] = 'storage/clients/' . $imageName;
        }

        $client->update($data);
        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }
}
