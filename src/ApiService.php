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

use YearOfPrayer\ApiService\Contracts\ApiServiceInterface;
use YearOfPrayer\ApiService\Contracts\HttpServiceInterface;

class ApiService implements ApiServiceInterface
{
    /**
     * The Consumer Service Object
     * @var ConsumerService
     * @access private
     */
    private $consumerService;

    /**
     * The Prayer Service Object
     * @var PrayerService
     * @access private
     */
    private $prayerService;

    /**
     * Set up the class
     *
     * @param ConsumerService      $consumerService The consumer service
     * @param PrayerService        $prayerService   [The Prayer service
     */
    public function __construct(
        ConsumerService $consumerService,
        PrayerService $prayerService
    ) {
        $this->consumerService = $consumerService;
        $this->prayerService = $prayerService;
    }

    /**
     * Register the new Consumer
     *
     * @param  string $clientId Your client id
     * @param  array  $data     The consumers data
     * @return string           The new consumer's API key
     *
     * @throws \InvalidArgumentException if the client id is not set
     * @access public
     */
    public function registerConsumer(string $clientId, array $data): string
    {
        if ((!$clientId) || ($clientId == '')) {
            throw new \InvalidArgumentException('You are missing the Year of Prayer Client ID.');
        }
        if ($this->consumerService->validate($data)) {
            $consumer = $this->consumerService->register($clientId, $data);
            return $consumer['api_key'];
        } else {
            throw new \InvalidArgumentException('The data you provided is invalid.');
        }
    }

    /**
     * Update the Consumer
     * @param  string   $apiKey     The API Key of the Consumer
     * @param  array    $data       The consumers data
     * @return boolean              Was it successful?
     *
     * @throws \InvalidArgumentException If you do not provide a valid API key
     * @throws \InvalidArgumentException If you do not pass data to update.
     * @access public
     */
    public function updateConsumer(string $apiKey, array $data): bool
    {
        if ($apiKey == '') {
            throw new \InvalidArgumentException('You need to provide a vaild API key.');
        } elseif (count($data) == 0) {
            throw new \InvalidArgumentException('You need to provide data for the consumer update.');
        } elseif (array_key_exists('api_key', $data)) {
            throw new \InvalidArgumentException('You cannot update the API key.');
        }
        return $this->consumerService->update($apiKey, $data);
    }

    /**
     * Indicate a user is praying
     * @param  string   $apiKey     The API Key of the Consumer
     * @param  string   $prayerId   The unique id for the Prayer
     * @return boolean              Did it register the prayer?
     *
     * @throws \InvalidArgumentException If you do not provide a valid API key.
     * @throws \InvalidArgumentException If you do not provide a valid prayerId.
     * @access public
     */
    public function praying(string $apiKey, string $prayerId): bool
    {
        $data = ['id'   =>  $prayerId];
        if (empty($apiKey)) {
            throw new \InvalidArgumentException('The apiKey you provided is invalid.');
        } elseif (!$this->prayerService->validate($data)) {
            throw new \InvalidArgumentException('The prayerId you provided is invalid.');
        } else {
            return $this->prayerService->praying($apiKey, $data);
        }
    }

    /**
     * Get the stats for the prayer
     * @param  string   $authorizeKey   The API Key of the Consumer or the application id for the client.
     * @param  string   $keyType        The type of auth key sent (consumer|client).
     * @param  string   $prayerId       The unique id for the Prayer
     * @return array                    The prayer stats from the API
     *
     * @throws \InvalidArgumentException If you do not provide a valid authorization key.
     * @throws \InvalidArgumentException If you do not provide a valid authorization key type.
     * @throws \InvalidArgumentException If you do not provide a valid prayerId.
     * @access public
     */
    public function prayerStats(string $authorizeKey, string $keyType, string $prayerId): array
    {
        $data = ['id'   =>  $prayerId];
        if (empty($authorizeKey)) {
            throw new \InvalidArgumentException('The authorizeKey you provided is invalid.');
        } elseif (!in_array($keyType, ['consumer', 'client'])) {
            throw new \InvalidArgumentException('The keyType you provided is invalid.');
        } elseif (!$this->prayerService->validate($data)) {
            throw new \InvalidArgumentException('The prayerId you provided is invalid.');
        } else {
            return $this->prayerService->prayerStats($authorizeKey, $keyType, $data);
        }
    }
}
