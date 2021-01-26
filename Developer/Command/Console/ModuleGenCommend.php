<?php
/**
 * Created by PhpStorm.
 * User: prabhakaran
 * Date: 26/1/21
 * Time: 4:30 PM
 */

namespace Wac\Developer\Command\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Magento\Framework\Console\Cli;
use Wac\Developer\Model\CreateModule;

class ModuleGenCommend extends Command
{

    const VENDOR_NAME='vendor';

    const MODULE_NAME = 'name';

    /**
     * @var CreateModule
     */
    protected $createModule;


    /**
     * ModuleGenCommend constructor.
     * @param null $name
     * @param CreateModule $createModule
     */
    public function __construct (
     CreateModule $createModule
    )
    {

        parent::__construct();
        $this->createModule = $createModule;
    }


    protected function configure()
    {
        $commandOptions = [new InputArgument(self::VENDOR_NAME,  InputArgument::REQUIRED, 'Vendor Name'),
            new InputArgument(self::MODULE_NAME,  InputArgument::REQUIRED, 'Module Name')];
        $this->setName('module:gen');
        $this->setAliases(['mo:g']);
        $this->setDescription('Module Generator via cli');
        $this->setDefinition($commandOptions);

        parent::configure();
    }


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {


        try {
            $vendorName = $input->getArgument(self::VENDOR_NAME);
            $moduleName = $input->getArgument(self::MODULE_NAME);


            $this->createModule->execute($vendorName,$moduleName);

        }

        catch (\Exception $e)
        {
            $output->writeln($e->getMessage());
            return Cli::RETURN_FAILURE;
        }


        return Cli::RETURN_SUCCESS;


    }


}