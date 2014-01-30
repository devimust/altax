<?php
namespace Altax\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends \Symfony\Component\Console\Command\Command
{
    protected $task;
    
    protected function getContainer()
    {
        return $this->getApplication()->getContainer();
    }

    /**
     * Runs the command.
     *
     * The code to execute is either defined directly with the
     * setCode() method or by overriding the execute() method
     * in a sub-class.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return integer The command exit code
     *
     * @throws \Exception
     *
     * @see setCode()
     * @see execute()
     *
     * @api
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        if ($this->hasTask()) {

            $this->preProcessForTask($input, $output);

        }

        return parent::run($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }

    protected function executeTaskClosure(InputInterface $input, OutputInterface $output)
    {
        return call_user_func($this->task->closure, $this->task);
    }

    protected function preProcessForTask(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<info>Running</info> ".$this->task->name);
        $this->task->setInput($input);
        $this->task->setOutput($output);

        if ($this->task->hasClosure()) {
            $this->setCode(array($this, "executeTaskClosure"));
        }
    }

    public function setTask($task)
    {
        $this->task = $task;
    }

    public function hasTask()
    {
        return isset($this->task);
    }
}