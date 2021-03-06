<?php

/*
 * This file is part of the RollerworksPasswordStrengthBundle package.
 *
 * (c) Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Rollerworks\Bundle\PasswordStrengthBundle\Tests\Command;

use Rollerworks\Bundle\PasswordStrengthBundle\Command\BlacklistUpdateCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class BlacklistUpdateCommandTest extends BlacklistCommandTestCase
{
    public function testAddOneWord()
    {
        $application = new Application();
        $command = new BlacklistUpdateCommand();
        $command->setContainer(self::$container);
        $application->add($command);

        $command = $application->find('rollerworks-password:blacklist:update');

        self::assertFalse($this->getProvider()->isBlacklisted('test'));

        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName(), 'passwords' => 'test'));

        self::assertTrue($this->getProvider()->isBlacklisted('test'));
        self::assertRegExp('/Successfully added 1 password\(s\) to your blacklist database/', $commandTester->getDisplay());
    }

    public function testAddExistingWord()
    {
        $application = new Application();
        $command = new BlacklistUpdateCommand();
        $command->setContainer(self::$container);
        $application->add($command);

        $command = $application->find('rollerworks-password:blacklist:update');

        self::assertFalse($this->getProvider()->isBlacklisted('test'));

        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName(), 'passwords' => 'test'));

        self::assertTrue($this->getProvider()->isBlacklisted('test'));
        self::assertRegExp('/Successfully added 1 password\(s\) to your blacklist database/', $commandTester->getDisplay());

        self::assertTrue($this->getProvider()->isBlacklisted('test'));
        $commandTester->execute(array('command' => $command->getName(), 'passwords' => 'test'));

        self::assertTrue($this->getProvider()->isBlacklisted('test'));
        self::assertRegExp('/Successfully added 0 password\(s\) to your blacklist database/', $commandTester->getDisplay());
    }

    public function testAddTwoWords()
    {
        $application = new Application();
        $command = new BlacklistUpdateCommand();
        $command->setContainer(self::$container);
        $application->add($command);

        $command = $application->find('rollerworks-password:blacklist:update');

        self::assertFalse($this->getProvider()->isBlacklisted('test'));
        self::assertFalse($this->getProvider()->isBlacklisted('foobar'));

        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName(), 'passwords' => array('test', 'foobar')));

        self::assertTrue($this->getProvider()->isBlacklisted('test'));
        self::assertTrue($this->getProvider()->isBlacklisted('foobar'));
        self::assertRegExp('/Successfully added 2 password\(s\) to your blacklist database/', $commandTester->getDisplay());
    }

    public function testNoInput()
    {
        $application = new Application();
        $command = new BlacklistUpdateCommand();
        $command->setContainer(self::$container);
        $application->add($command);

        $command = $application->find('rollerworks-password:blacklist:update');

        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        self::assertNotRegExp('/Successfully added \d+ password\(s\) to your blacklist database/', $commandTester->getDisplay());
        self::assertRegExp('/No passwords or file-option given/', $commandTester->getDisplay());
    }

    public function testImportFromFile()
    {
        $application = new Application();
        $command = new BlacklistUpdateCommand();
        $command->setContainer(self::$container);
        $application->add($command);

        $command = $application->find('rollerworks-password:blacklist:update');

        self::assertFalse($this->getProvider()->isBlacklisted('test'));
        self::assertFalse($this->getProvider()->isBlacklisted('foobar'));

        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName(), '--file' => __DIR__.'/../fixtures/passwords-list1.txt'));

        self::assertTrue($this->getProvider()->isBlacklisted('test'));
        self::assertTrue($this->getProvider()->isBlacklisted('foobar'));
        self::assertRegExp('/Successfully added 2 password\(s\) to your blacklist database/', $commandTester->getDisplay());
    }

    public function testImportExistingFromFile()
    {
        $application = new Application();
        $command = new BlacklistUpdateCommand();
        $command->setContainer(self::$container);
        $application->add($command);

        $command = $application->find('rollerworks-password:blacklist:update');

        self::assertFalse($this->getProvider()->isBlacklisted('test'));
        self::assertFalse($this->getProvider()->isBlacklisted('foobar'));

        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName(), '--file' => __DIR__.'/../fixtures/passwords-list1.txt'));

        self::assertTrue($this->getProvider()->isBlacklisted('test'));
        self::assertTrue($this->getProvider()->isBlacklisted('foobar'));
        self::assertRegExp('/Successfully added 2 password\(s\) to your blacklist database/', $commandTester->getDisplay());

        self::assertTrue($this->getProvider()->isBlacklisted('test'));
        self::assertTrue($this->getProvider()->isBlacklisted('foobar'));
        $commandTester->execute(array('command' => $command->getName(), '--file' => __DIR__.'/../fixtures/passwords-list1.txt'));

        self::assertTrue($this->getProvider()->isBlacklisted('test'));
        self::assertTrue($this->getProvider()->isBlacklisted('foobar'));
        self::assertRegExp('/Successfully added 0 password\(s\) to your blacklist database/', $commandTester->getDisplay());
    }

    public function testImportFromRelFile()
    {
        $application = new Application();
        $command = new BlacklistUpdateCommand();
        $command->setContainer(self::$container);
        $application->add($command);

        $command = $application->find('rollerworks-password:blacklist:update');

        self::assertFalse($this->getProvider()->isBlacklisted('test'));
        self::assertFalse($this->getProvider()->isBlacklisted('foobar'));

        // This changes the current working directory to this one so we can check relative files
        chdir(__DIR__);

        $commandTester = new CommandTester($command);
        $commandTester->execute(
            array('command' => $command->getName(), '--file' => '../fixtures/passwords-list1.txt')
        );

        self::assertRegExp('/Successfully added 2 password\(s\) to your blacklist database/', $commandTester->getDisplay());
        self::assertTrue($this->getProvider()->isBlacklisted('test'));
        self::assertTrue($this->getProvider()->isBlacklisted('foobar'));
    }

    public function testImportFromNoFile()
    {
        $application = new Application();
        $command = new BlacklistUpdateCommand();
        $command->setContainer(self::$container);
        $application->add($command);

        $command = $application->find('rollerworks-password:blacklist:update');

        self::assertFalse($this->getProvider()->isBlacklisted('test'));
        self::assertFalse($this->getProvider()->isBlacklisted('foobar'));

        $commandTester = new CommandTester($command);
        $commandTester->execute(
            array('command' => $command->getName(), '--file' => '../fixtures/unknown.txt')
        );

        self::assertRegExp('#Unable to read passwords list. No such file: \.\./fixtures/unknown\.txt#', $commandTester->getDisplay());
    }

    public function testImportFromEmptyFile()
    {
        $application = new Application();
        $command = new BlacklistUpdateCommand();
        $command->setContainer(self::$container);
        $application->add($command);

        $command = $application->find('rollerworks-password:blacklist:update');

        self::assertFalse($this->getProvider()->isBlacklisted('test'));
        self::assertFalse($this->getProvider()->isBlacklisted('foobar'));

        $commandTester = new CommandTester($command);
        $commandTester->execute(
            array('command' => $command->getName(), '--file' => __DIR__.'/../fixtures/passwords-list2.txt')
        );

        self::assertRegExp('/Passwords list seems empty, are you sure this is the correct file\?/', $commandTester->getDisplay());
    }
}
