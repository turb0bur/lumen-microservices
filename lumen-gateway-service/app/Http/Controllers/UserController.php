<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\AppResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use \Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use AppResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return Response
     */
    public function index(): JsonResponse
    {
        $users = User::all();

        return $this->validResponse($users);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $rules = [
            'name'     => 'required|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ];
        $this->validate($request, $rules);

        $request['password'] = Hash::make($request['password']);

        $user = $this->user->create($request->all());

        return $this->successResponse($user, Response::HTTP_CREATED);
    }

    /**
     * @param int $user
     * @return Response
     */
    public function show(int $user): Response
    {
        $user = User::findOrFail($user);

        return $this->successResponse($user);
    }

    /**
     * @param Request $request
     * @param int     $user
     * @return Response
     */
    public function update(Request $request, int $user): Response
    {
        $rules = [
            'name'     => 'required|max:255',
            'email'    => 'required|email|max:255|unique:users,email,' . $user,
            'password' => 'required|min:8|confirmed',
        ];
        $this->validate($request, $rules);

        $user = User::findOrFail($user);
        $user->fill($request->all());

        if ($request->has('password')) {
            $request['password'] = Hash::make($request['password']);
        }

        if ($user->isClean())
            return $this->errorResponse('At least one attribute must be provided', Response::HTTP_UNPROCESSABLE_ENTITY);
        $user->save();

        return $this->successResponse($user);
    }

    /**
     * @param int $user
     * @return Response
     */
    public function destroy(int $user): Response
    {
        $user = User::findOrFail($user);
        $user->delete();

        return $this->successResponse($user);
    }

    public function me(Request $request)
    {
        return $this->validResponse($request->user());
    }
}
