<?php
class ncMemcacheDbSessionStore implements ncDbSessionStore {
  private $memcache;
  private $servers;

  public function __construct($servers) {
    $this->memcache = new Memcache();
    $this->servers = $servers;
  }

  public function set($key, $value, $expire=0) {
    foreach($servers as $server) {
      if(!$this->connect($server)) {
        return false;
      } else {
        $this->memcache->set($key, $value, 0, $expire);
      }
    }
    return true;
  }

  public function get($key) {
    foreach($servers as $server) {
      if($this->connect($server)) {
        $value = $this->memcache->get($key);
        return $value ? $value : null;
      }
    }
    return false;
  }

  private function connect($server) {
    if(array_key_exists('port', $server)) {
      return $this->memcache->connect($server['host'], $server['port']);
    } else {
      return $this->memcache->connect($server['host']);
    }
  }
}
?>
