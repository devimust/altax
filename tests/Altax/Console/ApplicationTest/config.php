<?php

Server::node("127.0.0.1");

// Basic test task
Task::register("testBasic", function($task){

    $task->writeln("output log");
    $task->process("echo runLocally")->runLocally();
    
    // Can't catch the output message on PHPUnit test case.
    // Because this process runs on child forked process.
    $task->process("echo run Remotely")
        ->to("127.0.0.1")
        ->run()
        ;

    $task->process(function($node){

        return "echo ".$node->getName()."";

    })->to("127.0.0.1")->runLocally();

    $task->process(function($node){

        return "echo ".$node->getName()."";

    })->to("127.0.0.1")->run();

    $task->process('echo {{ $node->getName() }}')->to("127.0.0.1")->runLocally();

});


Task::register("testHidden", function(){

    
})->hidden();

Task::register("testBeforeAndAfter", function(){

    
})->after("sample2")->before("sample");