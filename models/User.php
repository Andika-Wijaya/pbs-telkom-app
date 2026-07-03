<?php
class User {
    private $username = "admin";
    private $password = "123";

    public function login($u, $p) {
        return ($u === $this->username && $p === $this->password);
    }
}
