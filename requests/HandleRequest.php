<?php

class HandleRequest
{

    private array $requestTypes = ["POST", "GET"];

    private string $requestType;
    private string $requestFile;

    public function __construct() {
        if(!$this->isRequestTypeValid())
            die();

        $this->setRequestType($_SERVER['REQUEST_METHOD']);

        if(!$this->isRequestedFileValid())
            die();

        $this->setRequestFile($_GET['url']);

        $this->callRequestedFile();
    }

    private function setRequestType(string $requestType) {
        $this->requestType = ucwords($requestType);
    }

    private function setRequestFile(string $requestFile) {
        $this->requestFile = $requestFile;
    }

    private function isRequestTypeValid(): bool {
        foreach ($this->requestTypes as $key => $requestType) {
            if ($_SERVER['REQUEST_METHOD'] === $requestType) {
                return true;
            }
        }

        return false;
    }

    private function isRequestedFileValid(): bool {
        $filename = "./requests/{$this->requestType}/{$_GET['url']}.php";

        if(file_exists($filename)) {
            return true;
        }
        return false;
    }

    private function callRequestedFile(): void {
        require_once ("{$this->requestType}/{$_GET['url']}.php");
    }

}