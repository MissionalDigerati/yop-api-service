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
namespace YearOfPrayer\ApiService\Tests;

use YearOfPrayer\ApiService\PrayerService;
use YearOfPrayer\ApiService\Contracts\HttpServiceInterface;
use PHPUnit\Framework\TestCase;

class PrayerServiceTest extends TestCase
{

    /**
     * The PrayerService that is being tested
     *
     * @var PrayerService
     * @access private
     */
    private $prayerService;

    /**
     * The Guzzle HTTP Client Object
     *
     * @var YearOfPrayer\ApiService\Contracts\HttpServiceInterface
     */
    private $httpService;

    /**
     * Setup the tests
     *
     * @access public
     */
    public function setUp() : void
    {
        $this->prayerService = new PrayerService();
        $this->httpService = $this->getMockBuilder(
            'YearOfPrayer\ApiService\Contracts\HttpServiceInterface'
        )
                                ->setMethods(array('post', 'get', 'put', 'setBaseUrl'))
                                ->getMock();
        $this->prayerService->setHttpService($this->httpService);
    }

    /**
     * validate() should return true if the prayer id is valid
     *
     * @return void
     * @access public
     */
    public function testValidateShouldReturnTrueIfValidPrayerId()
    {
        $data = ['id'   =>  '10-21'];
        $this->assertTrue($this->prayerService->validate($data));
    }

    /**
     * validate() should return false if the prayer id empty
     *
     * @return void
     * @access public
     */
    public function testValidateShouldReturnFalsePrayerIdIsEmpty()
    {
        $data = ['id'   =>  ''];
        $this->assertFalse($this->prayerService->validate($data));
    }

    /**
     * validate() should return false if the prayer id is malformed
     *
     * @return void
     * @access public
     */
    public function testValidateShouldReturnFalseIfPrayerIdIsMalformed()
    {
        $data = ['id'   =>  '10322-211'];
        $this->assertFalse($this->prayerService->validate($data));
    }

    /**
     * validate() should return false if the prayer month is not 01-12
     *
     * @return void
     * @access public
     */
    public function testValidateShouldReturnFalseIfPrayerMonthIsOutOfRange()
    {
        $data = ['id'   =>  '13-11'];
        $this->assertFalse($this->prayerService->validate($data));
    }

    /**
     * validate() should return false if the prayer day is not 01-12
     *
     * @return void
     * @access public
     */
    public function testValidateShouldReturnFalseIfPrayerDayIsOutOfRange()
    {
        $data = ['id'   =>  '12-33'];
        $this->assertFalse($this->prayerService->validate($data));
    }

    /**
     * praying() should return true, if there is a success
     *
     * @return void
     * @access public
     */
    public function testPrayingShouldReturnTrueIfItIsASuccess()
    {
        $apiKey = '123456';
        $data = ['id'   =>  '10-10'];
        $responseReturnData = [
            'status'    =>  'success',
            'error'     =>  [],
            'success'   =>  [
                'message'   =>  'Thank You! The consumer has indicated a prayer.',
                'data'      =>  []
            ]
        ];

        $this->httpService->expects($this->once())
                            ->method('post')
                            ->will($this->returnValue($responseReturnData));


        $this->assertTrue($this->prayerService->praying($apiKey, $data));
    }

    /**
     * praying() should throw an error if the status is not 200
     *
     * @return void
     * @expectedException Exception
     * @access public
     */
    public function testPrayingShouldThrowErrorIfStatusIsNot200()
    {
        $apiKey = 'JHG$32331';
        $data = ['id'   =>  '11-15'];
        $this->httpService->expects($this->once())
                            ->method('post')
                            ->will($this->throwException(new \Exception));

        $this->prayerService->praying($apiKey, $data);
    }

    /**
     * prayerStats() should return data, as a consumer, if the status is 200
     *
     * @return void
     * @access public
     */
    public function testPrayerStatsAsConsumerShouldReturnDataIfItIsASuccess()
    {
        $apiKey = 'MonKeyTree';
        $expected = [
            'prayer_request_id'     =>  '12-28',
            'total_prayers'         =>  3,
            'your_prayers'          =>  1,
            'your_last_prayer_on'   => '2016-01-15 13:42:00'
        ];

        $responseReturnData = [
            'status'    =>  'success',
            'error'     =>  [],
            'success'   =>  [
                'message'   =>  'Thank You! Attached is the data for the prayer request.',
                'data'      =>  $expected
            ]
        ];
        $sendData = [
            'headers'   =>    [
                'yop-api-key' =>  $apiKey
            ]
        ];
        $this->httpService->expects($this->once())
                            ->method('get')
                            ->with(
                                $this->equalTo('/prayers/12-28'),
                                $this->equalTo($sendData)
                            )
                            ->will($this->returnValue($responseReturnData));

        $actual = $this->prayerService->prayerStats($apiKey, 'consumer', ['id'  =>  '12-28']);
        $this->assertEquals($expected, $actual);
    }

    /**
     * prayerStats() should return data, as a client, if the status is 200
     *
     * @return void
     * @access public
     */
    public function testPrayerStatsAsClientShouldReturnDataIfItIsASuccess()
    {
        $clientId = 'my-client-id';
        $expected = [
            'prayer_request_id'     =>  '11-10',
            'total_prayers'         =>  3
        ];

        $responseReturnData = [
            'status'    =>  'success',
            'error'     =>  [],
            'success'   =>  [
                'message'   =>  'Thank You! Attached is the data for the prayer request.',
                'data'      =>  $expected
            ]
        ];
        $sendData = [
            'headers'   =>    [
                'yop-client-application-id' =>  $clientId
            ]
        ];
        $this->httpService->expects($this->once())
                            ->method('get')
                            ->with(
                                $this->equalTo('/prayers/11-10'),
                                $this->equalTo($sendData)
                            )
                            ->will($this->returnValue($responseReturnData));

        $actual = $this->prayerService->prayerStats($clientId, 'client', ['id'  =>  '11-10']);
        $this->assertEquals($expected, $actual);
    }

    /**
     * prayerStats() should throw an error if the status is not 200
     * @return void
     * @expectedException Exception
     * @access public
     */
    public function testPrayerStatsShouldThrowAnErrorIfStatusIsNot200()
    {
        $apiKey = 'FritosPintos';
        $this->httpService->expects($this->once())
                            ->method('get')
                            ->will($this->throwException(new \Exception));

        $this->prayerService->prayerStats($apiKey, 'consumer', ['id'    =>  '10-21']);
    }
}
