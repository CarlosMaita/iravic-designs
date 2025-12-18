<?php

namespace App\Http\Controllers\admin\config;

use App\Http\Controllers\Controller;
use App\Models\SubmenuLink;
use App\Repositories\Eloquent\SubmenuLinkRepository;
use App\Http\Requests\SubmenuLinkRequest;

class SubmenuLinkController extends Controller
{
    protected $submenuLinks;

    public function __construct(SubmenuLinkRepository $submenuLinks)
    {
        $this->submenuLinks = $submenuLinks;
    }

    public function index()
    {
        $submenuLinks = $this->submenuLinks->all();
        return view('dashboard.config.submenu-links.index', compact('submenuLinks'));
    }

    public function create()
    {
        return view('dashboard.config.submenu-links.create');
    }

    public function store(SubmenuLinkRequest $request)
    {
        $this->submenuLinks->createByRequest($request);
        flash('Enlace de submenú creado correctamente.')->success();
        return redirect()->route('admin.submenu-links.index');
    }

    public function edit($id)
    {
        $submenuLink = $this->submenuLinks->find($id);
        return view('dashboard.config.submenu-links.edit', compact('submenuLink'));
    }

    public function update(SubmenuLinkRequest $request, $id)
    {
        $submenuLink = $this->submenuLinks->find($id);
        $this->submenuLinks->updateByRequest($request, $submenuLink);
        flash('Enlace de submenú actualizado correctamente.')->success();
        return redirect()->route('admin.submenu-links.index');
    }

    public function destroy($id)
    {
        $submenuLink = $this->submenuLinks->find($id);
        $this->submenuLinks->delete($submenuLink);
        flash('Enlace de submenú eliminado correctamente.')->success();
        return redirect()->route('admin.submenu-links.index');
    }
}
