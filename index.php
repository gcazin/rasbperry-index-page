<?php
$folders = array_filter(glob('*'), 'is_dir');

// Get an array of all files in the current directory.
// Edit to use whatever location you need
$dir = scandir(__DIR__);

$newest_file = null;
$mdate = null;

// Loop over files in directory and if it is a subdirectory and
// its modified time is greater than $mdate, set that as the current
// file.
foreach ($dir as $file) {
    // Skip current directory and parent directory
    if ($file == '.' || $file == '..') {
        continue;
    }
    if (is_dir(__DIR__.'/'.$file)) {
        if (filemtime(__DIR__.'/'.$file) > $mdate) {
            $directories[filemtime(__DIR__.'/'.$file)] = [
                'name' => $file,
            ];
        }
    }
}

$execution_time = ((microtime(true) -  microtime(true)) / 60) * 1000;
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Labo - Serveur web</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Martel+Sans:wght@200;300;400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Martel Sans', sans-serif;
        }
        input::before {
            content: '>';
            display: block;
        }
        .hr::before {
            content: '+';
            display: block;
            position: absolute;
            top: 50%;
            left: -5px;
            transform: translateY(-50%);
            font-size: 18px;
            color: rgb(75 85 99 / var(--tw-bg-opacity))
        }
        .hr::after {
            content: '+';
            display: block;
            position: absolute;
            top: 50%;
            right: -5px;
            transform: translateY(-50%);
            font-size: 18px;
            color: rgb(75 85 99 / var(--tw-bg-opacity))
        }
        /* The Modal (background) */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }

        /* Modal Content/Box */
        .modal-content {
            margin: 7% auto; /* 15% from the top and centered */
            width: 40%; /* Could be more or less, depending on screen size */
        }

        /* The Close Button */
        .close {
            float: right;
        }

        .close:hover,
        .close:focus {
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="flex flex-col lg:flex-row h-screen overflow-auto lg:overflow-hidden w-100">
    <!-- Sidebar -->
    <div class="bg-neutral-800 w-100 lg:w-32">
        <div class="container mx-auto lg:container-none flex flex-col text-center justify-center">
            <a href="<?= $_SERVER['REQUEST_URI'] ?>" class="text-3xl !bg-transparent p-8">
                <ion-icon class="text-white" name="code-slash-outline"></ion-icon>
            </a>
            <ul class="text-white text-center">
                <li onclick="openPage('home', this)" id="homeOpen" class="tablink cursor-pointer p-5 text-xl">
                    <ion-icon name="folder-outline"></ion-icon>
                    <p class="text-xs uppercase">Accueil</p>
                </li>
                <li onclick="openPage('stats', this)" id="statsOpen" class="tablink cursor-pointer p-5 text-xl">
                    <ion-icon name="pie-chart-outline"></ion-icon>
                    <p class="text-xs uppercase">Statistiques</p>
                </li>
                <li onclick="openPage('info', this)" id="infoOpen" class="tablink cursor-pointer p-5 text-xl">
                    <ion-icon name="settings-outline"></ion-icon>
                    <p class="text-xs uppercase">Paramètres</p>
                </li>
            </ul>
        </div>
    </div>
    <!-- Contenu -->
    <div class="flex-1 bg-neutral-900 px-16">
        <!-- Menu -->
        <div class="flex justify-between items-center py-7">
            <div>
                <span class="text-white text-2xl">Serveur web</span>
            </div>
            <button id="myBtn" class="hover:!text-blue-500 text-white transition duration-200 cursor-pointer">
                <ion-icon class="text-2xl align-text-top mr-2" name="cloud-upload-outline"></ion-icon>
            </button>
        </div>
        <!-- Page home -->
        <div class="tabcontent mx-auto" id="home">
            <?php if (isset($_POST)): ?>
                <?php if (isset($_POST['commands'])) : ?>
                    <div class="px-3 py-2 bg-gray-900 text-white rounded-lg mb-5">
                        <?php
                        $command = $_POST['commands'];
                        $stream = shell_exec($command);
                        ?>
                        <pre><?= $stream ?></pre>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <div class="flex mx-auto">
                <div class="flex-1 text-right">
                </div>
                <!-- Modal -->
                <div id="myModal" class="modal">

                    <!-- Modal content -->
                    <div class="modal-content text-white rounded-lg shadow-lg p-5" style="background: #21212a">
                        <div class="flex justify-between mb-5">
                            <p class="text-xl">Exécuter une commande</p>
                            <span class="close font-bold text-xl">&times;</span>
                        </div>
                        <div class="hr relative bg-gray-700 h-px w-full rounded-full mb-5"></div>
                        <div class="content">
                            <form action="index.php" method="post">
                                <div class="form-group mb-5">
                                    <label class="text-white mb-2 block" for="commands">Commande a exécuter sur le serveur</label>
                                    <input type="text" id="commands" name="commands" class="bg-transparent w-full py-2 text-black" placeholder="ls -l, laravel new Blog...">
                                </div>
                                <div class="hr relative bg-gray-700 h-px w-full rounded-full mb-5"></div>
                                <div class="form-group mt-5 text-right">
                                    <button type="submit" class="bg-blue-800 px-3 py-2 rounded text-white"><ion-icon class="text-xl align-middle" name="terminal-outline"></ion-icon> Exécuter</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

            <div class="hr relative bg-gray-700 h-px w-full rounded-full mb-5"></div>

            <div class="grid lg:grid-cols-5 gap-5">
                <?php foreach ($directories as $folder): ?>
                    <div
                            class="flex flex-col rounded-lg shadow text-white hover:!bg-blue-900 transition duration-500 cursor-pointer"
                            onclick="document.location.href = '<?= $folder['name'] ?>'"
                            style="background: #21212a"
                    >
                        <div class="px-5 pt-5">
                            <ion-icon class="text-3xl text-gray-400 mb-0" name="folder"></ion-icon>
                        </div>
                        <div class="p-5">
                            <p class="text-2xl font-medium"><?= ucfirst($folder['name']) ?></p>
                        </div>
                        <div class="text-sm text-gray-500 px-5 pb-5 rounded mr-1">
                            10 fichiers
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Stats -->
        <div class="tabcontent mx-auto" id="stats">
            <h1 class="text-xl text-white pb-5">Statistiques</h1>
            <div class="text-center">
                <span>
                <ion-icon class="align-middle" name="server-outline"></ion-icon> <?=  number_format(disk_free_space("/") / 1024 / 1024 / 1024, 2) ?>GB restants / <?= number_format(disk_total_space("/") / 1024 / 1024 / 1024, 2) ?>GB</span>
            </div>
            <div class="text-right h-100 sticky bottom-0 right-0 mb-1 mr-1">
                <span class="text-xs text-gray-400"><?= $_SERVER['HTTP_HOST'] ?> (<?= $_SERVER['DOCUMENT_ROOT'] ?>) - <?= $_SERVER['HTTP_USER_AGENT'] ?> sur <?= $_SERVER['SERVER_SOFTWARE'] ?> en <?= number_format($execution_time, 10) ?>ms</span>
            </div>
        </div>

        <!-- Page info -->
        <div class="tabcontent mx-auto" id="info">
            <h1 class="text-xl text-white pb-5">Informations</h1>
            <div class="text-center">
                <?= phpinfo() ?>
            </div>
        </div>
    </div>
</div>
<script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
<script>
    // Get the modal
    let modal = document.getElementById("myModal");

    // Get the button that opens the modal
    let btn = document.getElementById("myBtn");

    // Get the <span> element that closes the modal
    let span = document.getElementsByClassName("close")[0];

    // When the user clicks on the button, open the modal
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }
</script>
<script>
    function openPage(pageName, element = null) {
        let i, tabcontent, tablinks
        tabcontent = document.getElementsByClassName("tabcontent")
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none"
        }
        tablinks = document.getElementsByClassName("tablink")
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.add('text-gray-400')
            tablinks[i].classList.remove('border-r-2', '!text-white')
        }
        document.getElementById(pageName).style.display = "block"
        document.getElementById(pageName).style.webkitOverflowScrolling = "touch"
        const urlParams = new URLSearchParams(window.location.search)

        if (urlParams) {
            if (!urlParams.has('page')) {
                const url = new URL(window.location)
                url.searchParams.set('page', 'home')
                window.history.pushState({page: 'home'}, '', url)
                document.getElementById('homeOpen').click()
            } else {
                const url = new URL(window.location)
                url.searchParams.set('page', pageName)
                window.history.pushState({page: pageName}, '', url)
                document.getElementById(`${pageName}Open`).click()
            }
        }

        console.log('Element', element)
        if (!element) {
            element = document.getElementById('homeOpen')
            element.classList.add('border-r-2', '!text-white')
        } else {
            element = document.getElementById(`${pageName}Open`)
            element.classList.add('border-r-2', '!text-white')
        }
    }

    window.addEventListener('DOMContentLoaded', () => {
        if (!(new URLSearchParams(window.location.search)).has('page')) {
            openPage('home')
        } else {
            openPage((new URLSearchParams(window.location.search)).get('page'))
        }
    })
</script>
</body>
</html>
