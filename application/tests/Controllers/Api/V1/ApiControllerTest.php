<?php namespace CodeIgniter;

use CodeIgniter\Test\CIDatabaseTestCase;
use CodeIgniter\Test\ControllerTester;

class ApiControllerTest extends CIDatabaseTestCase
{
    use ControllerTester;

    /**
     * function _getModel
     *
     * @dataProvider getControllerProvider
     */
    public function testGetModel($controller, $expected)
    {
        $base = new \App\Controllers\Api\V1\ApiController();
        $method = $this->getPrivateMethodInvoker($base, '_getModel');

        $actual = $method(new $controller());
        $expected = "App\\Models\\{$expected}Model";

        $this->assertEquals($expected, get_class($actual));
    }

    /**
     * function _getEntity
     *
     * @dataProvider getControllerProvider
     */
    public function testGetEntity($controller, $expected)
    {
        $base = new \App\Controllers\Api\V1\ApiController();
        $method = $this->getPrivateMethodInvoker($base, '_getEntity');

        $actual = $method(new $controller());
        $expected = "App\\Entities\\{$expected}";

        $this->assertEquals($expected, get_class($actual));
    }

    public function getControllerProvider()
    {
        return [
            ['\\App\\Controllers\\Api\\V1\\Shoken', 'Shoken'],
            ['\\App\\Controllers\\Api\\V1\\Ukeban', 'Ukeban'],
            ['\\App\\Controllers\\Api\\V1\\Shujutsu', 'Shujutsu'],
            ['\\App\\Controllers\\Api\\V1\\Nyuin', 'Nyuin'],
            ['\\App\\Controllers\\Api\\V1\\Tsuin', 'Tsuin'],
            ['\\App\\Controllers\\Api\\V1\\Result', 'Result'],
        ];
    }

    /**
     * function _getParentId
     *
     * @dataProvider getParentIdProvider
     */
    public function testGetParentId($uri, $expected)
    {
        $mock = new Mock($uri);
        $base = new \App\Controllers\Api\V1\ApiController();
        $method = $this->getPrivateMethodInvoker($base, '_getParentId');
        $actual = $method($mock);

        $this->assertEquals($expected, $actual);
    }

    public function getParentIdProvider()
    {
        return [
            [ // standard
                ['api', 'v1', 'test', '10', 'ff', '100'],
                ['test_id' => '10', 'ff_id' => '100'],
            ],
            [ // if mock_id included
                ['api', 'v1', 'mock', '10', 'ff', '100'],
                ['id' => '10', 'ff_id' => '100'],
            ],
        ];
    }
}

class Mock
{
    public $request;
    public function __construct($uri)
    {
        $this->request = new class($uri)
            {
                public $uri;
                function __construct($uri)
                {
                    $this->uri = new class($uri)
                        {
                            public $uri;
                            function __construct($uri)
                            {
                                $this->uri = $uri;
                            }
                            function getSegments()
                            {
                                return $this->uri;
                            }
                        };
                }
            };
    }
}
