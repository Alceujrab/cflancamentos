<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\LancamentoAnexo;
use App\Models\LancamentoFinanceiro;
use App\Models\Veiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LancamentoFinanceiroController extends Controller
{
    public function index(Request $request)
    {
        $tipo = $request->get('tipo');
        $de = $request->get('de');
        $ate = $request->get('ate');

        $lancamentos = LancamentoFinanceiro::query()
            ->with(['cliente', 'veiculo'])
            ->withCount('anexos')
            ->when($tipo, fn ($q) => $q->where('tipo', $tipo))
            ->when($de, fn ($q) => $q->whereDate('data', '>=', $de))
            ->when($ate, fn ($q) => $q->whereDate('data', '<=', $ate))
            ->orderByDesc('data')
            ->paginate(20)
            ->withQueryString();

        return view('lancamentos.index', compact('lancamentos', 'tipo', 'de', 'ate'));
    }

    public function create()
    {
        return view('lancamentos.form', [
            'lancamento' => new LancamentoFinanceiro(['data' => now()->toDateString(), 'tipo' => 'receita']),
            'clientes' => Cliente::orderBy('nome')->get(),
            'veiculos' => Veiculo::orderBy('placa')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $lancamento = LancamentoFinanceiro::create($this->validated($request));
        $this->salvarAnexos($request, $lancamento);
        return redirect()->route('lancamentos.index')->with('ok', 'Lançamento registrado.');
    }

    public function edit(LancamentoFinanceiro $lancamento)
    {
        $lancamento->load('anexos');
        return view('lancamentos.form', [
            'lancamento' => $lancamento,
            'clientes' => Cliente::orderBy('nome')->get(),
            'veiculos' => Veiculo::orderBy('placa')->get(),
        ]);
    }

    public function update(Request $request, LancamentoFinanceiro $lancamento)
    {
        $lancamento->update($this->validated($request));
        $this->salvarAnexos($request, $lancamento);
        return redirect()->route('lancamentos.index')->with('ok', 'Lançamento atualizado.');
    }

    public function destroy(LancamentoFinanceiro $lancamento)
    {
        foreach ($lancamento->anexos as $anexo) {
            Storage::disk('public')->delete($anexo->path);
        }
        $lancamento->delete();
        return back()->with('ok', 'Lançamento removido.');
    }

    public function removerAnexo(LancamentoAnexo $anexo)
    {
        Storage::disk('public')->delete($anexo->path);
        $anexo->delete();
        return back()->with('ok', 'Anexo removido.');
    }

    private function salvarAnexos(Request $request, LancamentoFinanceiro $lancamento): void
    {
        $request->validate([
            'anexos.*' => ['file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
        ]);

        foreach ((array) $request->file('anexos', []) as $file) {
            if (! $file) continue;
            $path = $file->store('anexos/' . $lancamento->id, 'public');
            $lancamento->anexos()->create([
                'nome_original' => $file->getClientOriginalName(),
                'path' => $path,
                'mime' => $file->getMimeType(),
                'tamanho' => $file->getSize(),
            ]);
        }
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'tipo' => ['required', 'in:receita,despesa'],
            'cliente_id' => ['nullable', 'exists:clientes,id'],
            'veiculo_id' => ['nullable', 'exists:veiculos,id'],
            'data' => ['required', 'date'],
            'valor' => ['required'],
            'descricao' => ['required', 'string', 'max:255'],
            'observacao' => ['nullable', 'string'],
        ]);

        // Aceita "1.234,56" ou "1234.56"
        $valor = $data['valor'];
        if (is_string($valor)) {
            $valor = str_replace(['.', ' '], ['', ''], $valor);
            $valor = str_replace(',', '.', $valor);
        }
        $data['valor'] = (float) $valor;

        return $data;
    }
}
