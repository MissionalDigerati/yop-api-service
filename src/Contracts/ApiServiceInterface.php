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
     * @access public
     */
    public function registerConsumer($clientId, $data);
    /**
     * Update the Consumer
     * @param  string   $apiKey     The API Key of the Consumer
     * @param  array    $data       The consumers data
     * @return boolean              Was it successful?
     * @access public
     */
    public function updateConsumer($apiKey, $data);
    /**
     * Indicate a user is praying
     * @param  string   $apiKey     The API Key of the Consumer
     * @param  string   $prayerId   The unique id for the Prayer
     * @return boolean              Did it register the prayer?
     * @access public
     */
    public function praying($apiKey, $prayerId);
    /**
     * Get the stats for the prayer
     * @param  string   $apiKey     The API Key of the Consumer
     * @param  string   $prayerId   The unique id for the Prayer
     * @return array                The prayer stats from the API
     * @access public
     */
    public function prayerStats($apiKey, $prayerId);
}
