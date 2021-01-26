<?php
/**
 * Created by PhpStorm.
 * User: prabhakaran
 * Date: 26/1/21
 * Time: 5:04 PM
 */

namespace Wac\Developer\Model;

use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\App\Filesystem\DirectoryList as AppDirectoryList;
use Magento\Framework\Filesystem\Directory\ReadFactory;
use Magento\Framework\Filesystem\Directory\WriteFactory;
use Magento\Framework\Filesystem;

class CreateModule
{

    const CODE_PATH_NAME="code";

    /**
     * @var DirectoryList
     */
    private $directoryList;
    /**
     * @var ReadFactory
     */
    private $readFactory;
    /**
     * @var WriteFactory
     */
    private $writeFactory;
    /**
     * @var Filesystem
     */
    private $filesystem;


    /**
     * CreateModuleCli constructor.
     * @param DirectoryList $directoryList
     * @param ReadFactory $readFactory
     * @param WriteFactory $writeFactory
     * @param Filesystem $filesystem
     */
    public function __construct (
        DirectoryList $directoryList,
        ReadFactory $readFactory,
        WriteFactory $writeFactory,
        Filesystem $filesystem
    )
    {

        $this->directoryList = $directoryList;
        $this->readFactory = $readFactory;
        $this->writeFactory = $writeFactory;
        $this->filesystem = $filesystem;
    }


    /**
     * @param $vendorName
     * @param $moduleName
     */

    public function execute($vendorName,$moduleName){


        try {

            $appCodePath = $this->filesystem->getDirectoryRead(AppDirectoryList::APP)->getAbsolutePath();

            $appCodePath .= self::CODE_PATH_NAME . "/";


            $moduleFullName=$vendorName.'_'.$moduleName;

            $moduleDirectory=$appCodePath.$vendorName.'/'.$moduleName;

            $this->creteModuleXmlFile($moduleFullName,$moduleDirectory);

            $this->creteRegistrationFile($moduleFullName,$moduleDirectory);

        }
        catch (\Exception $e)
        {
            throw new $e;
        }
    }


    /**
     * @param $moduleName
     * @param $moduleDirectory
     * @throws \Magento\Framework\Exception\FileSystemException
     */

    public function creteRegistrationFile($moduleName,$moduleDirectory){

        $fileName='registration.php';

        $template=$this->getRegistrationTemplate();

        $templateData = str_replace('%moduleName%', $moduleName, $template);

        $fileWriter = $this->writeFactory->create($moduleDirectory);

        $fileWriter->writeFile($fileName, $templateData);


    }


    /**
     * @param $moduleName
     * @param $moduleDirectory
     * @throws \Magento\Framework\Exception\FileSystemException
     */

    public function creteModuleXmlFile($moduleName,$moduleDirectory){

        $fileName='module.xml';

        $template=$this->getModuleFileTemplate();

        $templateData = str_replace('%moduleName%', $moduleName, $template);

        $moduleDirectory=$moduleDirectory.'/etc';

        $fileWriter = $this->writeFactory->create($moduleDirectory);

        $fileWriter->writeFile($fileName, $templateData);


    }


    /**
     * Returns template
     *
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    private function getRegistrationTemplate(): string
    {
        $read = $this->readFactory->create(__DIR__ . '/');
        $content = $read->readFile('registration.php.dist');
        return $content;
    }


    /**
     * Returns template
     *
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    private function getModuleFileTemplate(): string
    {
        $read = $this->readFactory->create(__DIR__ . '/');
        $content = $read->readFile('module.xml.dist');
        return $content;
    }

}