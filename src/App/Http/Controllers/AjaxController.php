<?php

namespace PROLANCEE\DYNAMIC\CRUD\Ajax\App\Http\Controllers;

use PROLANCEE\DYNAMIC\CRUD\Ajax\App\Http\Controllers\Base\BaseController;
use PROLANCEE\Support\App\Services\BaseService;
use Illuminate\Http\{Request, JsonResponse};

class AjaxController extends BaseController
{
    /**
     * Constructor to inject the BaseService dependency.
     *
     * @param BaseService $service
     */
    public function __construct(BaseService $service)
    {
        parent::__construct($service);
    }

    /**
     * Handle register request.
     *
     * @param Request $req
     * @return JsonResponse
     */
    public function registerControl(Request $req): JsonResponse
    {
        return $this->authControlBase($req, 'register');
    }

    /**
     * Handle login request.
     *
     * @param Request $req
     * @return JsonResponse
     */
    public function loginControl(Request $req): JsonResponse
    {
        return $this->authControlBase($req, 'login');
    }

    /**
     * Handle forgot password reset link request.
     *
     * @param Request $req
     * @return JsonResponse
     */
    public function passwordResetTokenControl(Request $req): JsonResponse
    {
        return $this->authControlBase($req, 'passwordResetToken');
    }

    /**
     * Handle change password request.
     *
     * @param Request $req
     * @return JsonResponse
     */
    public function changePasswordControl(Request $req): JsonResponse
    {
        return $this->authControlBase($req, 'changePassword');
    }

    /**
     * Handle logout request.
     *
     * @param Request $req
     * @return JsonResponse
     */
    public function logoutControl(Request $req): JsonResponse
    {
        return $this->authControlBase($req, 'logout');
    }

    /**
     * Store a single record in the database.
     *
     * @param Request $req
     * @return JsonResponse
     */
    public function storeSingleControl(Request $req): JsonResponse
    {
        return $this->singleControlBase($req, 'store');
    }

    /**
     * Store bulk records in the database.
     *
     * @param Request $req
     * @return JsonResponse
     */
    public function storeBulkControl(Request $req): JsonResponse
    {
        return $this->bulkControlBase($req, 'store');
    }

    /**
     * Fetch a single record from the database.
     *
     * @param Request $req
     * @return JsonResponse
     */
    public function fetchSingleControl(Request $req): JsonResponse
    {
        return $this->singleControlBase($req, 'fetch');
    }

    /**
     * Fetch builder records from the database.
     *
     * @param Request $req
     * @return JsonResponse
     */
    public function fetchBuilderControl(Request $req): JsonResponse
    {
        return $this->fetchBuilderControlBase($req, 'fetch');
    }

    /**
     * Update a single record in the database.
     *
     * @param Request $req
     * @return JsonResponse
     */
    public function updateSingleControl(Request $req): JsonResponse
    {
        return $this->singleControlBase($req, 'update');
    }

    /**
     * Update bulk records in the database.
     *
     * @param Request $req
     * @return JsonResponse
     */
    public function updateBulkControl(Request $req): JsonResponse
    {
        return $this->bulkControlBase($req, 'update');
    }

    /**
     * Delete a single record from the database.
     *
     * @param Request $req
     * @return JsonResponse
     */
    public function deleteSingleControl(Request $req): JsonResponse
    {
        return $this->singleControlBase($req, 'delete');
    }

    /**
     * Delete bulk records from the database.
     *
     * @param Request $req
     * @return JsonResponse
     */
    public function deleteBulkControl(Request $req): JsonResponse
    {
        return $this->bulkControlBase($req, 'delete');
    }

    /**
     * Handle file upload request.
     *
     * @param Request $req
     * @return JsonResponse
     */
    public function filesUploadControl(Request $req): JsonResponse
    {
        return $this->filesUploadControlBase($req, 'file-upload');
    }

    /**
     * Handle file upload delete request.
     *
     * @param Request $req
     * @return JsonResponse
     */
    public function filesDeleteControl(Request $req): JsonResponse
    {
        return $this->filesDeleteControlBase($req, 'file-delete');
    }

    /**
     * Handles encryption control request.
     *
     * @param Request $req
     * @return JsonResponse
     */
    public function encryptionControl(Request $req): JsonResponse
    {
        return $this->cryptoControlBase($req, 'encrypt');
    }

    /**
     * Handles decryption control request.
     *
     * @param Request $req
     * @return JsonResponse
     */
    public function decryptionControl(Request $req): JsonResponse
    {
        return $this->cryptoControlBase($req, 'decrypt');
    }
}