<?php

namespace App\Services;

use App\Core\Request;
use App\Entity\Configuration;
use App\Repository\ConfigurationRepository;
use App\Repository\UserRepository;
use App\Repository\WebQueryBuilder;
use App\Utils\Auth;
use App\Utils\Env;
use App\Utils\Token;
use PSpell\Config;

class ConfigurationService
{
    public function __construct(protected ConfigurationRepository $repository)
    {
    }
    public function list(Request $request)
    {
        $webQueryBuilder = new WebQueryBuilder(Configuration::class);
        $webQueryBuilder->orderBy($request->input('orderBy', 'createdAt'), $request->input('direction', 'asc'));
        $webQueryBuilder->page($request->input('page', 1));

        if ($request->input('identifierTerm')) {
            $webQueryBuilder->where('identifier', 'LIKE', '%' . $request->input('identifierTerm') . '%');
        }

        if ($request->input('dateTerm')) {
            $webQueryBuilder->where('createdAt', 'LIKE', '%' . $request->input('dateTerm') . '%');
        }


        return $this->repository->webPaginate(
            $webQueryBuilder
        );
    }

    public function generate()
    {
        try {
            $dateImmutable = new \DateTimeImmutable();
            $period = new \DateInterval('P7D');
            $datePlus7DaysImmutable = $dateImmutable->add($period);
            $identifier = $this->identifier();
            $token = Token::encryptToken($identifier);

        // Cria o .zip no servidor
            $this->make($token);

            $configuration = new Configuration();
            $configuration->setIdentifier($identifier);

        // converte para DateTime mutável antes de setar:
            $dateMutable = \DateTime::createFromFormat('Y-m-d H:i:s', $dateImmutable->format('Y-m-d H:i:s'));
            $datePlus7DaysMutable = \DateTime::createFromFormat('Y-m-d H:i:s', $datePlus7DaysImmutable->format('Y-m-d H:i:s'));

            $configuration->setCreatedAt($dateMutable);
            $configuration->setValidUntil($datePlus7DaysMutable);
            $configuration->setDownloadLink($token);

            $user = new UserRepository();
            $user = $user->findById(Auth::getUser()->getId());
        // $user = $user->findById(1);

            $configuration->setUser($user);

            $this->repository->save($configuration);

            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    protected function identifier()
    {
        do {
            $id = substr(uniqid(), 0, 7);
        } while ($this->repository->findByIdentifier($id));

        return $id;
    }

    public function download(Configuration $config)
    {
        if ($config->getUser()->getId() == Auth::getUser()->getId()) {
            $token = $config->getDownloadLink();
            $file = $this->getZip($token);

            return $file;
        }

        return null;
    }

    protected function make($token)
    {

        $ch = curl_init(
            sprintf('%s?token=%s', Env::get('API_PACKER') . 'make', $token)
        );

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true); // Use POST method
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($token)
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $token);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Request error: ' . curl_error($ch);
            curl_close($ch);
            return false;
        }

        curl_close($ch);

        return $response;
    }

    protected function getZip(string $token)
    {
        $url = sprintf('%s?token=%s', Env::get('API_PACKER') . 'download', $token);

        $ch = curl_init($url);

        // Salvar o conteúdo em um arquivo temporário
        $tempFile = tempnam(sys_get_temp_dir(), 'zip_');

        $fp = fopen($tempFile, 'w+');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // seguir redirecionamentos
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // timeout opcional
        curl_setopt($ch, CURLOPT_FAILONERROR, true); // falhar se código HTTP for >= 400

        $success = curl_exec($ch);

        if (!$success || curl_errno($ch)) {
            echo 'Request error: ' . curl_error($ch);
            curl_close($ch);
            fclose($fp);
            return false;
        }

        curl_close($ch);
        fclose($fp);

        // Retorna o caminho do arquivo baixado
        return $tempFile;
    }

    public function findByIdentifier(string $identifier)
    {
        return $this->repository->findByIdentifier($identifier);
    }

    public function revoke(Configuration $configuration)
    {

        /**
         * TODO: Apagar cerificado
         */

        try {
            $this->repository->remove($configuration);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
