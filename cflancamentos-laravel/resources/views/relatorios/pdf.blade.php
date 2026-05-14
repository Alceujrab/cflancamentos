<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<title>{{ $tituloTipo }}</title>
<style>
  @page { margin: 28mm 14mm 18mm 14mm; }
  * { box-sizing: border-box; }
  body { font-family: DejaVu Sans, sans-serif; color: #0f172a; font-size: 10px; margin: 0; }
  .header {
    position: fixed; top: -20mm; left: 0; right: 0; height: 22mm;
    background: linear-gradient(135deg, #0f1f5c 0%, #1e3dd6 60%, #3b6cf6 100%);
    color: #fff; padding: 6mm 10mm; border-bottom: 3px solid #f97316;
  }
  .header h1 { margin: 0; font-size: 14px; font-weight: bold; letter-spacing: 0.5px; }
  .header .empresa { font-size: 9px; opacity: .85; margin-top: 2px; }
  .header .logo {
    position: absolute; right: 10mm; top: 5mm; width: 14mm; height: 14mm;
    background: #fff; border-radius: 4mm; text-align: center; line-height: 14mm;
    color: #1e3dd6; font-weight: bold; font-size: 14px;
  }
  .footer {
    position: fixed; bottom: -12mm; left: 0; right: 0; height: 10mm;
    border-top: 1px solid #e2e8f0; padding: 3mm 10mm; font-size: 8px; color: #64748b;
  }
  .footer .right { float: right; }
  .meta { background: #f6f8fc; border: 1px solid #e2e8f0; border-radius: 3mm;
          padding: 4mm 5mm; margin-bottom: 5mm; }
  .meta-row { margin: 1.5mm 0; font-size: 9px; }
  .meta-row strong { color: #1e3dd6; display: inline-block; min-width: 22mm; }
  .title { font-size: 13px; font-weight: bold; color: #0f1f5c; margin: 4mm 0 2mm; border-bottom: 2px solid #f97316; padding-bottom: 1.5mm; }
  .kpis { width: 100%; margin: 4mm 0 6mm; }
  .kpis td { padding: 3mm; background: #fff; border: 1px solid #e2e8f0; border-radius: 2mm; width: 25%; }
  .kpi-label { font-size: 8px; color: #64748b; text-transform: uppercase; letter-spacing: .8px; font-weight: bold; }
  .kpi-value { font-size: 12px; font-weight: bold; margin-top: 1.5mm; }
  .kpi-r { color: #059669; }
  .kpi-d { color: #ea580c; }
  .kpi-s { color: #1e3dd6; }
  .kpi-neg { color: #ea580c; }
  table.data { width: 100%; border-collapse: collapse; margin-top: 2mm; }
  table.data th { background: #1e3dd6; color: #fff; padding: 2.5mm 3mm; text-align: left;
                  font-size: 9px; font-weight: bold; text-transform: uppercase; letter-spacing: .4px; }
  table.data td { padding: 2.2mm 3mm; border-bottom: 1px solid #e2e8f0; font-size: 9px; }
  table.data tr:nth-child(even) td { background: #f8fafc; }
  .tipo-r { color: #059669; font-weight: bold; }
  .tipo-d { color: #ea580c; font-weight: bold; }
  .right { text-align: right; }
  .group-header { background: #eef4ff; color: #1e3dd6; font-weight: bold;
                  padding: 3mm; margin-top: 4mm; border-left: 3px solid #f97316; font-size: 10px; }
  .group-totals { float: right; font-size: 8px; color: #64748b; font-weight: normal; }
  .group-totals .r { color: #059669; font-weight: bold; }
  .group-totals .d { color: #ea580c; font-weight: bold; }
  .totals-row td { background: #0f1f5c !important; color: #fff !important; font-weight: bold; padding: 3mm !important; font-size: 10px; }
</style>
</head>
<body>

<div class="header">
  <div class="logo">CF</div>
  <h1>{{ $empresa->razao_social ?: 'CFLançamentos' }}</h1>
  <div class="empresa">
    @if($empresa->cnpj) CNPJ: {{ $empresa->cnpj }} @endif
    @if($empresa->telefone) · Tel: {{ $empresa->telefone }} @endif
    @if($empresa->email) · {{ $empresa->email }} @endif
  </div>
</div>

<div class="footer">
  Emitido em {{ now()->format('d/m/Y H:i') }}
  <span class="right">Página <script type="text/php">if (isset($pdf)) { $pdf->page_text(540, 800, "{PAGE_NUM}/{PAGE_COUNT}", null, 8, [0.39, 0.45, 0.55]); }</script></span>
</div>

<div class="title">{{ $tituloTipo }}</div>

<div class="meta">
  <div class="meta-row"><strong>Período:</strong> {{ \Carbon\Carbon::parse($de)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($ate)->format('d/m/Y') }}</div>
  @if($cliente)<div class="meta-row"><strong>Cliente:</strong> {{ $cliente->nome }}</div>@endif
  @if($veiculo)<div class="meta-row"><strong>Veículo:</strong> {{ $veiculo->placa }} · {{ $veiculo->modelo }}</div>@endif
  <div class="meta-row"><strong>Emissão:</strong> {{ now()->format('d/m/Y H:i') }}</div>
</div>

@php
  $fmt = fn($v) => 'R$ ' . number_format((float)$v, 2, ',', '.');
  $resumo = $dados['resumo'];
@endphp

<table class="kpis" cellspacing="3">
  <tr>
    <td><div class="kpi-label">Receitas</div><div class="kpi-value kpi-r">{{ $fmt($resumo['receitas']) }}</div></td>
    <td><div class="kpi-label">Despesas</div><div class="kpi-value kpi-d">{{ $fmt($resumo['despesas']) }}</div></td>
    <td><div class="kpi-label">Saldo</div>
        <div class="kpi-value {{ $resumo['saldo'] >= 0 ? 'kpi-s' : 'kpi-neg' }}">{{ $fmt($resumo['saldo']) }}</div></td>
    <td><div class="kpi-label">Lançamentos</div><div class="kpi-value">{{ $resumo['total'] }}</div></td>
  </tr>
</table>

@if($tipo === 'geral')
  <table class="data">
    <thead><tr>
      <th style="width:18mm">Data</th><th style="width:18mm">Tipo</th>
      <th>Descrição</th><th>Cliente</th><th style="width:22mm">Veículo</th>
      <th class="right" style="width:25mm">Valor</th>
    </tr></thead>
    <tbody>
      @forelse($dados['lancamentos'] as $l)
        <tr>
          <td>{{ $l->data->format('d/m/Y') }}</td>
          <td class="{{ $l->tipo === 'receita' ? 'tipo-r' : 'tipo-d' }}">{{ ucfirst($l->tipo) }}</td>
          <td>{{ $l->descricao }}</td>
          <td>{{ $l->cliente?->nome ?? '—' }}</td>
          <td>{{ $l->veiculo?->placa ?? '—' }}</td>
          <td class="right {{ $l->tipo === 'receita' ? 'tipo-r' : 'tipo-d' }}">
            {{ $l->tipo === 'receita' ? '+' : '−' }}{{ $fmt($l->valor) }}
          </td>
        </tr>
      @empty
        <tr><td colspan="6" style="text-align:center; padding: 10mm; color:#64748b;">Sem lançamentos.</td></tr>
      @endforelse
      <tr class="totals-row">
        <td colspan="5">TOTAL DO PERÍODO</td>
        <td class="right">{{ $fmt($resumo['saldo']) }}</td>
      </tr>
    </tbody>
  </table>
@elseif($tipo === 'consolidado')
  <table class="data">
    <thead><tr>
      <th>Mês</th><th class="right">Receitas</th><th class="right">Despesas</th>
      <th class="right">Saldo</th><th class="right">Qtd.</th>
    </tr></thead>
    <tbody>
      @forelse($dados['agrupado'] as $row)
        @php $saldo = $row['receitas'] - $row['despesas']; @endphp
        <tr>
          <td><strong>{{ \Carbon\Carbon::parse($row['mes'].'-01')->translatedFormat('F / Y') }}</strong></td>
          <td class="right tipo-r">{{ $fmt($row['receitas']) }}</td>
          <td class="right tipo-d">{{ $fmt($row['despesas']) }}</td>
          <td class="right" style="color: {{ $saldo >= 0 ? '#1e3dd6' : '#ea580c' }}; font-weight:bold;">{{ $fmt($saldo) }}</td>
          <td class="right">{{ $row['total'] }}</td>
        </tr>
      @empty
        <tr><td colspan="5" style="text-align:center; padding: 10mm; color:#64748b;">Sem dados.</td></tr>
      @endforelse
      <tr class="totals-row">
        <td>CONSOLIDADO</td>
        <td class="right">{{ $fmt($resumo['receitas']) }}</td>
        <td class="right">{{ $fmt($resumo['despesas']) }}</td>
        <td class="right">{{ $fmt($resumo['saldo']) }}</td>
        <td class="right">{{ $resumo['total'] }}</td>
      </tr>
    </tbody>
  </table>
@else
  @forelse($dados['agrupado'] as $chave => $row)
    @php $saldo = $row['receitas'] - $row['despesas']; @endphp
    <div class="group-header">
      {{ $chave }} @if(isset($row['modelo'])) <span style="font-weight:normal; font-size:8px; color:#64748b;">· {{ $row['modelo'] }}</span>@endif
      <span class="group-totals">
        <span class="r">+{{ $fmt($row['receitas']) }}</span> ·
        <span class="d">−{{ $fmt($row['despesas']) }}</span> ·
        <strong style="color: {{ $saldo >= 0 ? '#1e3dd6' : '#ea580c' }};">= {{ $fmt($saldo) }}</strong>
      </span>
    </div>
    <table class="data">
      <thead><tr>
        <th style="width:20mm">Data</th><th style="width:18mm">Tipo</th>
        <th>Descrição</th><th class="right" style="width:25mm">Valor</th>
      </tr></thead>
      <tbody>
        @foreach($row['itens'] as $l)
          <tr>
            <td>{{ $l->data->format('d/m/Y') }}</td>
            <td class="{{ $l->tipo === 'receita' ? 'tipo-r' : 'tipo-d' }}">{{ ucfirst($l->tipo) }}</td>
            <td>{{ $l->descricao }}</td>
            <td class="right {{ $l->tipo === 'receita' ? 'tipo-r' : 'tipo-d' }}">
              {{ $l->tipo === 'receita' ? '+' : '−' }}{{ $fmt($l->valor) }}
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @empty
    <div style="text-align:center; padding: 10mm; color:#64748b;">Sem dados.</div>
  @endforelse
@endif

</body>
</html>
