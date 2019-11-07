<?php

declare(strict_types=1);

namespace App\Rules;

use GuzzleHttp\Client;
use Illuminate\Contracts\Validation\Rule;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;

/**
 * Only accept URLs that respond with a successful status code (e.g. 200 OK).
 * Since this check is time-consuming, it should be placed at the end of the
 * validation chain while making it use the `bail` validator so it can fail early.
 */
class SuccessRespondingUrl implements Rule
{
    /**
     * Determine if the validation rule passes.
     */
    public function passes($attribute, $value): bool
    {
        $client = new Client();

        try {
            $response = $client->get($value, [
                'synchronous' => true,
                'timeout' => 10,
                'http_errors' => false,
            ]);

            // It must return a successful response code to be considered valid
            return $response->getStatusCode() >= 200 && $response->getStatusCode() < 400;
        } catch (ConnectException | RequestException $exception) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     */
    public function message()
    {
        return __("The :attribute doesn't seem to point to a valid page or resource.");
    }
}
