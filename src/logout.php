<?php
session_start();
require 'sessioni.php';

chiudiSessione();

session_destroy();

header("Location: login.php");
exit;
