<?php
namespace Altax\Command\Builtin;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Init Command
 */
class InitCommand extends SymfonyCommand
{
    const TEMPLATE = <<<EOL
<?php

Task::register('hello', function (\$name = "world") {

    Output::writeln("Hello \$name!");

})
->description("Say hello");

EOL;

    const COMPOSER_TEMPLATE = <<<EOL
{
    "require": {

    }
}

EOL;

    const GITIGNORE_TEMPLATE = <<<EOL
/vendor

EOL;

    protected function configure()
    {
        $this
            ->setName('init')
            ->setDescription('Creates default configuration directory and files under the current directory')
            ->addOption(
                '--global',
                '-g',
                InputOption::VALUE_NONE,
                "If specified, create user home configuration '~/.altax/config.php'"
                )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configurationPath = getcwd()."/.altax/config.php";

        if ($input->getOption('global')) {
            $configurationPath = getenv("HOME")."/.altax/config.php";
        }

        $output->writeln("<info>Creating inital configuration.</info>");
        if (!is_file($configurationPath)) {
            $this->generateConfig($configurationPath, $output);
        } else {
            $output->writeln("<error>File '$configurationPath' is already exists. Skiped creation process.</error>");
        }

        $composerFile = dirname($configurationPath)."/composer.json";
        if (!is_file($composerFile)) {
            $this->generateComposerFile($composerFile, $output);
        } else {
            $output->writeln("<error>File '$composerFile' is already exists. Skipped creation process.</error>");
        }

        $gitignoreFile = dirname($configurationPath)."/.gitignore";
        if (!is_file($gitignoreFile)) {
            $this->generateGitignore($gitignoreFile, $output);
        } else {
            $output->writeln("<error>File '$gitignoreFile' is already exists. Skipped creation process.</error>");
        }

     }

     protected function generateConfig($configurationPath, $output)
     {
        $fs = new Filesystem();
        $fs->mkdir(dirname($configurationPath), 0755);
        file_put_contents($configurationPath, self::TEMPLATE);
        $output->writeln("<info>Created file: </info>$configurationPath");
     }

     protected function generateComposerFile($composerFile, $output)
     {
        file_put_contents($composerFile, self::COMPOSER_TEMPLATE);
        $output->writeln("<info>Created file: </info>$composerFile");
     }

     protected function generateGitignore($gitignorePath, $output)
     {
        file_put_contents($gitignorePath, self::GITIGNORE_TEMPLATE);
        $output->writeln("<info>Created file: </info>$gitignorePath");
     }

}
