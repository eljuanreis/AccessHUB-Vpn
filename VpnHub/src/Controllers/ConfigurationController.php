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
                'identifierTerm' => $request->input('identifierTerm'),
                'dateTerm' => $request->input('dateTerm'),
                'currentOrder' => $request->input('orderBy', 'createdAt'),
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

    public function download(Request $request)
    {
        $repository = new ConfigurationRepository();
        $config = $repository->findByIdentifier($request->input('i'));

        $service = new ConfigurationService($repository);
        $file = $service->download($config);

        if ($file && file_exists($file)) {
            $downloadName = time() . '.zip'; // defina o nome real que você quer no navegador

            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $downloadName . '"');
            header('Content-Length: ' . filesize($file));

            $fp = fopen($file, 'rb');
            if ($fp) {
                while (!feof($fp)) {
                    echo fread($fp, 8192);
                    ob_flush();
                    flush();
                }
                fclose($fp);
            }

            unlink($file); // apaga o temporário

            exit;
        }

    }


}
