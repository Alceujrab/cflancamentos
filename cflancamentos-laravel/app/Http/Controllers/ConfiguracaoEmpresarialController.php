<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracaoEmpresarial;
use Illuminate\Http\Request;

class ConfiguracaoEmpresarialController extends Controller
{
    public function edit()
    {
        return view('configuracoes.form', ['config' => ConfiguracaoEmpresarial::singleton()]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'razao_social' => ['nullable', 'string', 'max:255'],
            'cnpj' => ['nullable', 'string', 'max:25'],
            'email' => ['nullable', 'email', 'max:255'],
            'telefone' => ['nullable', 'string', 'max:30'],
            'website' => ['nullable', 'string', 'max:255'],
            'endereco' => ['nullable', 'string'],
        ]);

        ConfiguracaoEmpresarial::singleton()->update($data);
        return back()->with('ok', 'Configurações salvas.');
    }
}
