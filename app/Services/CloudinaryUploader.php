<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class CloudinaryUploader
{
    /**
     * Sube un archivo a Cloudinary usando subida firmada (signed upload) y
     * devuelve la URL segura (https) del recurso ya alojado en Cloudinary.
     *
     * Al guardar esa URL directamente en la base de datos (en vez de una ruta
     * local), la imagen o video deja de depender del disco del contenedor y
     * sobrevive a cualquier redeploy o reinicio del servicio en Render.
     *
     * @param  UploadedFile  $file
     * @param  string  $resourceType  'image', 'video' o 'auto'
     * @param  string  $folder        Carpeta dentro de Cloudinary para organizar los archivos
     * @return string  URL https del recurso subido (secure_url)
     *
     * @throws RuntimeException si falta configuración o la subida falla
     */
    public static function upload(UploadedFile $file, string $resourceType = 'auto', string $folder = 'etnobotanica'): string
    {
        $cloudName = config('services.cloudinary.cloud_name');
        $apiKey    = config('services.cloudinary.api_key');
        $apiSecret = config('services.cloudinary.api_secret');

        if (!$cloudName || !$apiKey || !$apiSecret) {
            throw new RuntimeException(
                'Cloudinary no está configurado. Revisa CLOUDINARY_CLOUD_NAME, ' .
                'CLOUDINARY_API_KEY y CLOUDINARY_API_SECRET en el archivo .env.'
            );
        }

        $timestamp = time();

        // Los parámetros a firmar van ordenados alfabéticamente por clave y
        // unidos como key=value&key=value (así lo exige Cloudinary).
        $paramsToSign = [
            'folder'    => $folder,
            'timestamp' => $timestamp,
        ];
        ksort($paramsToSign);

        $signatureBase = collect($paramsToSign)
            ->map(fn ($value, $key) => "{$key}={$value}")
            ->implode('&');

        $signature = sha1($signatureBase . $apiSecret);

        $response = Http::attach(
                'file',
                fopen($file->getRealPath(), 'r'),
                $file->getClientOriginalName()
            )
            ->post("https://api.cloudinary.com/v1_1/{$cloudName}/{$resourceType}/upload", [
                'api_key'   => $apiKey,
                'timestamp' => $timestamp,
                'folder'    => $folder,
                'signature' => $signature,
            ]);

        if ($response->failed()) {
            throw new RuntimeException('Error al subir el archivo a Cloudinary: ' . $response->body());
        }

        $secureUrl = $response->json('secure_url');

        if (!$secureUrl) {
            throw new RuntimeException('Cloudinary no devolvió una URL válida: ' . $response->body());
        }

        return $secureUrl;
    }
}
