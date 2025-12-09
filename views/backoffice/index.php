<!DOCTYPE html>
<html lang="en">

<?php

require_once __DIR__ . '/../shared/getHeader.php';

echo getPageHead('Dashboard', '../..');
?>

<body>

    <div class="flex flex-1 overflow-hidden h-screens">
        <!-- Sidebar -->
        <?php
        require_once __DIR__ . '/getBackofficeSidebar.php';
        echo getBackofficeSidebar('../..', "dashboard");
        ?>
        <!-- Header + Main -->
        <div class="flex flex-col flex-1 overflow-hidden h-screen">
            <?php
            require_once __DIR__ . '/getBackofficeHeader.php';
            echo getBackofficeHeader();
            ?>
            <main class="flex flex-col flex-1 p-5 bg-gray-300 overflow-hidden">
              
            </main>
            <?php
            require_once  __DIR__ . '/../shared/ui/toast.php';
            ?>
            <script>
                // Parse URL query parameters
                const urlParams = new URLSearchParams(window.location.search);

                // Check for success or error
                if (urlParams.get('success') === 'true') {
                    const message = urlParams.get('message') || 'Action completed successfully!';
                    showToast(message, 'success', 'bottom-right');
                } else if (urlParams.get('error') === 'true') {
                    const message = urlParams.get('message') || 'Something went wrong.';
                    showToast(message, 'error', 'bottom-right');
                }

                // Remove query parameters from URL without reloading
                if (urlParams.has('success') || urlParams.has('error')) {
                    const newUrl = window.location.pathname; // keeps same path, removes query
                    window.history.replaceState({}, document.title, newUrl);
                }
            </script>

        </div>
    </div>

    <?php
    require_once __DIR__ . '/../shared/getScripts.php';
    echo getScripts('../..');
    ?>
    
</body>

</html>