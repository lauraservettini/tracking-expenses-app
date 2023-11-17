<!DOCTYPE html>
<html lang="en">

<?php include $this->resolve("partials/_head.php"); ?>

<body class="bg-indigo-50 font-['Outfit']">
    <!-- Start Header -->
    <?php include $this->resolve("partials/_header.php"); ?>
    <!-- End Header -->

    <!-- Start Main Content Area -->
    <section class="container mx-auto mt-12 p-4 bg-white shadow-md border border-gray-200 rounded">
        <div class="flex items-center justify-between border-b border-gray-200 pb-4">
            <h4 class="font-medium">Users List</h4>
            <div class="flex space-x-4">
            </div>
        </div>
        <!-- Search Form -->
        <form method="GET" class="mt-4 w-full">
            <div class="flex">
                <input name="s" type="text" value="<?php echo escape((string) $searchTerm); ?>" class="w-full rounded-l-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Enter search term" />
                <button type="submit" class="rounded-r-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Search
                </button>
            </div>
        </form>
        <!-- Transaction List -->
        <table class="table-auto min-w-full divide-y divide-gray-300 mt-6">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-4 text-left text-sm font-semibold text-gray-900">
                        Email
                    </th>
                    <th class="p-4 text-left text-sm font-semibold text-gray-900">
                        Age
                    </th>
                    <th class="p-4 text-left text-sm font-semibold text-gray-900">
                        Country
                    </th>
                    <th class="p-4 text-left text-sm font-semibold text-gray-900">
                        Social Media URL
                    </th>
                    <th class="p-4 text-left text-sm font-semibold text-gray-900">
                        Created date
                    </th>
                </tr>
            </thead>
            <!-- Transaction Table Body -->
            <tbody class="divide-y divide-gray-200 bg-white">
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <!-- Email -->
                        <td class="p-4 text-sm text-gray-600">
                            <a href="/admin/users/<?php echo escape($user['id']); ?>" class="flex items-center p-2 bg-sky-50 text-xs text-sky-900 hover:bg-sky-500 hover:text-white transition rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 inline-block">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                                <?php echo escape($user['email']); ?>
                            </a>
                        </td>
                        <!-- Age -->
                        <td class="p-4 text-sm text-gray-600"><?php echo escape($user['age']); ?></td>
                        <!-- Country -->
                        <td class="p-4 text-sm text-gray-600"><?php echo escape($user['country']); ?></td>
                        <!-- Social Medial URL -->
                        <td class="p-4 text-sm text-gray-600"><?php echo escape($user['social_media_url']); ?></td>
                        <!-- Created date -->
                        <td class="p-4 text-sm text-gray-600 flex justify-center space-x-2"><?php echo escape($user['formatted_date']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <nav class="flex items-center justify-between border-t border-gray-200 px-4 sm:px-0 mt-6">
            <!-- Previous Page Link -->
            <div class="-mt-px flex w-0 flex-1">
                <?php if ($currentPage > 1) : ?>
                    <a href="/admin/users/?<?php echo escape($previousPageQuery); ?>" class="inline-flex items-center border-t-2 border-transparent pr-1 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                        <svg class="mr-3 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M18 10a.75.75 0 01-.75.75H4.66l2.1 1.95a.75.75 0 11-1.02 1.1l-3.5-3.25a.75.75 0 010-1.1l3.5-3.25a.75.75 0 111.02 1.1l-2.1 1.95h12.59A.75.75 0 0118 10z" clip-rule="evenodd" />
                        </svg>
                        Previous
                    </a>
                <?php endif; ?>
            </div>
            <!-- Pages Link -->
            <div class="hidden md:-mt-px md:flex">
                <?php foreach ($pageLinks as $pageNum => $pageLink) : ?>
                    <a href="/admin/users/?<?php echo escape($pageLink); ?>" class="<?php echo $pageNum + 1 === $currentPage ? "border-indigo-500 text-indigo-600" : "border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300"; ?> inline-flex items-center border-t-2 px-4 pt-4 text-sm font-medium">
                        <?php echo $pageNum + 1; ?>
                    </a>
                <?php endforeach; ?>
                <!-- Current: "border-indigo-500 text-indigo-600", Default: "border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" -->
            </div>
            <!-- Next Page Link -->
            <div class="-mt-px flex w-0 flex-1 justify-end">
                <?php if ($currentPage < $lastPage) : ?>
                    <a href="/admin/users/?<?php echo escape($nextPageQuery); ?>" class="inline-flex items-center border-t-2 border-transparent pl-1 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                        Next
                        <svg class="ml-3 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M2 10a.75.75 0 01.75-.75h12.59l-2.1-1.95a.75.75 0 111.02-1.1l3.5 3.25a.75.75 0 010 1.1l-3.5 3.25a.75.75 0 11-1.02-1.1l2.1-1.95H2.75A.75.75 0 012 10z" clip-rule="evenodd" />
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
        </nav>
    </section>
    <!-- End Main Content Area -->

    <!-- Footer -->
    <?php include $this->resolve("/partials/_footer.php"); ?>
    <!-- End Footer -->
</body>

</html>