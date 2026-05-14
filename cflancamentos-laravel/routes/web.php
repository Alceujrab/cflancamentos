<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ConfiguracaoEmpresarialController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LancamentoFinanceiroController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\VeiculoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('clientes', ClienteController::class)->except(['show']);
Route::resource('veiculos', VeiculoController::class)->except(['show'])->parameters(['veiculos' => 'veiculo']);
Route::resource('lancamentos', LancamentoFinanceiroController::class)
    ->parameters(['lancamentos' => 'lancamento'])
    ->except(['show']);
Route::delete('anexos/{anexo}', [LancamentoFinanceiroController::class, 'removerAnexo'])
    ->name('anexos.destroy');

Route::get('relatorios', [RelatorioController::class, 'index'])->name('relatorios.index');
Route::get('relatorios/pdf', [RelatorioController::class, 'pdf'])->name('relatorios.pdf');

Route::get('configuracoes', [ConfiguracaoEmpresarialController::class, 'edit'])->name('configuracoes.edit');
Route::put('configuracoes', [ConfiguracaoEmpresarialController::class, 'update'])->name('configuracoes.update');
