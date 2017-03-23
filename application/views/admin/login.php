<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><html>
<head>
    <meta charset="UTF-8">
    <title>관리자</title>
</head>
<body>
    <?php echo validation_errors(); ?>
    <?php echo form_open('admin/login'); ?>
        <label for="username">Username:</label>
        <input type="text" size="20" id="username" name="username"/>
        <br/>
        <label for="password">Password:</label>
        <input type="password" size="20" id="password" name="password"/>
        <br/>
        <input type="submit" value="Login"/>
    </form>
</body>
</html>