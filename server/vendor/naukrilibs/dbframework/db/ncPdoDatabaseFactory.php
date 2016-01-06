<?php
class ncPdoDatabaseFactory implements ncDatabaseFactory {
  public function createDatabase($params) {
    //$ncDnsResolver = new ncDnsResolver();
    //return new ncPdoDatabase($ncDnsResolver->resolveDNS($params['dsn']), $params['username'], $params['password'], array_key_exists('reconnect', $params) && $params['reconnect'] === true, array_key_exists('debug', $params) && $params['debug'] === true);
    return new ncPdoDatabase($params['dsn'], $params['username'], $params['password'], array_key_exists('reconnect', $params) && $params['reconnect'] === true, array_key_exists('debug', $params) && $params['debug'] === true);
  }
}
?>
