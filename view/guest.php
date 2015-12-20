<div class="page-header">
  <h1>Register or sign in below</h1>
</div>

<div class="row">
  <div class="col-md-6">
    <h2>Register</h2>
<form method="POST" action="?action=register">
  <div class="form-group">
    <label for="username">Username</label>
    <input type="text" class="form-control" id="username" placeholder="Username" name="username">
  </div>
  <div class="form-group">
    <label for="password">Password</label>
    <input type="password" class="form-control" id="password" placeholder="Password" name="password">
  </div>
  <div class="form-group">
    <label for="password2">Password Again</label>
    <input type="password" class="form-control" id="password2" placeholder="Password Again" name="password2">
  </div>
  <div class="form-group">
    <label for="email">Email Address</label>
    <input type="email" class="form-control" id="email" placeholder="Email Address" name="email">
  </div>
  <button type="submit" class="btn btn-success">Register</button>
</form>
</div>
<div class="col-md-6">
  <h2>Login</h2>

<form method="POST" action="?action=login">
  <div class="form-group">
    <label for="username">Username</label>
    <input type="text" class="form-control" id="username" placeholder="Username" name="username">
  </div>
  <div class="form-group">
    <label for="password">Password</label>
    <input type="password" class="form-control" id="password" placeholder="Password" name="password">
  </div>
  <button type="submit" class="btn btn-success">Login</button> <a href="?action=forgotPassword" class="btn btn-link pull-right">Forgot your password?</a>
</form>
</div>
</div>
