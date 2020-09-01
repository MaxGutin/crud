
    <meta charset="UTF-8">
    <meta name="author" content="Max Gutin">
    <meta name="description" content="Web-developer Max Gutin. HTML, CSS, PHP, MySQL.">
    <meta name="keywords" content="Max Gutin, web-developer, html, css, php, mysql">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/material.css">

    <!-- Material Design Lite -->
    <script src="https://code.getmdl.io/1.3.0/material.min.js"></script>
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
    <!-- Material Design icon font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">


</head>
<body>
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <header class="mdl-layout__header">
        <div class="mdl-layout__header-row">
            <span class="mdl-layout-title">PHP CRUD</span>
            <div class="mdl-layout-spacer"></div>
            <nav class="mdl-navigation mdl-layout--large-screen-only">
                <a class="mdl-navigation__link" href="/">Main</a>
                <a class="mdl-navigation__link" href="./tasks.php">Tasks</a>
                <a class="mdl-navigation__link" href="./user.php">Profile</a>
                <a class="mdl-list__item-secondary-action" href="/?logout"><i class="material-icons">exit_to_app</i></a>
            </nav>
<!--            <div class="demo-list-action mdl-list">
                <div class="mdl-list__item">
                    <span class="mdl-list__item-primary-content">
                      <i class="material-icons mdl-list__item-avatar">person</i>
                       <span>Administrator</span>
                    </span>
                </div>
            </div>-->
        </div>
    </header>

    <div class="mdl-layout__drawer">
        <span class="mdl-layout-title">PHP CRUD</span>
        <nav class="mdl-navigation">
            <a class="mdl-navigation__link" href="../..">Main</a>
            <a class="mdl-navigation__link" href="./users.php">Users</a>
            <a class="mdl-navigation__link" href="./task_list.php">Tasks</a>
            <a class="mdl-list__item-secondary-action" href="?logout"><i class="material-icons">exit_to_app</i></a>
        </nav>
    </div>
    <main class="mdl-layout__content">
        <div class="page-content">