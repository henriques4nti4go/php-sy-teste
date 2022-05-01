<?php

use Symfony\Component\HttpFoundation\JsonResponse;

class HttpResponse
{
    static function json($content, $status = 200)
    {
        if (is_array($content)) {
            $content = json_encode($content);
        }

        $jsonResponse = new JsonResponse();
        $jsonResponse
            ->setStatusCode($status)
            ->setContent(
                $content
            );

        return $jsonResponse;
    }
}
