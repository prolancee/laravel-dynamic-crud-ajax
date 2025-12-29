<?php

namespace PROLANCEE\DYNAMIC\CRUD\Ajax\Classes\Routes;

class ConfigRouter
{
    /*
    |----------------------------------------------------------------------------------------
    | CRUD Route Configuration
    |----------------------------------------------------------------------------------------
    |
    | This class dynamically generates REST-standard CRUD Blade routes for any given controller
    | and resource prefix. Each route maps to a specific controller method.
    |
    | Available Endpoints:
    |
    */
    public static function get(string $controller, string $prefixBase): array
    {
        return [
            // -------------------- AUTH --------------------
            "{$prefixBase}/auth/register" => ['method' => 'post', 'action' => [$controller, 'registerControl']],
            "{$prefixBase}/auth/login"  => ['method' => 'post', 'action' => [$controller, 'loginControl']],
            "{$prefixBase}/auth/social" => ['method' => 'post', 'action' => [$controller, 'socialControl']],
            "{$prefixBase}/auth/logout" => ['method' => 'post', 'action' => [$controller, 'logoutControl']],
            "{$prefixBase}/auth/password/reset-token" => ['method' => 'post', 'action' => [$controller, 'passwordResetTokenControl']],
            "{$prefixBase}/auth/change/password" => ['method' => 'post', 'action' => [$controller, 'changePasswordControl']],

            // -------------------- STORE -------------------------
            "{$prefixBase}/store/single" => ['method' => 'post', 'action' => [$controller, 'storeSingleControl']],
            "{$prefixBase}/store/bulk"   => ['method' => 'post', 'action' => [$controller, 'storeBulkControl']],

            // -------------------- FETCH -------------------------
            "{$prefixBase}/fetch/single"  => ['method' => 'get', 'action' => [$controller, 'fetchSingleControl']],
            "{$prefixBase}/fetch/builder" => ['method' => 'get', 'action' => [$controller, 'fetchBuilderControl']],

            // -------------------- UPDATE ------------------------
            "{$prefixBase}/update/single" => ['method' => 'put', 'action' => [$controller, 'updateSingleControl']],
            "{$prefixBase}/update/bulk"   => ['method' => 'put', 'action' => [$controller, 'updateBulkControl']],

            // -------------------- DELETE ------------------------
            "{$prefixBase}/delete/single" => ['method' => 'delete', 'action' => [$controller, 'deleteSingleControl']],
            "{$prefixBase}/delete/bulk"   => ['method' => 'delete', 'action' => [$controller, 'deleteBulkControl']],

            // -------------------- FILE --------------------------
            "{$prefixBase}/files/upload" => ['method' => 'post', 'action'   => [$controller, 'filesUploadControl']],
            "{$prefixBase}/files/delete" => ['method' => 'delete', 'action' => [$controller, 'filesDeleteControl']],

            // Encryption / Decryption
            "$prefixBase/encryption" => ['method' => 'post', 'action' => [$controller, 'encryptionControl']],
            "$prefixBase/decryption" => ['method' => 'post', 'action' => [$controller, 'decryptionControl']],
        ];
    }
}
