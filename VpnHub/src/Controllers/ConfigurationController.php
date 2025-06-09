<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Exceptions\ValidationException;
use App\Repository\ConfigurationRepository;
use App\Services\ConfigurationService;
use App\Services\LoginService;
use App\Utils\Env;
use App\Utils\Route;
use App\Utils\Session;
use App\Utils\SessionFlash;
use App\Validators\Web\LoginValidator;

class ConfigurationController
{
    public function show(Request $request)
    {
        $service = new ConfigurationService(new ConfigurationRepository());
        $configs = $service->list($request);

        return View::make(
            'tunnel_listing/vpn_configs',
            [
                'configs' => $configs,
                'searchTerm' => $request->input('q'),
                'currentOrder' => $request->input('orderBy', 'name'),
                'currentDirection' => $request->input('direction', 'asc')
            ]
        );
    }

    public function generate()
    {
        $service = new ConfigurationService(new ConfigurationRepository());
        
        try {
            if ($service->generate()) {
                return new Response(json_encode('Configuração gerada com sucesso!'));
            }
        } catch (\Throwable $th) {
                return new Response(json_encode($th->getMessage()), 500);
        }
    }

public function delete(Request $request)
{
    $identifiers = $request->input('identifiers');
    $service = new ConfigurationService(new ConfigurationRepository());

    foreach ($identifiers as $identifier) {
        $config = $service->findByIdentifier($identifier);

        try {
            $service->revoke($config);
        } catch (ValidationException $e) {
            Session::put(Session::FLASH, 'errors', [$e->getMessage()]);

            return new Response(json_encode(['error' => 'Erro ao excluir as configurações.']), 500);
        } catch (\Throwable $th) {
            if (Env::get('DEBUG')) {
                throw $th;
            }

            return new Response(json_encode(['error' => 'Erro inesperado ao excluir as configurações.']), 500);
        }
    }

    return new Response(json_encode(['success' => 'Configurações excluídas com sucesso.']), 200);
}

}
