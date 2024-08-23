<?php

/**
 * This file is part of Year of Prayer Service.
 *
 * Year of Prayer Service is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Year of Prayer Service is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Missional Digerati <info@missionaldigerati.org>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */

namespace YearOfPrayer\ApiService;

use YearOfPrayer\ApiService\Contracts\HttpServiceInterface;

class ConsumerService
{
    /**
     * The Http Service
     *
     * @var HttpServiceInterface
     * @access private
     */
    private $httpService;

    /**
     * Set the Http client to use
     *
     * @param  HttpServiceInterface     $service    The service for making Http Requests
     *
     * @access public
     */
    public function setHttpService(HttpServiceInterface $service)
    {
        $this->httpService = $service;
    }

    /**
     * Is the Consumer data valid?
     * @param  array    $data   The Consumer data
     * @return boolean          Is it valid?
     * @access public
     */
    public function validate(array $data): bool
    {
        $valid = true;
        if ((!isset($data['device_uuid'])) || ($data['device_uuid'] == '')) {
            $valid = false;
        }
        return $valid;
    }

    /**
     * Register a new consumer
     *
     * @param   string  $clientId   The id for the Client of the API
     * @param   array    $data      The Consumer data
     * @return  array               The Consumer's new data
     * @access  public
     */
    public function register(string $clientId, array $data): array
    {
        $sendData = [
            'headers'   =>    [
                'yop-client-application-id' =>  $clientId
            ],
            'form_params'   => $data
        ];
        $response = $this->httpService->post('/consumers/register', $sendData);
        return $response['success']['data'];
    }

    /**
     * Update the consumer information
     *
     * @param  string   $apiKey Their API key
     * @param  array    $data   The data to update
     * @return boolean          Was it successful?
     * @access public
     */
    public function update(string $apiKey, array $data): bool
    {
        $sendData = [
            'headers'   =>    [
                'yop-api-key' =>  $apiKey
            ],
            'form_params'   => $data
        ];
        $response = $this->httpService->put('/consumers/update', $sendData);
        return ($response['status'] === 'success');
    }
}
