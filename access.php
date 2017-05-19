<?php

require_once 'html.php';

if (isset($_POST[AgentName]) && isset($_POST["AgentPassword"])) {
  $_SESSION["Agent"] = login($_POST["AgentName"], $_POST["AgentPassword"]);
}

function isin() {
  return isset($_SESSION["Agent"]);
}

function loginForm() {
  return
    form(
      "loginForm",
      div(
        "Please login:".
        table(
          tr(td("Agent:").td(input("AgentName"))).
          tr(td("Password:").td(input("AgentPassword"))).
          tr(td(submit("Ok")))
        )
      )
    );
}

function login($agentName, $agentPassword) {
  return $agentName;  
}

//unset($_SESSION[Agent]);
?>