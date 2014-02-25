{include file='inc.header.tpl'}


<div class="panel panel-default">
  <div class="panel-heading">Settings</div>
  <div class="panel-body">
    <ul>
      <li>
        <strong>get(<i>key</i>)</strong>
        <p>Returns the object/array/string/integer/boolean or what ever is stored in the settings.json for the given <i>key</i>.</p>
      </li>
      <li>
        <strong>getDBCredentials()</strong>
        <p>Returns the database credentials
          <pre>{literal}{
    "host" : "127.0.0.1",
    "port" : 3306,
    "username" : "admin",
    "password" : "abc1"
  }{/literal}</pre>
        </p>
      </li>
      <li>
        <strong>userByEmail(<i>email</i>)</strong>
        <p>Returns the user data for the email address.</p>
      </li>
    </ul>
  </div>
</div>

<div class="panel panel-default">
  <div class="panel-heading">Session</div>
  <div class="panel-body">
    <ul>
      <li>
        <strong>getSessionValue(<i>key</i>)</strong>
        <p>Returns the object/array/string/integer/boolean or what ever is stored for that <i>key</i> in the current user session.</p>
      </li>
      <li>
        <strong>setSessionValue(<i>key</i>, <i>value</i>)</strong>
        <p>Stores the given value under the given key in the current session.</p>
      </li>
      <li>
        <strong>getUser()</strong>
        <p>Returns the user data for the currently logged in user.</p>
      </li>
      <li>
        <strong>init()</strong>
        <p>Initialize the session. Call the method before any other method to be sure that everything is initalized.</p>
      </li>
      <li>
        <strong>isLoggedIn()</strong>
        <p>Returns true, if the user is authorized and correctly logged in.</p>
      </li>
      <li>
        <strong>redirectTo(<i>url</i>)</strong>
        <p>Redirects to the given URL immediately.</p>
      </li>
    </ul>
  </div>
</div>

{include file='inc.footer.tpl'}