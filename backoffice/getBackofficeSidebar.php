<?php

function getBackofficeSidebar()
{
    return "
    <aside class='w-64 h-screen bg-gray-800 text-gray-100 flex flex-col'>
        <div class='p-6 text-2xl font-bold border-b border-gray-700'>
            Admin Panel
        </div>
        <nav class='flex-1 px-4 py-6 space-y-2'>
            <a href='#' class='block px-4 py-2 rounded hover:bg-gray-700'>Dashboard</a>
            <a href='#' class='block px-4 py-2 rounded hover:bg-gray-700'>Products</a>
            <a href='#' class='block px-4 py-2 rounded hover:bg-gray-700'>Orders</a>
            <a href='#' class='block px-4 py-2 rounded hover:bg-gray-700'>Customers</a>
            <a href='#' class='block px-4 py-2 rounded hover:bg-gray-700'>Reports</a>
            <a href='#' class='block px-4 py-2 rounded hover:bg-gray-700'>Settings</a>
        </nav>
        <div class='p-4 border-t border-gray-700'>
            <button class='w-full px-4 py-2 bg-red-600 rounded hover:bg-red-500'>Logout</button>
        </div>
    </aside>
    ";
}

?>
