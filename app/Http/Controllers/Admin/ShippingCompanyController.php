<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingCompany;
use Illuminate\Http\Request;

class ShippingCompanyController extends Controller
{
    public function index()
    {
        try {
            $shippingCompanies = ShippingCompany::all();
        } catch (\Throwable $e) {
            $shippingCompanies = collect();
        }
        return view('admin.shipping_companies.index', compact('shippingCompanies'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        ShippingCompany::create($request->only('name', 'tracking_url', 'is_active'));
        return redirect()->route('admin.shipping_companies.index')->with('success', 'Kargo şirketi eklendi.');
    }

    public function update(Request $request, $id)
    {
        $company = ShippingCompany::findOrFail($id);
        $company->update($request->only('name', 'tracking_url', 'is_active'));
        return redirect()->route('admin.shipping_companies.index')->with('success', 'Kargo şirketi güncellendi.');
    }

    public function destroy($id)
    {
        $company = ShippingCompany::findOrFail($id);
        $company->delete();
        return redirect()->route('admin.shipping_companies.index')->with('success', 'Kargo şirketi silindi.');
    }
}
