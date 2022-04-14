<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ModuleClassResource;
use App\Services\Contracts\ModuleClassServiceContract;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ModuleClassController extends Controller
{
    private ModuleClassServiceContract $moduleClassService;

    /**
     * @param ModuleClassServiceContract $moduleClassService
     */
    public function __construct (ModuleClassServiceContract $moduleClassService)
    {
        $this->moduleClassService = $moduleClassService;
    }

    public function readMany (Request $request) : AnonymousResourceCollection
    {
        $response = $this->moduleClassService->readMany($request->all());
        return ModuleClassResource::collection($response);
    }

    public function readManyByIdDepartment (Request $request,
                                            string  $idDepartment) : AnonymousResourceCollection
    {
        $response = $this->moduleClassService->readManyByIdDepartment($idDepartment,
                                                                      $request->all());
        return ModuleClassResource::collection($response);
    }

    public function updateMany (Request $request)
    {
        $this->moduleClassService->updateMany($request->all());
    }

    public function destroyMany (Request $request)
    {

    }
}
