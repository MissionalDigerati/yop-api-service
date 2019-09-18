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

class PrayerService
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
     * Indicate that you are praying
     *
     * @param  string   $apiKey     The Consumer's apiKey
     * @param  array    $data       The data containing the prayerId
     * @return boolean              Was it successful
     * @access public
     */
    public function praying($apiKey, $data)
    {
        $sendData = [
            'headers'   =>    [
                'yop-api-key' =>  $apiKey
            ]
        ];
        $response = $this->httpService->post('/prayers/' . $data['id'] . '/praying', $sendData);
        return ($response['status'] === 'success');
    }

    /**
     * Get the stats for the prayer
     *
     * @param  string   $authorizeKey   The API Key of the Consumer or the application id for the client.
     * @param  string   $keyType        The type of auth key sent (consumer|client).
     * @param  string   $data           The data containing the prayerId
     * @return array                    An array of prayer stats
     * @access public
     */
    public function prayerStats($authorizeKey, $keyType, $data)
    {
        if ($keyType === 'client') {
            $sendData = [
                'headers'   =>    [
                    'yop-client-application-id' =>  $authorizeKey
                ]
            ];
        } elseif ($keyType === 'consumer') {
            $sendData = [
                'headers'   =>    [
                    'yop-api-key' =>  $authorizeKey
                ]
            ];
        }
        $response = $this->httpService->get('/prayers/' . $data['id'], $sendData);
        return $response['success']['data'];
    }

    /**
     * Validate the prayer data
     *
     * @param  array    $data   The data to validate
     * @return boolean          Is it valid
     * @access public
     */
    public function validate($data)
    {
        $valid = true;
        $regex = '/^[0-9]{2}-[0-9]{2}$/';

        if (!preg_match($regex, $data['id'])) {
            $valid = false;
        } else {
            $prayerPieces = explode('-', $data['id']);
            $month = intval($prayerPieces[0]);
            $day = intval($prayerPieces[1]);
            if ((!$this->validMonth($month)) || (!$this->validDay($month, $day))) {
                $valid = false;
            }
        }
        return $valid;
    }

    /**
     * Is the month valid?
     *
     * @param  int      $month  The month to test
     * @return boolean          Is it valid?
     * @access private
     */
    private function validMonth($month)
    {
        return ($month <= 12);
    }

    /**
     * Is the day valid?
     *
     * @param  int      $month  The month of the day to test
     * @param  int      $day    The day to test
     * @return boolean          Is it valid?
     * @access private
     */
    private function validDay($month, $day)
    {
        $totalDays = 31;
        if (in_array($month, [4, 6, 9, 11])) {
            $totalDays = 30;
        } elseif ($month === 2) {
            $totalDays = 29;
        }
        return ($day <= $totalDays);
    }
}
