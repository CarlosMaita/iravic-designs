<?php

namespace App\Http\Controllers\admin\config;

use App\Http\Controllers\Controller;
use App\Models\HomeCta;
use App\Repositories\Eloquent\HomeCtaRepository;
use App\Http\Requests\HomeCtaRequest;

class HomeCtaController extends Controller
{
    protected $homeCtas;

    public function __construct(HomeCtaRepository $homeCtas)
    {
        $this->homeCtas = $homeCtas;
    }

    public function index()
    {
        $homeCtas = $this->homeCtas->all();
        return view('admin.home-ctas.index', compact('homeCtas'));
    }

    public function create()
    {
        return view('admin.home-ctas.create');
    }

    public function store(HomeCtaRequest $request)
    {
        $this->homeCtas->createByRequest($request);
        return redirect()->route('admin.home-ctas.index')->with('success', 'CTA creado correctamente.');
    }

    public function edit($id)
    {
        $homeCta = $this->homeCtas->find($id);
        
        if (!$homeCta) {
            return redirect()->route('admin.home-ctas.index')->with('error', 'CTA no encontrado.');
        }
        
        return view('admin.home-ctas.edit', compact('homeCta'));
    }

    public function update(HomeCtaRequest $request, $id)
    {
        $homeCta = $this->homeCtas->find($id);
        
        if (!$homeCta) {
            return redirect()->route('admin.home-ctas.index')->with('error', 'CTA no encontrado.');
        }
        
        $this->homeCtas->updateByRequest($request, $homeCta);
        return redirect()->route('admin.home-ctas.index')->with('success', 'CTA actualizado correctamente.');
    }

    public function destroy($id)
    {
        $homeCta = $this->homeCtas->find($id);
        
        if (!$homeCta) {
            return redirect()->route('admin.home-ctas.index')->with('error', 'CTA no encontrado.');
        }
        
        $this->homeCtas->delete($homeCta);
        return redirect()->route('admin.home-ctas.index')->with('success', 'CTA eliminado correctamente.');
    }
}
