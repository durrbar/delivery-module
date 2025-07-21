<?php

namespace Modules\Delivery\Http\Controllers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Modules\Core\Exceptions\DurrbarException;
use Modules\Core\Http\Controllers\CoreController;
use Modules\Delivery\Http\Requests\CreateShippingRequest;
use Modules\Delivery\Http\Requests\UpdateShippingRequest;
use Modules\Delivery\Repositories\ShippingRepository;
use Prettus\Validator\Exceptions\ValidatorException;

class ShippingController extends CoreController
{
    public $repository;

    public function __construct(ShippingRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Collection|Type[]
     */
    public function index(Request $request)
    {
        return $this->repository->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return LengthAwarePaginator|Collection|mixed
     *
     * @throws ValidatorException
     */
    public function store(CreateShippingRequest $request)
    {
        try {
            $validateData = $request->validated();

            return $this->repository->create($validateData);
        } catch (DurrbarException $th) {
            throw new DurrbarException(SOMETHING_WENT_WRONG);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        try {
            return $this->repository->findOrFail($id);
        } catch (DurrbarException $e) {
            throw new DurrbarException(NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CreateShippingRequest  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(UpdateShippingRequest $request, $id)
    {
        try {
            $validateData = $request->validated();

            return $this->repository->findOrFail($id)->update($validateData);
        } catch (DurrbarException $e) {
            throw new DurrbarException(NOT_FOUND);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            return $this->repository->findOrFail($id)->delete();
        } catch (DurrbarException $e) {
            throw new DurrbarException(NOT_FOUND);
        }
    }
}
