<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use Illuminate\Http\Request;

class VeiculoController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $veiculos = Veiculo::query()
            ->when($q !== '', fn ($qb) => $qb->where(function ($w) use ($q) {
                $w->where('placa', 'like', "%$q%")->orWhere('modelo', 'like', "%$q%");
            }))
            ->orderBy('placa')
            ->paginate(15)
            ->withQueryString();

        return view('veiculos.index', compact('veiculos', 'q'));
    }

    public function create()
    {
        return view('veiculos.form', ['veiculo' => new Veiculo()]);
    }

    public function store(Request $request)
    {
        Veiculo::create($this->validated($request));
        return redirect()->route('veiculos.index')->with('ok', 'Veículo cadastrado.');
    }

    public function edit(Veiculo $veiculo)
    {
        return view('veiculos.form', compact('veiculo'));
    }

    public function update(Request $request, Veiculo $veiculo)
    {
        $veiculo->update($this->validated($request, $veiculo->id));
        return redirect()->route('veiculos.index')->with('ok', 'Veículo atualizado.');
    }

    public function destroy(Veiculo $veiculo)
    {
        $veiculo->delete();
        return back()->with('ok', 'Veículo removido.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $unique = 'unique:veiculos,placa' . ($ignoreId ? ",$ignoreId" : '');
        return $request->validate([
            'placa' => ['required', 'string', 'max:15', $unique],
            'modelo' => ['required', 'string', 'max:255'],
            'observacao' => ['nullable', 'string'],
            'ativo' => ['nullable', 'boolean'],
        ]) + ['ativo' => $request->boolean('ativo', true)];
    }
}
