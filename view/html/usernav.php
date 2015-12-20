 <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
      </ul>
      <ul class="nav navbar-nav navbar-right">
      <?php if ($user->isAdmin()):?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Admin <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="?action=viewUserList">
              <i class="fa fa-users"></i>
              User List
            </a></li>
          </ul>
        </li>
      <?php endif; ?>
        <li><a href="?action=myProfile"><?php echo $user->username;?></a></li>
        <li><a href="?action=logout">Logout</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
