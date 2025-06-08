<?php

namespace App\Services;

use App\Core\Request;
use App\Entity\User;
use App\Exceptions\ValidationException;
use App\Repository\UserRepository;
use App\Repository\WebQueryBuilder;
use App\Validators\Entities\UserValidator;

class UserService
{
    public function __construct(protected UserRepository $repository)
    {
    }
    public function list(Request $request)
    {
        $webQueryBuilder = new WebQueryBuilder(User::class);
        $webQueryBuilder->orderBy($request->input('orderBy', 'name'), $request->input('direction', 'asc'));
        $webQueryBuilder->page($request->input('page', 1));

        if ($request->input('username')) {
            $webQueryBuilder->where('username', 'LIKE', '%' . $request->input('username') . '%');
        }

        return $this->repository->webPaginate(
            $webQueryBuilder
        );
    }

    public function findById(int $id)
    {
        return $this->repository->findById($id);
    }

    public function create(User $user)
    {
        $validator = new UserValidator();

        if (!$validator->create($user)) {
            throw new ValidationException(implode(',', $validator->messages()));
        }

        try {
            $this->repository->save($user);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function promote(User $user)
    {
        try {
            $user->setIsAdmin(true);
            $this->repository->save($user);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function unpromote(User $user)
    {
        try {
            $user->setIsAdmin(false);
            $this->repository->save($user);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function active(User $user)
    {
        try {
            $user->active(true);
            $this->repository->save($user);
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function inactive(User $user)
    {
        try {
            $user->active(false);
            $this->repository->save($user);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function remove(User $user)
    {
        try {
            $this->repository->remove($user);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
