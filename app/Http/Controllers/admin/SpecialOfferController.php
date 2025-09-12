<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\SpecialOffer;
use App\Models\Product;
use App\Services\Images\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SpecialOfferController extends Controller
{
    private $filedisk = 'special-offers';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {        $specialOffers = SpecialOffer::with('product')
                                   ->ordered()
                                   ->paginate(15);
        
        return view('admin.special-offers.index', compact('specialOffers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {        $products = Product::orderBy('name')->get();
        
        return view('admin.special-offers.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'product_id' => 'required|exists:products,id',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'background_color' => ['nullable','string','max:20','regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/'],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer|min:0'
        ]);

        $data = $request->except(['image']);
        $data['is_active'] = $request->has('is_active');
        $data['order'] = $request->order ?? 0;

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $data['image'] = ImageService::save($this->filedisk, $file, $extension);
        }

        SpecialOffer::create($data);

        flash('Oferta especial creada correctamente.')->success();

        return redirect()->route('special-offers.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SpecialOffer $specialOffer)
    {        $products = Product::orderBy('name')->get();
        
        return view('admin.special-offers.edit', compact('specialOffer', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SpecialOffer $specialOffer)
    {        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'product_id' => 'required|exists:products,id',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'background_color' => ['nullable','string','max:20','regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/'],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer|min:0'
        ]);

        $data = $request->except(['image']);
        $data['is_active'] = $request->has('is_active');
        $data['order'] = $request->order ?? 0;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($specialOffer->image) {
                ImageService::delete($this->filedisk, $specialOffer->image);
            }
            
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $data['image'] = ImageService::save($this->filedisk, $file, $extension);
        }

        $specialOffer->update($data);

        flash('Oferta especial actualizada correctamente.')->success();

        return redirect()->route('special-offers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SpecialOffer $specialOffer)
    {        if ($specialOffer->image) {
            ImageService::delete($this->filedisk, $specialOffer->image);
        }
        
        $specialOffer->delete();

        flash('Oferta especial eliminada correctamente.')->success();

        return redirect()->route('special-offers.index');
    }
}
