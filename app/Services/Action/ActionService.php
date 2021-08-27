<?php

namespace App\Services\Action;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

/**
 * Class ActionService
 */
class ActionService implements ActionContract
{
    private string $landingId;
    private string $actionServiceUrl;
    private string $actionServiceToken;
    private string $actionServiceMethod;
    private string $jsonRpcVersion = '2.0';

    public function __construct()
    {
        $this->landingId = config('services.landing.id');
        $this->actionServiceUrl = config('services.action_service.url');
        $this->actionServiceToken = config('services.action_service.token');
    }

    /**
     * @param  string  $url
     */
    public function store(string $url): void
    {
        $this->actionServiceMethod = config('services.action_service.method.store');
        if ($this->emptyVariable()) {
            return;
        }

        $parameters = [
            'landing_id' => $this->landingId,
            'url' => $url,
            'visit_date' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $this->makeRequest($parameters);
    }

    /**
     * @param  int  $page
     * @return array
     */
    public function show(int $page): array
    {
        $this->actionServiceMethod = config('services.action_service.method.show');
        if ($this->emptyVariable()) {
            return [];
        }

        $parameters = [
            'landing_id' => $this->landingId,
            'page' => $page,
        ];

        $result = $this->makeRequest($parameters);
        if (!is_array($result)) {
            return [];
        }

        return $result;
    }

    /**
     * @param  array  $parameters
     * @return array|mixed
     */
    private function makeRequest(array $parameters)
    {
        $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->withToken($this->actionServiceToken)
            ->timeout(5)
            ->post($this->actionServiceUrl, [
                'jsonrpc' => $this->jsonRpcVersion,
                'method' => $this->actionServiceMethod,
                'params' => $parameters,
                'id' => time(),
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return [];
    }

    /**
     * @return bool
     */
    private function emptyVariable(): bool
    {
        return empty($this->landingId)
            || empty($this->actionServiceUrl)
            || empty($this->actionServiceToken)
            || empty($this->actionServiceMethod);
    }
}
