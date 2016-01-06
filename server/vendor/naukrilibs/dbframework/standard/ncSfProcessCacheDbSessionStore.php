<?php
class ncSfProcessCacheDbSessionStore implements ncDbSessionStore {
  public function set($key, $value, $expire=0) {
    sfProcessCache::set($key, $value, $expire);
  }

  public function get($key) {
    return sfProcessCache::get($key);
  }
}
?>
