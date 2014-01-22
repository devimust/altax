<?php
namespace Altax\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class InitCommand extends \Altax\Command\BaseCommand
{   
    const TEMPLATE = <<<EOL
<?php
/**
 * Altax Configurations.
 *
 * You need to modify this file for your environment.
 *
 * @see https://github.com/kohkimakimoto/altax
 * @author yourname <youremail@yourcompany.com>
 */

EOL;
    
    const COMPOSER_TEMPLATE = <<<EOL
{
  "require": {
    "php": ">=5.3.0"
  }
}
EOL;

    protected function configure()
    {
        $this
            ->setName('init')
            ->setDescription('Create default configuration directory and files under the current directory.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configurationPath = getcwd()."/.altax/config.php";

        if (is_file($configurationPath)) {
            throw new \RuntimeException("$configurationPath already exists.");
        }

        $fs = new Filesystem();
        $fs->mkdir(dirname($configurationPath), 0755);
        file_put_contents($configurationPath, self::TEMPLATE);
        $output->writeln("<info>Created file: </info>$configurationPath");

        $composerFile = dirname($configurationPath)."/composer.json";
        if (is_file($composerFile)) {
            throw new \RuntimeException("$composerFile already exists.");
        }
        file_put_contents($composerFile, self::COMPOSER_TEMPLATE);
        $output->writeln("<info>Created file: </info>$composerFile");

     }

}