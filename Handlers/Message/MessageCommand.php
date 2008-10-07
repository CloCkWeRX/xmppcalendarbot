<?php
interface MessageCommand {
    public function exec($conn, $args, $data);
}