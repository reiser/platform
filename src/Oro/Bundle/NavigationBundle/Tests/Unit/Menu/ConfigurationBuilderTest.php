<?php

namespace Oro\Bundle\NavigationBundle\Tests\Unit\Menu;

use Oro\Bundle\NavigationBundle\Menu\AclAwareMenuFactoryExtension;
use Oro\Bundle\NavigationBundle\Menu\ConfigurationBuilder;
use Oro\Component\Config\Resolver\SystemAwareResolver;

use Knp\Menu\MenuItem;

class ConfigurationBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ConfigurationBuilder $configurationBuilder
     */
    protected $configurationBuilder;

    /**
     * @var AclAwareMenuFactoryExtension
     */
    protected $factory;

    protected function setUp()
    {
        $resolver = new SystemAwareResolver();
        $this->configurationBuilder = new ConfigurationBuilder($resolver);

        $this->factory = $this->getMockBuilder('Knp\Menu\MenuFactory')
            ->setMethods(array('getRouteInfo', 'processRoute'))
            ->getMock();

        $this->factory->expects($this->any())
            ->method('getRouteInfo')
            ->will($this->returnValue(false));

        $this->factory->expects($this->any())
            ->method('processRoute')
            ->will($this->returnSelf());
    }

    /**
     * @dataProvider menuStructureProvider
     * @param array $options
     */
    public function testBuild($options)
    {
        $this->configurationBuilder->setConfiguration($options);

        $menu = new MenuItem('navbar', $this->factory);
        $this->configurationBuilder->build($menu, array(), 'navbar');

        $this->assertCount(2, $menu->getChildren());
        $this->assertEquals($options['tree']['navbar']['type'], $menu->getExtra('type'));
        $this->assertCount(
            count($options['tree']['navbar']['children']['user_user_show']['children']),
            $menu->getChild('user_user_show')
        );
        $this->assertEquals('user_user_show', $menu->getChild('user_user_show')->getName());
    }

    /**
     * @dataProvider setAreaToExtraProvider
     * @param array $options
     * @param string $expectedArea
     */
    public function testSetAreaToExtra($options, $expectedArea)
    {
        $this->configurationBuilder->setConfiguration($options);

        $menu = new MenuItem('navbar', $this->factory);
        $this->configurationBuilder->build($menu, array(), 'navbar');

        $this->assertEquals($expectedArea, $menu->getExtra('area'));
    }

    public function setAreaToExtraProvider()
    {
        $defaultConfig = array(
            'areas' => array(),
            'items' => array(
                'homepage' => array(
                    'name' => 'Home page 2',
                    'label' => 'Home page title',
                    'route' => 'oro_menu_index',
                    'translateDomain' => 'SomeBundle',
                    'translateParameters' => array(),
                    'routeParameters' => array(),
                    'extras' => array()
                )
            ),
            'tree' => array(
                'navbar' => array(
                    'type' => 'navbar',
                    'children' => array(
                        'homepage' => array(
                            'position' => 7,
                            'children' => array()
                        )
                    )
                )
            )
        );

        return array(
            'with no area specified' => array(
                'options' => $defaultConfig,
                'expectedArea' => ConfigurationBuilder::DEFAULT_AREA,
            ),
            'with area' => array(
                'options' => array_merge($defaultConfig, array(
                    'areas' => array(
                        'frontend' => ['navbar']
                    )
                )),
                'expectedArea' => 'frontend',
            )
        );
    }

    /**
     * @return array
     */
    public function menuStructureProvider()
    {
        return array(
            'full_menu' => array(array(
                'areas' => array(),
                'templates' => array(
                    'navbar' => array(
                        'template' => 'OroNavigationBundle:Menu:navbar.html.twig'
                        ),
                    'dropdown' => array(
                        'template' => 'OroNavigationBundle:Menu:dropdown.html.twig'
                    )
                ),
                'items' => array(
                    'homepage' => array(
                        'name' => 'Home page 2',
                        'label' => 'Home page title',
                        'route' => 'oro_menu_index',
                        'translateDomain' => 'SomeBundle',
                        'translateParameters' => array(),
                        'routeParameters' => array(),
                        'extras' => array()
                    ),
                    'user_registration_register' => array(
                        'route' => 'oro_menu_submenu',
                        'translateDomain' => 'SomeBundle',
                        'translateParameters' => array(),
                        'routeParameters' => array(),
                        'extras' => array()
                    ),
                    'user_user_show' => array(
                        'translateDomain' => 'SomeBundle',
                        'translateParameters' => array(),
                        'routeParameters' => array(),
                        'extras' => array()
                    ),
                ),
                'tree' => array(
                    'navbar' => array(
                        'type' => 'navbar',
                        'extras' => array(
                            'brand' => 'Oro',
                            'brandLink' => '/'
                        ),
                        'children' => array(
                            'user_user_show' => array(
                                'position' => '10',
                                'children' => array(
                                    'user_registration_register' => array(
                                        'children' => array()
                                    )
                                )
                            ),
                            'homepage' => array(
                                'position' => 7,
                                'children' => array()
                            )
                        )
                    )
                )
            ))
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Item key "user_user_show" duplicated in tree menu "navbar".
     */
    public function testBuildDiblicatedItemTreeCallException()
    {
        $options = [
            'areas' => [],
            'items' => [
                'user_registration_register' => [
                    'route' => 'oro_menu_submenu',
                    'extras' => []
                ],
                'user_user_show' => [
                    'translateDomain' => 'SomeBundle',
                    'extras' => []
                ],
            ],
            'tree' => [
                'navbar' => [
                    'type' => 'navbar',
                    'extras' => [],
                    'children' => [
                        'user_user_show' => [
                            'position' => '10',
                            'children' => [
                            'user_registration_register' => [
                                'children' => [
                                    'user_user_show' => [
                                        'children' => []
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $this->configurationBuilder->setConfiguration($options);
        $menu = new MenuItem('navbar', $this->factory);
        $this->configurationBuilder->build($menu, [], 'navbar');
    }
}
