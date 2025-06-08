<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Entity\User;
use App\Exceptions\ValidationException;
use App\Repository\UserRepository;
use App\Services\UserService;
use App\Utils\Env;
use App\Utils\Route;
use App\Utils\Session;

class AdminUserController
{
    public function show(Request $request)
    {
        $service = new UserService(new UserRepository());
        $users = $service->list($request);

        return View::make(
            'panel/admin_users',
            [
                'users' => $users,
                'searchTerm' => $request->input('username'),
                'currentOrder' => $request->input('orderBy', 'name'),
                'currentDirection' => $request->input('direction', 'asc')
            ]
        );
    }

    public function store(Request $request)
    {
        $user = new User();
        $user->setName($request->input('name'));
        $user->setEmail(
            sprintf('%s%s', $request->input('email_prefix'), 'juan.com.br')
        );
        $user->setUsername($request->input('username'));
        $user->setPassword(password_hash($request->input('password'), PASSWORD_BCRYPT));
        $user->setIsAdmin($request->input('is_admin', false));

        $service = new UserService(new UserRepository());

        try {
            $service->create($user);
        } catch (ValidationException $e) {
            Session::put(Session::FLASH, 'errors', [$e->getMessage()]);

            return Route::redirect('GET', '/administrar-usuarios');
        } catch (\Throwable $th) {
            if (Env::get('DEBUG')) {
                throw $th;
            }
        }

        Session::put(Session::FLASH, 'success', [sprintf('Você criou o funcionário %s', $user->getUsername())]);

        return Route::redirect('GET', '/administrar-usuarios');
    }

    public function promote(Request $request)
    {
        $userIds = $request->input('userIds');
        $service = new UserService(new UserRepository());

        foreach ($userIds as $id) {
            $user = $service->findById($id);

            try {
                $service->promote($user);
            } catch (ValidationException $e) {
                Session::put(Session::FLASH, 'errors', [$e->getMessage()]);

                return new Response(json_encode('Erro ao promover os usuários', 500));
            } catch (\Throwable $th) {
                if (Env::get('DEBUG')) {
                    throw $th;
                }

                return new Response(json_encode('Erro ao promover os usuários', 500));
            }
        }

          return new Response(json_encode('Promovido com sucesso!'));
    }

    public function unpromote(Request $request)
    {
        $userIds = $request->input('userIds');
        $service = new UserService(new UserRepository());

        foreach ($userIds as $id) {
            $user = $service->findById($id);

            try {
                $service->unpromote($user);
            } catch (ValidationException $e) {
                Session::put(Session::FLASH, 'errors', [$e->getMessage()]);

                return new Response(json_encode('Erro ao despromover os usuários', 500));
            } catch (\Throwable $th) {
                if (Env::get('DEBUG')) {
                    throw $th;
                }

                return new Response(json_encode('Erro ao despromover os usuários', 500));
            }
        }

          return new Response(json_encode('Despromovido com sucesso!'));
    }

    public function active(Request $request)
    {
        $userIds = $request->input('userIds');
        $service = new UserService(new UserRepository());

        foreach ($userIds as $id) {
            $user = $service->findById($id);

            try {
                $service->active($user);
            } catch (ValidationException $e) {
                Session::put(Session::FLASH, 'errors', [$e->getMessage()]);

                return new Response(json_encode('Erro ao ativar os usuários', 500));
            } catch (\Throwable $th) {
                if (Env::get('DEBUG')) {
                    throw $th;
                }

                return new Response(json_encode('Erro ao ativar os usuários', 500));
            }
        }

          return new Response(json_encode('Ativado com sucesso!'));
    }

    public function inactive(Request $request)
    {
        $userIds = $request->input('userIds');
        $service = new UserService(new UserRepository());

        foreach ($userIds as $id) {
            $user = $service->findById($id);

            try {
                $service->inactive($user);
            } catch (ValidationException $e) {
                Session::put(Session::FLASH, 'errors', [$e->getMessage()]);

                return new Response(json_encode('Erro ao desativar os usuários', 500));
            } catch (\Throwable $th) {
                if (Env::get('DEBUG')) {
                    throw $th;
                }

                return new Response(json_encode('Erro ao desativar os usuários', 500));
            }
        }

          return new Response(json_encode('Desativado com sucesso!'));
    }

    public function remove(Request $request)
    {
        $userIds = $request->input('userIds');
        $service = new UserService(new UserRepository());

        foreach ($userIds as $id) {
            $user = $service->findById($id);

            try {
                $service->remove($user);
            } catch (ValidationException $e) {
                Session::put(Session::FLASH, 'errors', [$e->getMessage()]);

                return new Response(json_encode('Erro ao excluir os usuários', 500));
            } catch (\Throwable $th) {
                if (Env::get('DEBUG')) {
                    throw $th;
                }

                return new Response(json_encode('Erro ao excluir os usuários', 500));
            }
        }

          return new Response(json_encode('Excluído com sucesso!'));
    }
}
