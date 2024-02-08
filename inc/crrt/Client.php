<?php

class Client {
    private string $phone;
    private string $email;
    private string $dateOfBirth;
    private array $locations;
    private string $flightNumber;
    private string $agreeTerms;

    public function __construct(string $phone, string $email, string $dateOfBirth, string $agreeTerms, string $flightNumber, array $locations) {
        $this->phone = $phone;
        $this->email = $email;
        $this->dateOfBirth = $dateOfBirth;
        $this->locations = $locations;
        $this->flightNumber = $flightNumber;
        $this->agreeTerms = $agreeTerms;
    }

    public function getPhone() {
        return $this->phone;
    }
    public function getEmail() {
        return $this->email;
    }
    public function getDateOfBirth() {
        return $this->dateOfBirth;
    }
    public function getAgreeTerms() {
        return $this->agreeTerms;
    }
    public function getFlightNumber() {
        return $this->flightNumber;
    }
    public function getLocations() {
        return $this->locations;
    }
}
