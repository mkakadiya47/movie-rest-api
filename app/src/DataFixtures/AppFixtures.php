<?php

namespace App\DataFixtures;

use App\Entity\Cast;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\ApiToken;
use App\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AppFixtures
 * @package App\DataFixtures
 */
class AppFixtures extends Fixture implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    public function load(ObjectManager $manager): void
    {

        $user = new User();
        $user->setEmail('arpi@test.tech');
        $user->setUserName('arpi@test.tech');
        $user->setSalt('123456');
        $hash = $this->container->get('security.password_encoder')->encodePassword($user, 'arpita');
        $user->setPassword($hash);
        $user->setRoles(["ROLE_API_USER"]);
        $manager->persist($user);
        $manager->flush();
    }
}
