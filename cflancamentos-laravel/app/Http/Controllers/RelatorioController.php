<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\ConfiguracaoEmpresarial;
use App\Models\LancamentoFinanceiro;
use App\Models\Veiculo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class RelatorioController extends Controller
{
    private array $tipos = [
        'geral'         => ['Fluxo de Caixa Geral',         'receipt_long'],
        'por_cliente'   => ['Receitas/Despesas por Cliente','groups'],
        'por_veiculo'   => ['Receitas/Despesas por Veículo','directions_car'],
        'consolidado'   => ['Consolidado Mensal',           'insert_chart'],
    ];

    public function index(Request $request)
    {
        $tipo = $request->get('tipo', 'geral');
        if (! isset($this->tipos[$tipo])) abort(404);

        $de  = $request->get('de',  now()->startOfMonth()->toDateString());
        $ate = $request->get('ate', now()->endOfMonth()->toDateString());
        $clienteId = $request->get('cliente_id');
        $veiculoId = $request->get('veiculo_id');

        $dados = $this->coletar($tipo, $de, $ate, $clienteId, $veiculoId);

        return view('relatorios.index', [
            'tipos'       => $this->tipos,
            'tipo'        => $tipo,
            'tituloTipo'  => $this->tipos[$tipo][0],
            'de'          => $de,
            'ate'         => $ate,
            'cliente_id'  => $clienteId,
            'veiculo_id'  => $veiculoId,
            'clientes'    => Cliente::orderBy('nome')->get(),
            'veiculos'    => Veiculo::orderBy('placa')->get(),
            'dados'       => $dados,
        ]);
    }

    public function pdf(Request $request)
    {
        $tipo = $request->get('tipo', 'geral');
        if (! isset($this->tipos[$tipo])) abort(404);

        $de  = $request->get('de',  now()->startOfMonth()->toDateString());
        $ate = $request->get('ate', now()->endOfMonth()->toDateString());
        $clienteId = $request->get('cliente_id');
        $veiculoId = $request->get('veiculo_id');

        $dados = $this->coletar($tipo, $de, $ate, $clienteId, $veiculoId);

        $pdf = Pdf::loadView('relatorios.pdf', [
            'tipo'       => $tipo,
            'tituloTipo' => $this->tipos[$tipo][0],
            'de'         => $de,
            'ate'        => $ate,
            'dados'      => $dados,
            'empresa'    => ConfiguracaoEmpresarial::singleton(),
            'cliente'    => $clienteId ? Cliente::find($clienteId) : null,
            'veiculo'    => $veiculoId ? Veiculo::find($veiculoId) : null,
        ])->setPaper('a4', 'portrait');

        $nome = 'relatorio_' . $tipo . '_' . $de . '_a_' . $ate . '.pdf';
        return $pdf->stream($nome);
    }

    private function coletar(string $tipo, string $de, string $ate, $clienteId, $veiculoId): array
    {
        $base = LancamentoFinanceiro::with(['cliente', 'veiculo'])
            ->whereDate('data', '>=', $de)
            ->whereDate('data', '<=', $ate)
            ->when($clienteId, fn ($q) => $q->where('cliente_id', $clienteId))
            ->when($veiculoId, fn ($q) => $q->where('veiculo_id', $veiculoId))
            ->orderBy('data');

        $lancamentos = (clone $base)->get();
        $receitas = (float) (clone $base)->where('tipo', 'receita')->sum('valor');
        $despesas = (float) (clone $base)->where('tipo', 'despesa')->sum('valor');

        $resumo = [
            'receitas' => $receitas,
            'despesas' => $despesas,
            'saldo'    => $receitas - $despesas,
            'total'    => $lancamentos->count(),
        ];

        $agrupado = [];
        if ($tipo === 'por_cliente') {
            $agrupado = $lancamentos->groupBy(fn ($l) => $l->cliente?->nome ?? '(Sem cliente)')
                ->map(fn ($g) => [
                    'receitas' => $g->where('tipo','receita')->sum('valor'),
                    'despesas' => $g->where('tipo','despesa')->sum('valor'),
                    'total'    => $g->count(),
                    'itens'    => $g,
                ])->sortKeys()->toArray();
        } elseif ($tipo === 'por_veiculo') {
            $agrupado = $lancamentos->groupBy(fn ($l) => $l->veiculo?->placa ?? '(Sem veículo)')
                ->map(fn ($g) => [
                    'receitas' => $g->where('tipo','receita')->sum('valor'),
                    'despesas' => $g->where('tipo','despesa')->sum('valor'),
                    'total'    => $g->count(),
                    'modelo'   => $g->first()->veiculo?->modelo,
                    'itens'    => $g,
                ])->sortKeys()->toArray();
        } elseif ($tipo === 'consolidado') {
            $agrupado = $lancamentos->groupBy(fn ($l) => $l->data->format('Y-m'))
                ->map(fn ($g, $k) => [
                    'mes'      => $k,
                    'receitas' => $g->where('tipo','receita')->sum('valor'),
                    'despesas' => $g->where('tipo','despesa')->sum('valor'),
                    'total'    => $g->count(),
                ])->sortKeys()->toArray();
        }

        return compact('lancamentos', 'resumo', 'agrupado');
    }
}
