<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace YvesUnit\Spryker\Zed\Kernel\ControllerResolver;

use Silex\Application;
use Spryker\Yves\Kernel\ControllerResolver\YvesFragmentControllerResolver;
use Symfony\Component\HttpFoundation\Request;

/**
 * @group Kernel
 * @group Yves
 * @group ControllerResolver
 */
class YvesFragmentControllerResolverTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider getController
     *
     * @param string $controller
     * @param string $expectedServiceName
     *
     * @return void
     */
    public function testCreateController($controller, $expectedServiceName)
    {
        $request = $this->getRequest($controller);
        $controllerResolver = $this->getFragmentControllerProvider($request);

        $result = $controllerResolver->getController($request);

        $this->assertSame($expectedServiceName, $request->attributes->get('_controller'));
        $this->assertInternalType('callable', $result);
    }

    /**
     * @return array
     */
    public function getController()
    {
        return [
            ['index/index/index', 'YvesUnit\Spryker\Zed\Kernel\ControllerResolver\YvesFragmentControllerResolverTest::indexAction'],
            ['/index/index/index', 'YvesUnit\Spryker\Zed\Kernel\ControllerResolver\YvesFragmentControllerResolverTest::indexAction'],
            ['Index/Index/Index', 'YvesUnit\Spryker\Zed\Kernel\ControllerResolver\YvesFragmentControllerResolverTest::indexAction'],
            ['/Index/Index/Index', 'YvesUnit\Spryker\Zed\Kernel\ControllerResolver\YvesFragmentControllerResolverTest::indexAction'],
            ['foo-bar/baz-bat/zip-zap', 'YvesUnit\Spryker\Zed\Kernel\ControllerResolver\YvesFragmentControllerResolverTest::zipZapAction'],
        ];
    }

    /**
     * @param $name
     * @param array $arguments
     *
     * @return void
     */
    public function __call($name, $arguments = [])
    {
    }

    /**
     * @param Request $request
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|YvesFragmentControllerResolver
     */
    protected function getFragmentControllerProvider(Request $request)
    {
        $controllerResolverMock = $this->getMock(YvesFragmentControllerResolver::class, ['resolveController', 'getCurrentRequest'], [], '', false);
        $controllerResolverMock->method('resolveController')->willReturn($this);
        $controllerResolverMock->method('getCurrentRequest')->willReturn($request);

        return $controllerResolverMock;
    }

    /**
     * @param string $controller
     *
     * @return Request
     */
    private function getRequest($controller)
    {
        return new Request([], [], ['_controller' => $controller]);
    }

}
