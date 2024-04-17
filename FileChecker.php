<?php

class FileChecker
{
    private $uploadedFileName;
    private $fileToUploadSize;
    private $tmpFileName;
    private $maxFileSize;

    public function __construct($file, $allowedMaxFileSize = 15)
    {
        $this->uploadedFileName = $file["name"];
        $this->fileToUploadSize = $file["size"];
        $this->tmpFileName = $file["tmp_name"];
        $this->maxFileSize = $allowedMaxFileSize * 1000000; // Convert MB to bytes
    }

    public function checkFileSize()
    {
        if ($this->fileToUploadSize > $this->maxFileSize) {
            echo "<div class='error'>Sorry, your file is too large.</div>";
            return false;
        }
        return true;
    }

    public function executeCommand()
    {
        // Check file size
        if (!$this->checkFileSize()) {
            return;
        }

        // Create a cURL handle
        $curl = curl_init();

        // Set the POST data
        $postData = array(
            'file' => new CURLFile($this->tmpFileName, '', $this->uploadedFileName)
        );

        // Set cURL options
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://127.0.0.1:5000/json',
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_RETURNTRANSFER => true
        ));

        // Execute the cURL request
        $response = curl_exec($curl);

        // Close cURL session
        curl_close($curl);

        // Display the response
        echo $response;
    }

public function getFileGroupInfo()
{
    // Set up the data to send in the POST request
    $postData = array(
        'file' => new CURLFile($this->tmpFileName, '', $this->uploadedFileName)
    );

    // Initialize cURL session
    $curl = curl_init();

    // Set cURL options
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://127.0.0.1:5000/json', // Adjust URL as needed
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_RETURNTRANSFER => true
    ));

    // Execute the cURL request
    $response = curl_exec($curl);

    // Check for errors
    if ($response === false) {
        // Handle error
        $error = curl_error($curl);
        // Return or handle the error appropriately
        return null;
    }

    // Close cURL session
    curl_close($curl);

    // Decode JSON response
    $resultArray = json_decode($response, true);

    // Extract the value of the "group" field
    $result = json_decode($resultArray['result'], true);
    $group = isset($result[0]['output']['group']) ? $result[0]['output']['group'] : null;

    return $group;
}

}


?>
