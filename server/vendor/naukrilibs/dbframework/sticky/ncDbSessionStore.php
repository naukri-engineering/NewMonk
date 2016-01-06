<?php
interface ncDbSessionStore {
  /**
   * @return boolean true if key is set to value, boolean false otherwise
   */
  function set($key, $value, $expire=0);

  /**
   * @return - value corresponding to key if found
   *         - null if key not found
   *         - boolean false on error
   */
  function get($key);
}
?>
