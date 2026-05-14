<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\LancamentoFinanceiro;
use App\Models\Veiculo;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $inicioMes = Carbon::now()->startOfMonth();
        $fimMes = Carbon::now()->endOfMonth();

        $receitasMes = (float) LancamentoFinanceiro::receitas()
            ->whereBetween('data', [$inicioMes, $fimMes])->sum('valor');
        $despesasMes = (float) LancamentoFinanceiro::despesas()
            ->whereBetween('data', [$inicioMes, $fimMes])->sum('valor');
        $saldoMes = $receitasMes - $despesasMes;

        $totalClientes = Cliente::count();
        $totalVeiculos = Veiculo::count();

        $ultimos = LancamentoFinanceiro::with(['cliente', 'veiculo'])
            ->orderByDesc('data')->limit(8)->get();

        // série dos últimos 6 meses
        $serie = collect(range(5, 0))->map(function ($i) {
            $ref = Carbon::now()->subMonths($i);
            $ini = $ref->copy()->startOfMonth();
            $fim = $ref->copy()->endOfMonth();
            return [
                'label' => $ref->translatedFormat('M/y'),
                'receita' => (float) LancamentoFinanceiro::receitas()->whereBetween('data', [$ini, $fim])->sum('valor'),
                'despesa' => (float) LancamentoFinanceiro::despesas()->whereBetween('data', [$ini, $fim])->sum('valor'),
            ];
        });

        return view('dashboard', compact(
            'receitasMes', 'despesasMes', 'saldoMes',
            'totalClientes', 'totalVeiculos', 'ultimos', 'serie'
        ));
    }
}
