<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Repositories\Eloquent\BannerRepository;
use App\Http\Requests\BannerRequest;

class BannerController extends Controller
{
    protected $banners;

    public function __construct(BannerRepository $banners)
    {
        $this->banners = $banners;
    }

    public function index()
    {
        $banners = $this->banners->all();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(BannerRequest $request)
    {
        $this->banners->createByRequest($request);
        return redirect()->route('banners.index')->with('success', 'Banner creado correctamente.');
    }

    public function edit($id)
    {
        $banner = $this->banners->find($id);
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(BannerRequest $request, $id)
    {
        $banner = $this->banners->find($id);
        $this->banners->updateByRequest( $request, $banner);
        return redirect()->route('banners.index')->with('success', 'Banner actualizado correctamente.');
    }

    public function destroy($id)
    {
        $banner = $this->banners->find($id);
        $this->banners->delete($banner);
        return redirect()->route('banners.index')->with('success', 'Banner eliminado correctamente.');
    }
}
