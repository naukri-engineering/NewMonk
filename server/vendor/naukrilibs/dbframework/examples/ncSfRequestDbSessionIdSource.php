<?php
class ncRequestDbSessionIdSource {
  public function getSessionId() {
    return sfContext::getInstance()->getRequest()->getParameter('sessionId');
  }
}
?>
