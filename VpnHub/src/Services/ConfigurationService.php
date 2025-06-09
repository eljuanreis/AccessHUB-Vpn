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

        if ($request->input('q')) {
            $webQueryBuilder->where('identifier', 'LIKE', '%' . $request->input('q') . '%');
        }

        if ($request->input('q')) {
            // $webQueryBuilder->where('createdAt', 'LIKE', '%' . $request->input('q') . '%');
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
        $token = Token::encryptToken(json_encode([
                    'identifier' => $identifier,
                    // 'user_id' => Auth::getUser()->getId(),
                    'valid_until' => $datePlus7DaysImmutable
        ]));

        $data = ['token' => $token];
                
        $zip = $this->integration($data);

        $configuration = new Configuration();
        $configuration->setIdentifier($identifier);

        // converte para DateTime mutável antes de setar:
        $dateMutable = \DateTime::createFromFormat('Y-m-d H:i:s', $dateImmutable->format('Y-m-d H:i:s'));
        $datePlus7DaysMutable = \DateTime::createFromFormat('Y-m-d H:i:s', $datePlus7DaysImmutable->format('Y-m-d H:i:s'));

        $configuration->setCreatedAt($dateMutable);
        $configuration->setValidUntil($datePlus7DaysMutable);
        $configuration->setDownloadLink(sprintf('%sdownload?token=%s', Env::get('API_PACKER'), $token));

        $user = new UserRepository();
        // $user = $user->findById(Auth::getUser()->getId());
        $user = $user->findById(1);

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

    protected function integration($data)
    {
        $jsonData = json_encode($data);

        $ch = curl_init(
            sprintf('%s?token=%s', Env::get('API_PACKER') . 'make', $data['token']
        ));

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true); // Use POST method
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Request error: ' . curl_error($ch);
            curl_close($ch);
            return false;
        }

        curl_close($ch);

        return $response;
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