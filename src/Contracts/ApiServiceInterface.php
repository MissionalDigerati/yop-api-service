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

namespace YearOfPrayer\ApiService\Contracts;

interface ApiServiceInterface
{
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
    public function registerConsumer(string $clientId, array $data): string;
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
    public function updateConsumer(string $apiKey, array $data): bool;
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
    public function praying(string $apiKey, string $prayerId): bool;
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
    public function prayerStats(string $authorizeKey, string $keyType, string $prayerId): array;
}
