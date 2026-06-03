<?php

$conn = new mysqli("localhost", "root", "", "oficina");

if ($conn->connect_error){
    die("Erro: " . $conn->connect_error);
}

