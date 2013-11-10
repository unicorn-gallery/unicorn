<?php

namespace lib;

// Server functions
class Server {

  public static function page_url() {
    return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
  }

}
?>
